<?php

namespace App\Http\Controllers;

use Alaouy\Youtube\Facades\Youtube;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use \App\Http\Controllers\ServiceWorkController;
use Config;

class YoutubeController extends Controller
{
    public static function getChannelInfoFromUrl(Request $request){
        
        $check = parse_url($request->input('url'));

        //берем id канала
        $channel_id = Str::replaceFirst('/channel/', '', $check['path']);

        //ищем в базе
        $findChannel = \App\Models\YoutubeChannels::where('channel_id',$channel_id)->first();
        if($findChannel){
            return $findChannel->data;
        }

        //подключаем api ключ
        $arrkeys = config('youtube.keys');
        $apikey = $arrkeys[array_rand($arrkeys)];
        Youtube::setApiKey($apikey);

        //получаем данные api канала как объект
        $channel = Youtube::getChannelById($channel_id);

        $recordChannel = new \App\Models\YoutubeChannels;
        $recordChannel->channel_id  = $channel_id;
        $recordChannel->data  = serialize($channel);
        $recordChannel->save();

        ServiceWorkController::downloadChannelImg($recordChannel->id);

        return $channel;
    }
    public static function getVideoInfo($id){
        $findVideo = \App\Models\YoutubeVideos::where('video_id',$id)->first();
        if($findVideo){
            return $findVideo->data;
        }
        $arrkeys = config('youtube.keys');
        $apikey = $arrkeys[array_rand($arrkeys)];
        Youtube::setApiKey($apikey);
        $video = Youtube::getVideoInfo($id);

        $recordVideo = new \App\Models\YoutubeVideos;
        $recordVideo->video_id  = $id;
        $recordVideo->data  = serialize($video);
        $recordVideo->save();

        return $video;
    }
    public static function getChannelInfoFromId($id){
        $findChannel = \App\Models\YoutubeChannels::where('channel_id',$id)->first();
        if($findChannel){
            return $findChannel->data;
        }
        $arrkeys = config('youtube.keys');
        $apikey = $arrkeys[array_rand($arrkeys)];
        Youtube::setApiKey($apikey);
        $channel = Youtube::getChannelById($id);

        $recordChannel = new \App\Models\YoutubeChannels;
        $recordChannel->channel_id  = $id;
        $recordChannel->data  = serialize($channel);
        $recordChannel->save();

        ServiceWorkController::downloadChannelImg($recordChannel->id);

        return $channel;
    }
    public static function getChannelInfoFromName($name){
        $arrkeys = config('youtube.keys');
        $apikey = $arrkeys[array_rand($arrkeys)];
        Youtube::setApiKey($apikey);
        $channel = Youtube::getChannelByName($name);
        return $channel;
    }
    public static function getCommentsFromVideoId(Request $request){

        $arrkeys = config('youtube.keys');
        $apikey = $arrkeys[array_rand($arrkeys)];
        $videoId = $request->input('videoId');
        $pageToken = $request->input('token');
        if($pageToken){
            $apiUrl = 'https://www.googleapis.com/youtube/v3/commentThreads?part=snippet&videoId=' . $videoId .'&pageToken=' . $pageToken . '&key=' . $apikey;
        }
        else{
            $apiUrl = 'https://www.googleapis.com/youtube/v3/commentThreads?part=snippet&videoId=' . $videoId . '&key=' . $apikey;
        }
        $response = Http::get($apiUrl);
        return $response;
    }
    public static function getSubscribeId(Request $request){
        $arrkeys = config('youtube.keys');

        $user_channel = $request->input('user_channel');
        $channel = $request->input('channel');
        $user = Auth::user()->load(['account'=> function ($q){
            return $q->where('active',true);
        }]);
        //берем id канала выполняющего
        $account_channel_url = parse_url($user->account[0]->url);
        $account_channel_id = Str::replaceFirst('/channel/', '', $account_channel_url['path']);

        //берем suscribe id
        //$apiRequestSubscriptions = 'https://youtube.googleapis.com/youtube/v3/subscriptions?part=snippet&channelId='. $account_channel_id . '&maxResults=25&order=relevance&key=' . $arrkeys[array_rand($arrkeys)];
        $apiRequestSubscriptions = 'https://youtube.googleapis.com/youtube/v3/subscriptions?part=snippet&channelId='. 'UCwVOuV969znJtqEhAAkuuLA' . '&maxResults=25&order=relevance&key=' . $arrkeys[array_rand($arrkeys)];
        $response = Http::get($apiRequestSubscriptions)->json();
        return $response;
    }
}
