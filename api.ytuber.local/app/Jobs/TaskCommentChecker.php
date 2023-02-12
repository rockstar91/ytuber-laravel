<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Alaouy\Youtube\Facades\Youtube;
use App\Events\CompleteForPublic;
use Illuminate\Support\Facades\Cache;
use Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TaskCommentChecker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $task;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $task)
    {
        $this->user = $user;
        $this->task = $task;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = \App\Models\User::with('activeAccount')->find($this->user->id);
        if(!$user->channel){
            return "no channel";
        }
        
        $refUser = \App\Models\User::find($this->user->referrer_id);

        //Текущая задача
        $currentTask = \App\Models\Task::find($this->task->id);
        $videoId = Youtube::parseVidFromURL($currentTask->url);
        $commentForUser = \App\Models\TasksComments::where('task_id',$currentTask->id)->where('account_id',$user->activeAccount->id)->first();

        //ссылка на канал
        $check = parse_url($user->activeAccount->url);

        //берем id канала
        $channel_id = Str::replaceFirst('/channel/', '', $check['path']);

        $status = false;

        //конфиг апи ключей
        $arrkeys = config('youtube.keys');
        $apikey = $arrkeys[array_rand($arrkeys)];


        $apiUrl = 'https://youtube.googleapis.com/youtube/v3/commentThreads?part=snippet&order=time&videoId=' . $videoId . '&key=' . $apikey;
        $comments = Http::get($apiUrl);

        foreach($comments['items'] as $c){
            if($c['snippet']['topLevelComment']['snippet']['authorChannelId']['value'] == $channel_id){
                if($c['snippet']['topLevelComment']['snippet']['textDisplay'] == $commentForUser->comment_text){
                    $status = true;
                }
            }
        }

        $complete = \App\Models\Completions::where('user_id', $this->user->id)->where('task_id', $this->task->id)->first();
        
        if($complete->status == 4){
            return "completed already";
        }
        //меняем статус
        if($status  && $complete->status != 4) {

            $complete->status = 4;
            $complete->update();

            //Зачисляем баллы

            $actionCost = $this->task->action_cost;

            //начисляем пользователю
            $user->increment('balance',$actionCost * 0.75);
            $user->increment('done',1);

            //аккаунт баланса системы
            $Account_payment = \App\Models\User::find(2);

            if($refUser){
                //начисляем реферу
                $refUser->increment('balance',$actionCost * 0.15);
                $refUser->increment('earned',$actionCost * 0.15);

                //начисляем системе
                $Account_payment->increment('balance',$actionCost * 0.10);
                //$Account_payment->increment('earned',$actionCost * 0.10);
            }
            else{
                //начисляем системе
                $Account_payment->increment('balance',$actionCost * 0.25);
                // $Account_payment->increment('earned',$actionCost * 0.25);
            }

            //Выключаем задачу
            if($currentTask->total_cost<= 0 || $currentTask->total_cost <= $actionCost){
                $currentTask->disabled = true;
                $currentTask->update();
            }

            // снимаем баллы с задачи
            $currentTask->decrement('total_cost',$actionCost);
            $currentTask->increment('action_done',1);

            //Записываем в Notifications
            $Notification = new \App\Models\Notifications;
            $Notification->user_id = $this->user->id;
            $Notification->task_id = $this->task->id;
            $Notification->task_type_id = $this->task->type_id;
            $Notification->task_target_time = $this->task->targetTime;
            $Notification->data = "";
            $Notification->cost = $actionCost * 0.75;
            $Notification->type = 1;
            $Notification->created_at = Carbon::now();
            $Notification->save();

            $commentForUser->status = 2;
            $commentForUser->update();

            $data_for_broadcast = [
                'yt_channel'=>$user->activeAccount->url,
                'text'=>'Выполнил комментарий',
                'icon'=>'icon-user-check mr-2 icon',
            ];

            CompleteForPublic::dispatch($data_for_broadcast);

        }
        else{
            //статус не выполнено
            $complete = \App\Models\Completions::where('user_id', $this->user->id)->where('task_id', $this->task->id)->first();
            $complete->status = 5;
            $complete->update();

            // счетчик fail
            $currentTask->increment('action_fail',1);
            $commentForUser->status = 0;
            $commentForUser->account_id = null;
            $commentForUser->update();
        }

        //записываем лог
        //Log::debug('Эта задача будет проверенна '. $this->task->id . ' Количество лайков было: ' . $status[2] .' Количество лайков стало '.$likesCount);
    }
}
