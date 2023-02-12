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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Events\CompleteForPublic;
use Config;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class TaskCommentLikeChecker implements ShouldQueue
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
        //Текущая задача
        $currentTask = \App\Models\Task::find($this->task->id);

        //Берем случайный api ключи из конфига
        $arrkeys = config('youtube.keys');

        //получаем старое число лайков
        //$status = Cache::store('redis')->get('status_' . $this->task->id);

        //берем новое число лайков
        $apiRequest = 'https://youtube.googleapis.com/youtube/v3/commentThreads?part=snippet&id='. $this->task->extend['id'] . '&key=' . $arrkeys[array_rand($arrkeys)];
        $response = Http::get($apiRequest)->json();

        $commentLikeCount = $response['items'][0]['snippet']['topLevelComment']['snippet']['likeCount'];

        $user = \App\Models\User::with('activeAccount')->find($this->user->id);

/*        if(!$status || !$status[2]){
            $user->increment('failed',1);
            return "failed";
        }*/

        $complete = \App\Models\Completions::where('user_id', $user->id)->where('task_id', $this->task->id)->first();

        if(!$complete){
            return "complete not founded";
        }

        if(!$complete->youtube){
            $complete->delete();
            return "complete not founded";
        }
        if($complete->status == 4){
            return "completed already";
        }
        //меняем статус
        if($commentLikeCount > $complete->youtube && $complete->status != 4) {

            $complete->status = 4;
            $complete->youtube_now = $commentLikeCount;
            $complete->update();

            //Зачисляем баллы


            
            if(!$user->channel){
                return "no channel";
            }
            $refUser = \App\Models\User::find($user->referrer_id);

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
            $Notification->user_id = $user->id;
            $Notification->task_id = $this->task->id;
            $Notification->task_type_id = $this->task->type_id;
            $Notification->task_target_time = $this->task->targetTime;
            $Notification->data = "";
            $Notification->cost = $actionCost * 0.75;
            $Notification->type = 1;
            $Notification->created_at = Carbon::now();
            $Notification->save();

            $data_for_broadcast = [
                'yt_channel'=>$user->activeAccount->url,
                'text'=>'Выполнил лайк к комментарию',
                'icon'=>'icon-bubbles10 mr-2 icon',
            ];

            CompleteForPublic::dispatch($data_for_broadcast);

        }
        else{
            //статус не выполнено
            $complete = \App\Models\Completions::where('user_id',$user->id)->where('task_id', $this->task->id)->first();
            $complete->status = 5;
            $complete->update();

            // счетчик fail
            $currentTask->increment('action_fail',1);
        }

        //записываем лог
        //Log::debug('Эта задача будет проверенна '. $this->task->id . ' Количество лайков было: ' . $status[2] .' Количество лайков стало '.$likesCount);
    }
}
