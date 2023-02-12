<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Events\MessageChat;
use \App\Events\PrivateChat;
use Alaouy\Youtube\Facades\Youtube;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function chatMessage(Request $request){
        //данные
        $user = Auth::user();
        if(!$user->activeAccount){
            return response()->json([
                'status' => 'error',
                'message' => 'Error',
                'errors' => ['Сначала добавьте канал...'],
            ], 422);
        }
        if($user->disabled){
            return response()->json([
                'status' => 'error',
                'message' => 'Error',
                'errors' => ['Вы не можете отправлять сообщения...'],
            ], 422);
        }
        $room_id = $request->input('room_id');
        $video = $request->input('youtube_video');
        $channel = $request->input('youtube_channel');
        $youtubeChannel = null;
        $youtubeVideo = null;

        //проверяем если отправляется канал
        if($channel){
            $check = parse_url($channel);
            $channel_id = Str::replaceFirst('/channel/', '', $check['path']);
            $findChannel = \App\Models\YoutubeChannels::where('channel_id',$channel_id)->first();

            //если инфа о канале уже получена
            if(!$findChannel){
                $youtubeChannel = YoutubeController::getChannelInfoFromUrl($channel)->data;
            }
            else{
                $youtubeChannel = $findChannel->data;
            }
        }

        //Проверяем если посылается видео
        if($video){
            $videoId = Youtube::parseVidFromURL($video);
            $findVideo = \App\Models\YoutubeVideos::where('video_id')->get()->Count();
            //проверяем если инфа уже есть
            if($findVideo<=0){
            $youtubeVideo = YoutubeController::getVideoInfo($videoId);
            $recordVideo = new \App\Models\YoutubeVideos;
            $recordVideo->video_id = $videoId;
            $recordVideo->data = serialize($youtubeVideo);
            $recordVideo->save();
            }
            else{
                $youtubeVideo = $findVideo->data;
            }
        }
        //формируем массив сообщения
        $data = [
            'user'=>$request->input('user'),
            'text'=>$request->input('text'),
            'room_id'=>$request->input('room_id'),
            'youtube_video'=>$youtubeVideo,
            'youtube_channel'=>$youtubeChannel,
        ];

        //сохраняет сообщение в базу
        $message = new \App\Models\ChatMessages;
        $message->user_id = $user->id;
        $message->room_id = $room_id;
        $message->data = serialize($data);
        $message->save();

        //Отправляет событие чата

        PrivateChat::dispatch($message);
        //event( new \App\Events\PrivateChat($data));
        //Log::info('сообщение отправлено' .$message);
    }
    public function banUserChat($id){
        $user = Auth::user();
        $userToBan = \App\Models\User::find($id);
        if($user->admin || $user->moderator){
            $userToBan->disabled = 1;
            $userToBan->update();

            foreach(\App\Models\ChatMessages::where('user_id',$userToBan->id)->get() as $message){
                $message->delete();
            }
        }
    }
    public function getMessagesMainChat(){
        //получаем последние сообещния
        //$messages = \App\Models\ChatMessages::where('room_id',1)->whereDate('created_at', Carbon::today())->get();
        $messages = \App\Models\ChatMessages::where('room_id',1)->limit(200)->get();
        return $messages;
    }
    public function deleteMessage($id){
        $user = Auth::user();
        $message = \App\Models\ChatMessages::find($id);
        if($message->user_id == $user->id || $user->admin || $user->moderator){
            $message->delete();
        }

    }
    public function chatRoomEnterMain(){
        $user = Auth::user();
        $check = \App\Models\RoomUser::where('user_id',$user->id)->where('room_id',1)->get();
        $check_account = \App\Models\User::find($user->id)->account;
        if(Count($check) == 0 && $check_account){
            $join = new \App\Models\RoomUser;
            $join->room_id = 1;
            $join->user_id = $user->id;
            $join->save();
            return $join;
        }
        else{
            return "access denied";
        }

    }
}
