<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Alaouy\Youtube\Facades\Youtube;

class RefundController extends Controller
{
    public function refundTask($id){

        return "sorry";
        //берем задачу
        $task = \App\Models\Task::with('taskType')->find(580610);

        //Пользователь по задачи
        $user = \App\Models\User::find($task->user_id);

        //Время создания задачи
        $createdTime = $task->created_at;

        //Текущее время + 3 дня через Carbon
        $timeNow = carbon::now()->addDays(3);

       //Проверяем разницу во времени создания задачи через Carbon diffInDays
       if($createdTime->diffInDays($timeNow) >0){

           if($task->taskType['name'] =="YT Просмотры"){
               return "type not supported";
           }

           if($task->taskType['name'] =="YT Лайки"){

               //было лайков при создании задачи youtube_initial

               $countComplete = $task->youtube_initial;
               $countNow = 0;

               if($task->extend['type']==1){
                   //получение по Api текущего числай лайков по id задачи
                   $video_id = Youtube::parseVidFromURL($task->url);
                   $check = YoutubeController::getVideoInfo($video_id);
                   $countNow = $check->statistics->likeCount;
               }
               if($task->extend['type']==0){
                   //получение по Api текущего числай лайков по id задачи
                   $video_id = Youtube::parseVidFromURL($task->url);
                   $check = YoutubeController::getVideoInfo($video_id);
                   $countNow = $check->statistics->dislikeCount;
               }
               //Получаем число выполнений было выполнено минус сейчас
               $checkCountComplete = $countComplete-$countNow;

               if($checkCountComplete>0){
                   return "task not completed";
               }
               else{
                   return "task completed";
               }

           }
           if($task->taskType['name'] =="YT Подписчики"){


               //Было подписок
               $countNow = 0;
               $countComplete = $task->youtube_initial;

               //берем id канала
               $channel_url = parse_url($task->url);
               $channel_id = Str::replaceFirst('/channel/', '', $channel_url['path']);

               //Берем данные ютуб канала
               $channel_data = YoutubeController::getChannelInfoFromId($channel_id);

               //Текущее число подписок
               $countNow = $channel_data->statistics->subscriberCount;

               //Получаем число выполнений было выполнено минус сейчас
               $checkCountComplete = $countComplete-$countNow;

               return $checkCountComplete;
           }
           if($task->taskType['name'] =="VK Поделиться"){
               return "type not supported";
           }
           if($task->taskType['name'] =="YT Комментарии"){
               return "type not supported";
           }
           if($task->taskType['name'] =="YT Лайки к комментам"){
               return "type not supported";
           }
           if($task->taskType['name'] =="Твитнуть"){
               return "type not supported";
           }
           if($task->taskType['name'] =="YT Ответы к комментариям "){
               return "type not supported";
           }
       }
       else{
           return "the time has not come";
       }
    }

    public function makeRefund(){

    }
}
