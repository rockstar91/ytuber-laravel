<?php

namespace App\Http\Controllers;

use Alaouy\Youtube\Facades\Youtube;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use \App\Models\YoutubeChannels;
use \App\Models\ModerateAccount;
use \App\Http\Controllers\YoutubeController;
use App\Events\CompleteForPublic;
use Illuminate\Support\Facades\Validator;
use Config;

class AccountsController extends Controller
{
    public function completeModerate($id){

        $status = false;
        $moderate = \App\Models\ModerateAccount::find($id);

        if($moderate){
            if(!$moderate->comment_id || $moderate->comment_id == null ){
                $moderate->delete();
                return "not founded";
            }
        }
        else{
            return "not founded";
        }

        //конфиг апи ключей
        $arrkeys = config('youtube.keys');
        $apikey = $arrkeys[array_rand($arrkeys)];


        $apiUrl = 'https://youtube.googleapis.com/youtube/v3/commentThreads?part=replies&maxResults=100&order=time&id=' . $moderate->comment_id . '&key=' . $apikey;

        if(!$apiUrl){
            return "try again";
        }
        //делаем запрос
        $comments = Http::get($apiUrl);
        //return $comments;
        //return $comments['items'][0]['replies']['comments'];
        try{
        if(!$comments || !$comments['items'][0] || !$comments['items'] || !$comments['items'][0]['replies']){
            return "try again";
        }

        if($comments['items'][0]['replies']){
        foreach($comments['items'][0]['replies']['comments'] as $c){
        if($c['snippet']['authorChannelId']['value'] == $moderate->channel){
            $status = true;
        }
        }
       if($status){
            $url = 'https://www.youtube.com/channel/' .$moderate->channel;
            AccountsController::create($url);

           $data_for_broadcast = [
               'yt_channel'=>$url,
               'text'=>'Добавлен новый канал',
               'icon'=>'icon-user-check mr-2 icon',
           ];

           CompleteForPublic::dispatch($data_for_broadcast);

            return 'success';
       }
       else{
           return "not founded";
       }
        }
        else{
            return "not founded";
        }
        }
        catch (\Exception $e) {
            return "not founded";
        }
    }
    public function getModerateAgain(Request $request){
        //ссылка на канал
        $check = parse_url($request->input('url'));

        //берем id канала
        $channel_id = Str::replaceFirst('/channel/', '', $check['path']);

        YoutubeController::getChannelInfoFromId($channel_id);

        //находим канал
        $findChannel = YoutubeChannels::where('channel_id',$channel_id)->first();
        $findModeration = ModerateAccount::where('channel_id',$findChannel->id)->first();

        if($findModeration){
             $findModeration->delete();
             return "moderation deleted";
        }
        else{
            return "moderation deleted";
        }

    }
    public function getModerateGoogle(Request $request){

        //конфиг апи ключей
        $arrkeys = config('youtube.keys');
        $apikey = $arrkeys[array_rand($arrkeys)];
        //$apikey = "AIzaSyAa_1Q6AddsnAHcDZxnFV0ZZ6oinO3UPhw";

        $token = 'ya29.a0AX9GBdUMOyAGbr309hBnPLhQw7Gv5PpXebb1OkXTh132hsUO-14gX43DFAaipJFbMg-MHuYd34qYOXvrmexzkp9n_yQ5x';
        $apiUrl = 'https://youtube.googleapis.com/youtube/v3/channels?part=snippet&mine=true&key='.$apikey;
        $channelData = Http::withHeaders([
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ])->get($apiUrl);

        return $channelData;

    }
    public function getModerate(Request $request){

        //ссылка на канал
        $check = parse_url($request->input('url'));

        //берем id канала
        $channel_id = Str::replaceFirst('/channel/', '', $check['path']);

        YoutubeController::getChannelInfoFromId($channel_id);

        //находим канал
        $findChannel = YoutubeChannels::where('channel_id',$channel_id)->first();

        $findModeration = ModerateAccount::where('channel_id',$findChannel->id)->first();

        if($findModeration){
            return $findModeration;
        }
        //авторизованные пользователь
        $user = auth::user();

        //конфиг апи ключей
        $arrkeys = config('youtube.keys');
        $apikey = $arrkeys[array_rand($arrkeys)];

        //случайное видео

        $videoId = \App\Models\YoutubeVideos::inRandomOrder()->first()->video_id;

        //получение комментариев

        $pageToken = null;

        $apiUrl = 'https://www.googleapis.com/youtube/v3/commentThreads?part=snippet&videoId=' . $videoId . '&key=' . $apikey;

        //делаем запрос
        $response = Http::get($apiUrl);

        if(!$response['items'] || !$response){
            return "not founded";
        }

        //первый коммент к видео
        $modedateComment = $response['items'][array_rand($response['items'])]['snippet'];

        //return $modedateComment;

        //создаём запись модерации
        $newModerateChannel = new \App\Models\ModerateAccount;
        $newModerateChannel->user_id = $user->id;
        $newModerateChannel->url = 'https://www.youtube.com/watch?v='.$videoId.'&lc='.$modedateComment['topLevelComment']['id'];
        $newModerateChannel->video_id = $videoId;
        $newModerateChannel->data = serialize($modedateComment);
        $newModerateChannel->channel = $findChannel->channel_id;
        $newModerateChannel->channel_id = $findChannel->id;
        $newModerateChannel->comment_id = $modedateComment['topLevelComment']['id'];
        $newModerateChannel->save();

        return $newModerateChannel;
    }
    public function captchaValidate (Request $request){
        //Проверяем запрос (Валидация)
        $validator = Validator::make(request()->all(), [
            'g-recaptcha-response' => 'recaptcha',
            // OR since v4.0.0
            recaptchaFieldName() => recaptchaRuleName()
        ]);

// check if validator fails
        if($validator->fails()) {
            $errors = $validator->errors();
            return $errors;
        }
        else{
            $user = auth::user();
            Cache::store('redis')->put('recaptcha_'.$user->id,true,Carbon::now()->addMinute(30));
            return "validated";
        }

    }
    public function activate(Request $request){
        $id =  $request->input('id');
        $user = auth::user();
        $user_id = $user->id;

        //снимаем активацию со всех аккаунтов пользователя
        \App\Models\Account::where('user_id', $user_id)->update(['active'=>0]);
        
        //Делаем один активным
        $account = \App\Models\Account::find($id);

        //ссылка на канал
        $check = parse_url($account->url);
        //берем id канала
        $channel_id = Str::replaceFirst('/channel/', '', $check['path']);

        //обновляем пользователя
        $user->avatar = $account->image;
        $user->channel = $channel_id;
        $user->update();

        if($account->user_id = $user_id){
            $account->active = 1;
            $account->update();
        }
    }
    public function getModerated(){
        
    }
    public function getList()
    {

        //получаем id авторизированного пользователя
        $user_id = auth::user()->id;

        //Создаем коллекцию аккаунтов где user_id

        $accounts = \App\Models\Account::where('user_id', $user_id)->with('AccountType')->paginate(10);

        return $accounts;
    }

