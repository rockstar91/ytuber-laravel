<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\NotificationSender;
use Illuminate\Support\Facades\Auth;
use Response;
use Carbon\Carbon;
use App\Models\Completions;

class DashboardController extends Controller
{
    public function getDailyBonus(){
        $user = Auth::user();
        $likes = 0;
        $views = 0;
        $subscribes = 0;
        $comments = 0;
        $completed_tasks = \App\Models\Notifications::where('user_id',$user->id)->with('TaskType')->whereDate('created_at', Carbon::today())->get();
        foreach($completed_tasks as $c){

            if($c.task_type.name=="YT Лайки"){
                $likes++;
            }
            if($c.task_type.name=="YT Просмотры"){
                $views++;
            }
            if($c.task_type.name=="YT Подписчики"){
                $subscribes++;
            }
            if($c.task_type.name=="YT Комментарии"){
                $comments++;
            }
        }
        if($completed_tasks && $likes>=15 && $subscribes>=3 && $comments>=5 && $views>=45){
            $check = \App\Models\Notifications::whereDate('created_at', Carbon::today())->where('type' == 5)->get();
            if(!$check){

                //создаем уведомление
                $notification = new \App\Models\Notifications;
                $notification->task_id = 0;
                $notification->task_type_id = 0;
                $notification->task_type_id = 0;
                $notification->type = 5;
                $notification->cost = 1000;

                //аккаунт баланса
                $Account_payment = \App\Models\User::find(2);
                //снимаем с баланса системы
                $Account_payment->decrement('balance',1000);

                //зачисляем бонус
                $user->increment('balance',1000);

                return "bonus taken";

            }
            else{
                return "bonus already taken";
            }


        }
        else{
            return "not completed yet";
        }


    }
    public function getData(){
        $user = auth::user();
        $email = $user->email;
        $refferals = \App\Models\User::where('referrer_id',$user->id)->latest()->take(5)->get()->toArray();
        $сompletions = \App\Models\Notifications::where('user_id',$user->id)->with('TaskType')->limit(10)->latest()->get()->toArray();
        $news = \App\Models\News::take(3)->orderByDesc('date')->get()->toArray();
        $taskCount = \App\Models\Task::where('user_id',$user->id)->get()->Count();
        $сompletionsCount = \App\Models\Notifications::where('user_id',$user->id)->where('type',1)->get()->Count();
        $refferalsCount = \App\Models\User::where('referrer_id',$user->id)->get()->Count();
        return Response::json([
            'completions'=>mb_convert_encoding($сompletions, 'UTF-8', 'UTF-8'),
            'news'=>mb_convert_encoding($news, 'UTF-8', 'UTF-8'),
            'refferals'=> mb_convert_encoding($refferals, 'UTF-8', 'UTF-8'),
            'email'=> mb_convert_encoding($email, 'UTF-8', 'UTF-8'),
            'taskCount' => $taskCount,
            'сompletionsCount' => $сompletionsCount,
            'refferalsCount' => $refferalsCount,
        ]);
    }
    public function getCompeted(){
            $user = auth::user();
            $stats1 = \App\Models\Notifications::where('user_id',$user->id)->whereDate('created_at','=',Carbon::today()->format('Y-m-d'))->where('type',1)
                ->whereTime('created_at','>=',Carbon::now()->subHours(10)->format('Y-m-d'))->get()->Count();
            $stats2 = \App\Models\Notifications::where('user_id',$user->id)->whereDate('created_at', Carbon::now()->subDay(1)->format('Y-m-d') )->get()->Count();
            $stats3 = \App\Models\Notifications::where('user_id',$user->id)->whereDate('created_at', Carbon::now()->subDay(2)->format('Y-m-d') )->get()->Count();
            $stats4 = \App\Models\Notifications::where('user_id',$user->id)->whereDate('created_at', Carbon::now()->subDay(3)->format('Y-m-d') )->get()->Count();
            $stats5 = \App\Models\Notifications::where('user_id',$user->id)->whereDate('created_at', Carbon::now()->subDay(4)->format('Y-m-d') )->get()->Count();
            $stats6 = \App\Models\Notifications::where('user_id',$user->id)->whereDate('created_at', Carbon::now()->subDay(5)->format('Y-m-d') )->get()->Count();
            $stats7 = \App\Models\Notifications::where('user_id',$user->id)->whereDate('created_at', Carbon::now()->subDay(6)->format('Y-m-d') )->get()->Count();
            $stats8 = \App\Models\Notifications::where('user_id',$user->id)->whereDate('created_at', '>=',Carbon::now()->subDay(7)->format('Y-m-d') )->where('type',1)->get()->Count();

            Carbon::setLocale('ru');

            $data1 = Carbon::now()->subHour(10)->diffForHumans();
            $data2 = Carbon::now()->subDay(1)->diffForHumans();
            $data3 = Carbon::now()->subDay(2)->diffForHumans();
            $data4 = Carbon::now()->subDay(3)->diffForHumans();
            $data5 = Carbon::now()->subDay(4)->diffForHumans();
            $data6 = Carbon::now()->subDay(5)->diffForHumans();
            $data7 = Carbon::now()->subDay(6)->diffForHumans();
            $data8 = Carbon::now()->subDay(7)->diffForHumans();

            $arrStat = [
                $stats1,
                $stats2,
                $stats3,
                $stats4,
                $stats5,
                $stats6,
                $stats7,
                $stats8,
            ];
            $arrData =[
                $data1,
                $data2,
                $data3,
                $data4,
                $data5,
                $data6,
                $data7,
                $data8,
            ];
            return [$arrStat,$arrData];
        }
}
