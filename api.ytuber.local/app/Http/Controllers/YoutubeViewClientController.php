<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Completions;
use App\Events\CompleteForPublic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;

class YoutubeViewClientController extends Controller
{
    public function complete(Request $request){

        $date = Carbon::now('UTC');
        $day = $date->day;
        $month = $date->month;
        $year = $date->year;

        $user = auth::user();


        if($user->failed >3000){
            $user->banned = true;
            $user->update();
            try{
                auth::logout();
                return "user banned";
            }
            catch (\Exception $e) {}
        }

        $passphrase = "6fa918f20226cb08aa609a4f425f6d85";

        $jsondata = $request->all();

        try {
            $salt = hex2bin($jsondata["eklemento"]);
            $iv  = hex2bin($jsondata["deteshpek"]);
        } catch(Exception $e) { return null; }

        $ciphertext = base64_decode($jsondata["zalypytra"]);
        $iterations = 999; //same as js encrypting

        $key = hash_pbkdf2("sha512", $passphrase, $salt, $iterations, 64);

        $decrypted= openssl_decrypt($ciphertext , 'aes-256-cbc', hex2bin($key), OPENSSL_RAW_DATA, $iv);

        $dateView = explode('#',$decrypted);

        if(!$user->channel || !$user){
            return "no channel";
        }
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

        $date_month = $dateView[0];
        $date_user_id = $dateView[1];
        $date_day = $dateView[2];
        $date_year = $dateView[3];
        $date_task_id = $dateView[4];
        $date_active_channel_id = $dateView[5];

        $task = \App\Models\Task::find($date_task_id);
        $account = \App\Models\Account::find($date_active_channel_id);

        if(!$account){
            return "wrong hash";
        }
        if(!$task) {
            $user->increment('failed', 1);
            return "wrong hash";
        }

        $checkRecapcha = Cache::store('redis')->get('recaptcha_'.$user->id);

        if(!$checkRecapcha) {
            return "recaptcha";
        }

        $completion = Completions::where('task_id',$task->id)->where('user_id',$user->id)->first();

        //Выключаем задачу
        if($task->total_cost<= 0 || $task->total_cost <= $task->action_cost || $task->disabled){
            $task->disabled = true;
            $task->update();
            return "wrong hash";
        }

        if(!$completion && $task && $task->type_id == 1 && $account && $user->id == $date_user_id && $date_month == $month && $date_day == $day && $date_year == $year){

            $refUser = \App\Models\User::find($user->referrer_id);

            $actionCost = $task->action_cost;

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
            if($task->total_cost<= 0 || $task->total_cost <= $actionCost){
                $task->disabled = true;
                $task->update();
            }
            // снимаем баллы с задачи
            $task->decrement('total_cost',$actionCost);
            $task->increment('action_done',1);

            //Записываем в Notifications
            $Notification = new \App\Models\Notifications;
            $Notification->user_id = $user->id;
            $Notification->task_id = $task->id;
            $Notification->task_type_id = $task->type_id;
            $Notification->task_target_time = $task->targetTime;
            $Notification->data = "";
            $Notification->cost = $actionCost * 0.75;
            $Notification->type = 1;
            $Notification->created_at = Carbon::now();
            $Notification->save();

            //Создаём запись в выполнениях
            $complete = new Completions;
            $complete->task_id = $task->id;
            $complete->user_id = $user->id;
            $complete->type_id = $task->type_id;
            $complete->ip_bin = inet_pton($request->ip);
            $complete->action_cost = $actionCost * 0.75;
            $complete->account_id = $account->id;
            $complete->cost_rule = 1;
            $complete->youtube = null;
            $complete->time = 0;
            $complete->check_count = 0;
            $complete->check_time = 0;
            $complete->expires = 0;
            $complete->data = 0;
            $complete->domain = 1;
            $complete->status = 4;
            $complete->save();


            $date_complete = [
                'points'=>$actionCost * 0.75,
                'name'=>$task->name,
                'url'=>$task->url,
                'message'=>'Выполнен просмотр ролика',
            ];

            $data_for_broadcast = [
                'yt_channel'=>$user->activeAccount->url,
                'text'=>'Выполнил просмотр',
                'icon'=>'icon-youtube mr-2 icon',
            ];
            CompleteForPublic::dispatch($data_for_broadcast);
            return $date_complete;
        }
        else{
            return "wrong hash";
        }

    }
}
