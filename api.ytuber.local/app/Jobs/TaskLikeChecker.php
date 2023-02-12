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
use Carbon\Carbon;
use Config;

class TaskLikeChecker implements ShouldQueue
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
        //Пользователь
        $user = \App\Models\User::find($this->user->id);
        //Log::debug('Эта пользователь '. $user);
        //Log::debug('Эта его канал '. $user->activeAccount);

        //$activeAccount = $user->activeAccount;

        if(!$user->activeAccount){
            if(!$user->channel && $user->activeAccount && $user->activeAccount->url){
                $check = parse_url($user->activeAccount->url);
                $channel_id = Str::replaceFirst('/channel/', '', $check['path']);
                $user->channel = $channel_id;
                $user->update();
            }
            else{
                return "no channel";
            }
            return "no channel";
        }
        //Текущая задача
        $currentTask = \App\Models\Task::find($this->task->id);

        if(!$currentTask){
            return "task not founded";
        }

        //получаем старое число лайков
        $status = Cache::store('redis')->get('status_' . $this->task->id);

        //берем новое число лайков
        $videoId = Youtube::parseVidFromURL($this->task->url);
        $likesCount = Youtube::getVideoInfo($videoId)->statistics->likeCount;

/*        if(!$status){
            $user->increment('failed',1);
            return "wrong hash";
        }*/

        if(!$likesCount){
            return "complete not founded";
        }

        $complete = \App\Models\Completions::where('task_id', $this->task->id)->where('account_id', $user->activeAccount->id)->first();

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
        if($likesCount > $complete->youtube){


            $complete->status = 4;
            $complete->youtube_now = $likesCount;
            $complete->update();

            //Зачисляем баллы

            $refUser = \App\Models\User::find($this->user->referrer_id);

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

            $data_for_broadcast = [
                'yt_channel'=>$user->activeAccount->url,
                'text'=>'Выполнил лайк',
                'icon'=>'icon-thumbs-up2 mr-2 icon',
            ];

            CompleteForPublic::dispatch($data_for_broadcast);

        }
        else{
            //статус не выполнено
            $complete = \App\Models\Completions::where('user_id', $this->user->id)->where('task_id', $this->task->id)->first();
            $complete->youtube_now = $likesCount;
            $complete->status = 5;
            $complete->update();

            // счетчик fail
            $currentTask->increment('action_fail',1);
            $user->increment('failed',1);
        }
        //записываем лог
        //Log::debug('Эта задача будет проверенна '. $this->task->id . ' Количество лайков было: ' . $status[2] .' Количество лайков стало '.$likesCount);
    }
}
