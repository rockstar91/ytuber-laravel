<?php

namespace App\Http\Controllers;
use Alaouy\Youtube\Facades\Youtube;
use App\Models\Task;
use App\Models\Completions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Jobs\TaskLikeChecker;
use App\Jobs\TaskSubsrcibeChecker;
use App\Jobs\TaskCommentLikeChecker;
use App\Jobs\TaskCommentAnwserChecker;
use App\Jobs\TaskCommentChecker;
use Carbon\Carbon;
use Config;
use Illuminate\Support\Facades\Http;

class WorkController extends Controller
{
    public function getList(Request $request){
        $user = Auth::user();
        $activeAccount = $user->activeAccount;

        if(!$activeAccount){
            return "no channel";
        }

        $account_reg_date = Carbon::now()->diffInDays($activeAccount->publishedAt);
        $account_videos = $activeAccount->videoCount;


        $task_type = $request->input('task_type');

        if($task_type==0){
            return \App\Models\Task::OrderBy('action_cost','DESC')->where('type_id',1)->where('user_id', '!=', $user->id)->Where('disabled',false)->with('TaskType')->with(['Completions' => function ($q) use ($user) {
                return $q->where('user_id',$user->id);
            }])->whereHas('ReferralSources', function ($query) {
                $query->where('referral_source_id', '1')->orWhere('referral_source_id',2);
            })->where('channelAgeLimit', '<', Carbon::now()->subDays($account_reg_date)->diffInDays())->where('videosCountLimit','<=',$account_videos)->orderBy('action_cost', 'desc')->OrderBy('action_cost','DESC')->paginate(20);
        }
        else{
            return \App\Models\Task::OrderBy('action_cost','DESC')->where('type_id',$task_type)->where('user_id', '!=', $user->id)->Where('disabled',false)->with('TaskType')->with(['Completions' => function ($q) use ($activeAccount) {
                return $q->where('account_id',$activeAccount->id);
            }])->whereHas('ReferralSources', function ($query) {
                $query->where('referral_source_id', '1')->orWhere('referral_source_id',2);
            })->where('channelAgeLimit', '<', Carbon::now()->subDays($account_reg_date)->diffInDays())->where('videosCountLimit','<=',$account_videos)->orderBy('action_cost', 'desc')->OrderBy('action_cost','DESC')->paginate(20);
        }
    }
    public function checkTaskJob(Request $request,$id){

        $user = Auth::user();

        $task = Task::with('TaskType')->find($id);

        $check = Cache::store('redis')->get('status_'.$task->id);

        if(!$check){
            return "task not founded";
        }
        if(!$user->activeAccount){
            return "no channel";
        }
        //получаем запись из выполнений...

        $completed = Completions::where('user_id',$user->id)->where('task_id',$task->id)->where('account_id',$user->activeAccount->id)->first();

        if(!$completed && $task->TaskType->name != 'YT Просмотры'){
            //Создаём запись в выполнениях
            $complete = new Completions;
            $complete->task_id = $task->id;
            $complete->user_id = $user->id;
            $complete->type_id = $task->type_id;
            $complete->ip_bin = inet_pton($request->ip);
            $complete->action_cost = $task->action_cost * 0.75;
            $complete->account_id = $user->activeAccount->id;
            $complete->cost_rule = 1;
            $complete->youtube = $check[2];
            $complete->time = 0;
            $complete->check_count = 0;
            $complete->check_time = 0;
            $complete->expires = 0;
            $complete->data = 0;
            $complete->domain = 1;
            $complete->status = 2;
            $complete->save();
        }

        //Если тип лайки то запускаем Проверку Лайков
        if($task && $task->TaskType->name == "YT Лайки"){
            dispatch(new TaskLikeChecker($user,$task))->delay(now()->addMinutes(10));
        }
        //Если тип Подписки то запускаем Проверку Подписок
        if($task && $task->TaskType->name == "YT Подписчики"){
            dispatch(new TaskSubsrcibeChecker($user,$task))->delay(now()->addMinutes(25));
        }
        //Если тип лайки к комментам то запускаем Проверку Лайков комментов
        if($task && $task->TaskType->name == "YT Лайки к комментам"){
            dispatch(new TaskCommentLikeChecker($user,$task))->delay(now()->addMinutes(10));
        }
        //Если тип ответы к комментам то запускаем Проверку Ответов комментов
        if($task && $task->TaskType->name == "YT Ответы к комментариям"){
            dispatch(new TaskCommentAnwserChecker($user,$task))->delay(now()->addMinutes(10));
        }
        //Если тип Комментарии то запускаем Проверку комментов
        if($task && $task->TaskType->name == "YT Комментарии"){
            dispatch(new TaskCommentChecker($user,$task))->delay(now()->addMinutes(10));
        }

        return "job created";
    }
    public function checkTaskStatus($id){

        $task = Task::find($id);
        $user = Auth::user();

        if(!$user->activeAccount){
            return "no channel";
        }

        $checkRecapcha = Cache::store('redis')->get('recaptcha_'.$user->id);

        if(!$checkRecapcha) {
            return "recaptcha";
        }
        $check = Cache::store('redis')->get('status_'.$task->id);

        if($task->TaskType->name == "YT Просмотры") {
            $checkTask = Completions::where('task_id',$id)->where('user_id',$user->id)->first();
        }
        else{
            $checkTask = Completions::where('task_id',$id)->where('user_id',$user->id)->where('account_id',$user->activeAccount->id)->first();
        }


        if($checkTask) {
            if ($checkTask->status == 2) {
                return "taskIsWaiting";
            }
            if ($checkTask->status == 4) {
                return "taskIsCompeleted";
            }
        }
        //Если задача уже занята
        if($check && $check[0] == $task->id && $check[1] != $user->id){
            return "taskIsBusy";
        }
        else{
            //Устанавливаем случайный api ключ
            $arrkeys = config('youtube.keys');
            Youtube::setApiKey($arrkeys[array_rand($arrkeys)]);

            if($task && $task->TaskType->name == "YT Лайки") {
                //Обращаемся к api youtube
                $videoId = Youtube::parseVidFromURL($task->url);
                $apiData = Youtube::getVideoInfo($videoId);

                //если данные получены
                if($apiData){

                    //если задача не создана и не создана этим же пользователем
                    if(!$check || !($check[1] == $user->id)){
                        $arr = [$task->id,$user->id,$apiData->statistics->likeCount];
                        //создаём запись открытия и число лайков
                        Cache::store('redis')->put('status_'.$task->id,$arr,Carbon::now()->addMinute(15));
                        //return Cache::store('redis')->get('status_' . $task->id);
                    }

                }
                else{
                    //удаляем задачу
                    TaskController::delete($task->id);
                    return "taskIsDeleted";
                }

            }
            //Комментарии
            if($task && $task->TaskType->name=="YT Комментарии") {
                $arr = [$task->id,$user->id,$task->TaskType->name];
                Cache::store('redis')->put('status_'.$task->id,$arr,Carbon::now()->addMinute(5));
            }
            //Лайки к комментам
            if($task && $task->TaskType->name=="YT Лайки к комментам"){
                $apiRequest = 'https://youtube.googleapis.com/youtube/v3/commentThreads?part=snippet&id='. $task->extend['id'] . '&key=' . $arrkeys[array_rand($arrkeys)];
                $response = Http::get($apiRequest)->json();
                try{
                    $commentLikeCount = $response['items'][0]['snippet']['topLevelComment']['snippet']['likeCount'];
                }
                catch(\Exception $e){
                    //TaskController::delete($task->id);
                    //return $response;
                    return "taskIsDeleted";
                }
                if($commentLikeCount || $commentLikeCount == 0){
                    if(!$check || !($check[1] == $user->id)){
                        $arr = [$task->id,$user->id,$commentLikeCount];
                        //создаём запись открытия и число лайков комментов
                        Cache::store('redis')->put('status_'.$task->id,$arr,Carbon::now()->addMinute(15));
                    }
                }
                else{
                    //return $response;
                    //TaskController::delete($task->id);
                    return "taskIsDeleted";
                }
            }
            //Подписки
            if($task && $task->TaskType->name == "YT Подписчики") {

                $channel_url = parse_url($task->url);
                //берем id канала
                $channel_id = Str::replaceFirst('/channel/', '', $channel_url['path']);

                //Устанавливаем случайный api ключ
                $arrkeys = config('youtube.keys');
                Youtube::setApiKey($arrkeys[array_rand($arrkeys)]);

                //получаем данные api канала как объект
                try {
                    $channel = Youtube::getChannelById($channel_id);
                }
                catch(\Exception $e){
                    TaskController::delete($task->id);
                    return "taskIsDeleted";
                }
                // return $channel;
                //Если не получены данные или отключено отображение подписок

                if(!$channel || $channel->statistics->hiddenSubscriberCount == 'true'){
                    //удаляем задачу
                    TaskController::delete($task->id);
                    return "taskIsDeleted";
                }
                else{
                    if(!$check || !($check[1] == $user->id)){
                        $arr = [$task->id,$user->id,$channel->statistics->subscriberCount];
                        //создаём запись открытия и число подписок
                        Cache::store('redis')->put('status_'.$task->id,$arr,Carbon::now()->addMinute(15));
                    }
                }

            }

        }
    }
}
