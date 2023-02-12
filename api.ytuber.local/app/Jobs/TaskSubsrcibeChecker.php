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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Events\CompleteForPublic;
use Config;
use Carbon\Carbon;

class TaskSubsrcibeChecker implements ShouldQueue
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
        $currentTask = \App\Models\Task::find($this->task->id);

        //получаем старое число подписок
        //$status = Cache::store('redis')->get('status_' . $this->task->id);

        $channel_url = parse_url($this->task->url);
        //берем id канала
        $channel_id = Str::replaceFirst('/channel/', '', $channel_url['path']);

        //Устанавливаем случайный api ключ
        $arrkeys = config('youtube.keys');
        Youtube::setApiKey($arrkeys[array_rand($arrkeys)]);

        //получаем данные api канала как объект
        $channel = Youtube::getChannelById($channel_id);
        $subsribersCount = $channel->statistics->subscriberCount;

        $user = \App\Models\User::with('activeAccount')->find($this->user->id);

        if(!$subsribersCount){
            return "not founded";
        }
/*        if(!$status || !$status[2]){
            $user->increment('failed',1);
            return "failed";
        }*/
        if(!$subsribersCount){
            return "complete not founded";
        }

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
        if($subsribersCount > $complete->youtube && $complete->status != 4) {

            
            if(!$user->channel){
                return "no channel";
            }

            $refUser = \App\Models\User::find($this->user->referrer_id);


            $complete->status = 4;
            $complete->youtube_now = $subsribersCount;
            $complete->account_id = $user->activeAccount->id;
            $complete->update();

            //Зачисляем баллы
            $actionCost = $this->task->action_cost;

            //начисляем пользователю
            $user->increment('balance',$actionCost * 0.75);
            $user->increment('done',1);


            //аккаунта баланса системы
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
                //$Account_payment->increment('earned',$actionCost * 0.25);
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
                'text'=>'Подписался на канал',
                'icon'=>'icon-user-check mr-2 icon',
            ];

            $data_for_broadcast = [
                'yt_channel'=>$user->activeAccount->url,
                'text'=>'Подписался на канал',
                'icon'=>'icon-bubble-lines4 mr-2 icon',
            ];

            CompleteForPublic::dispatch($data_for_broadcast);

        }
        else{
            //статус не выполнено
            $complete = \App\Models\Completions::where('user_id', $this->user->id)->where('task_id', $this->task->id)->first();
            $complete->status = 5;
            $complete->youtube_now = $subsribersCount;
            $complete->update();

            // счетчик fail
            $currentTask->increment('action_fail',1);
        }

        //записываем лог
        // Log::debug('Эта задача будет проверенна '. $this->task->id . ' Количество подписок было: ' . $status[2] .' Количество подписок стало '.$subsribersCount);
    }
}