    public function create($url)
    {
        $user = auth::user();
        //$validated = $request->validate([
        //    'url' => 'required|unique:accounts|max:255',
        //]);
        //$url = $request->input('url');

        //$account_pattern = \App\Models\AccountType::find(1)->pattern;

        $check = parse_url($url);

        //Проверяем если содержит channel
        if (Str::contains($check['path'], 'channel')) {

            //берем id канала
            $channel_id = Str::replaceFirst('/channel/', '', $check['path']);

            //подключаем api ключ
            Youtube::setApiKey('AIzaSyAgkXJD24gt8v1mjtOAojd3cWS5NojvNvc');

            //получаем данные api канала как объект
            $channel = Youtube::getChannelById($channel_id);

            $title = $channel->snippet->title;
            $description = $channel->snippet->description;
            $publishedAt = $channel->snippet->publishedAt;
            $thumbnail = $channel->snippet->thumbnails->default->url;
            $thumbnail_high = $channel->snippet->thumbnails->high->url;

            $viewCount = $channel->statistics->viewCount;
            $videoCount = $channel->statistics->videoCount;
            $hiddenSubscriberCount = $channel->statistics->hiddenSubscriberCount;

            //снимаем активацию со всех аккаунтов пользователя
            \App\Models\Account::where('user_id', $user->id)->update(['active'=>0]);

            //Добавляем аккаунт
            $account = new \App\Models\Account;
            $account->user_id = auth::user()->id;
            $account->account_type_id = 1;
            $account->active = true;
            $account->url = $url;
            $account->image = $thumbnail;
            $account->publishedAt = Carbon::parse($publishedAt);
            $account->description = $description;
            $account->title = $title;
            $account->viewCount = $viewCount;
            $account->image_high = $thumbnail_high;
            $account->videoCount = $videoCount;
            $account->hiddenSubscriberCount = $hiddenSubscriberCount;
            $account->updated_at = carbon::now();
            $account->save();



            return response()->make([
                'type' => 'success',
                'message' => 'Вы успешно добавили аккаунт ' . $account->url
            ]);
        }
        else{
            return response()->make([
                'type' => 'error',
                'message' => 'Не верная ссылка на канал '
            ]);
        }
    }

    public function delete(Request $request)
    {
        $auth_user = auth::user();
        $auth_user_id = $auth_user->id;
        $user_id = $request->input('user_id');
        $account_id = $request->input('id');

        if($auth_user_id == $user_id){
            $account = \App\Models\Account::find($account_id);
            $account->delete();
            return response()->make(['status' => 'success']);
        }
    }
    public function getRefferals(){
        return "sdfsdf";
    }
}
