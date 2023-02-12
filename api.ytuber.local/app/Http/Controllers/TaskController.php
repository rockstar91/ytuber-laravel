<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\TasksController;
use App\Models\Task;
use App\Models\Completions;
use App\Models\TaskType;
use App\Models\TasksToReferralSources;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Exception;
use Alaouy\Youtube\Facades\Youtube;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Jobs\TaskLikeChecker;
use Config;
use DateInterval;

class TaskController extends Controller
{
    public function changeStatusDisabled($id)
    {
        $currentTask = \App\Models\Task::find($id);
        if ($currentTask->total_cost > $currentTask->action_cost) {
            $currentTask->disabled = !$currentTask->disabled;
            $currentTask->update();
            return $currentTask;
        }
    }
    public function getCompletions($id){
        $user = auth::user();
        $task = \App\Models\Task::find($id);
        if($task->user_id == $user->id){
            return \App\Models\Completions::where('task_id',$id)->with('account')->with('user')->paginate(20);
        }
    }
    public function getList(Request $request)
    {
        $task_type = $request->input('task_type');
        //$mine = $request->input('mine');
        if ($task_type == 0) {
            $tasks = \App\Models\Task::where('user_id', Auth::user()->id)->with('ReferralSources')->with('TaskType')->with('Comments')->with(['completion' => function ($q) {
                return $q->with('account');
            }]);
        } else {
            $tasks =  \App\Models\Task::where('user_id', Auth::user()->id)->where('type_id', $task_type)->with('ReferralSources')->with('TaskType')->with('Comments')->with(['completion' => function ($q) {
                return $q->with('account');
            }]);
        }

        return $tasks->paginate(10);
    }

    public function edit($id)
    {
        return \App\Models\Task::with('ReferralSources')->with('TaskType')->with('TaskCategory')->with('Comments')->find($id);
    }

    public static function delete($id)
    {
        $user = Auth::user();
        $task = \App\Models\Task::find($id);
        //return $task;
        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error',
                'errors' => ['Задача не найдена'],
            ], 422);
        }


        if ($task && $task->user_id == $user->id || $user->admin) {

            $task_id = $task->id;
            $task->delete();

            //Забираем баллы с задачи на баланса пользователя
            $user->increment('balance', $task->total_cost);

            //удаляем комментарии
            $comments = \App\Models\TasksComments::where('task_id', $task_id)->get();
            if (Count($comments) > 0) {
                foreach ($comments as $comment) {
                    $comment->delete();
                }
            }

            return $task;

        }
        return response()->json([
            'status' => 'error',
            'message' => 'Error',
            'errors' => ['У вас нет прав, для редактирования этой задачи'],
        ], 422);
    }

    public function create(Request $request)
    {

        //Проверяем запрос (Валидация)
        try {
            $this->validate(request(), [
                'name' => 'required|max:255',
                'url' => 'required|url',
                'name' => 'required|max:255',
                'type_id' => 'required',
                'category_id' => 'required',
                'total_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'action_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'hour_limit' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'targetTime' => 'required',
                'referralSources' => 'required',
            ]);
        } catch (ValidationException $exception) {
            //В случае ошибки возвращаем запрос с ошибкой
            return response()->json([
                'status' => 'error',
                'message' => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }

        //Валидация комментов и ответов на комменты
        if ($request->input('type_id') == 3 || $request->input('type_id') == 201) {

            if (Count($request->input('customCommentList')) < 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error',
                    'errors' => ['Вы не указали комментарии'],
                ], 422);
            }
            if (Count($request->input('customCommentList')) < $request->input('total_cost') / $request->input('action_cost')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error',
                    'errors' => ['Количество комментов должно быть ровно количеству выполнений'],
                ], 422);
            }
        }


        //Валидация Если Лайки к комментам или ответы к комментам
        if ($request->input('type_id') == 7 || $request->input('type_id') == 201) {
            if (!($request->input('currentComment'))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error',
                    'errors' => [
                        'Вы не выбрали комментарий',
                        'Выберите комментарий из списка'
                    ],
                ], 422);
            }
        }

        //Максимальное время
        if ($request->input('targetTime') > 600) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error',
                'errors' => [
                    'Задача привышаает максимальное время',
                    'Уменьшите время задачи'
                ],
            ], 422);
        }
        //Минимальная цена
        if ($request->input('action_cost') < 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error',
                'errors' => [
                    'Минимальная цена 1 балл',
                ],
            ], 422);
        }
        //Получаем авторизированного пользователя и его id

        $user = Auth::user();
        $user_id = $user->id;

        //Если Подписки или Канал ID
        if ($request->input('type_id') == 4) {
            if (!$request->input('channel_id')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error',
                    'errors' => [
                        'Укажите канал для подписки',
                    ],
                ], 422);
            }
        }
        //Проверяем если ли баланс и хватит ли на создание задачи

        if ($user->balance > 0 && $user->balance >= $request->input('total_cost') && $request->input('total_cost') >0) {

            //Создаём новую задачу
            $task = new \App\Models\Task;

            $task->name = $request->input('name');
            $task->user_id = $user_id;

            if ($request->input('type_id') == 4) {
                $task->url = $request->input('url');
            } else {
                $task->url = 'https://www.youtube.com/watch?v=' . $request->input('video_id');
            }

            if ($request->input('action_cost') > $request->input('total_cost')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error',
                    'errors' => [
                        'Цена выполнения не может быть больше баланса',
                    ],
                ], 422);
            }

            $task->type_id = $request->input('type_id');
            $task->category_id = $request->input('category_id');
            $task->total_cost = $request->input('total_cost');
            $task->action_cost = $request->input('action_cost');
            $task->hour_limit = $request->input('hour_limit');
            $task->targetTime = $request->input('targetTime');
            $task->videosCountLimit = $request->input('videosCountLimit');
            $task->channelAgeLimit = $request->input('channelAgeLimit');
            $task->report = 0;
            $task->description = $request->input('name');
            $task->action_done = 0;
            $task->action_fail = 0;
            $task->action_refund = 0;
            $task->hour_done = 0;
            $task->youtube = 0;
            $task->youtube_initial = 0;
            $task->youtube_extend = '';
            $task->extend = '';

            if ($request->input('video_id')) {
                $apiVideoData = Youtube::getVideoInfo($request->input('video_id'));
                $task->youtube_extend = serialize($apiVideoData);
                $task->youtube_initial = $apiVideoData->statistics->viewCount;
                $task->youtube = 0;


                //проверяем время

                $interval = new DateInterval($apiVideoData->contentDetails->duration);
                $h = $interval->format('%h');
                $s = $interval->format('%s');
                $m = $interval->format('%i');

                $seconds = $m * 60 + $s + $h*3600;

                //Максимальное время
                if ($request->input('targetTime') > $seconds) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Error',
                        'errors' => [
                            'Задача привышаает время вашего ролика',
                            'Уменьшите время задачи'
                        ],
                    ], 422);
                }

            }

            //Если Просмотры

            if ($request->input('type_id') == 1) {
                $task->extend = serialize(['type' => 0]);
            }

            //Если Лайки или Дизлайки

            if ($request->input('type_id') == 2) {
                if ($request->input('likeType')[0] == 'Положителный (Лайк)') {
                    $task->extend = serialize(['type' => 1]);
                    $task->youtube_initial = $apiVideoData->statistics->likeCount;
                    $task->youtube = 0;
                } else {
                    $task->extend = serialize(['type' => 2]);
                    $task->youtube_initial = $apiVideoData->statistics->dislikeCount;
                    $task->youtube = 0;
                }
            }

            if ($request->input('type_id') == 4 && $request->input('channel_id')) {
                $channelData = Youtube::getChannelById($request->input('channel_id'));
                if (!$channelData || !$channelData->statistics->subscriberCount || $channelData->statistics->hiddenSubscriberCount == 'true') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Error',
                        'errors' => [
                            'Введите корректную ссылку на канал',
                        ],
                    ], 422);
                }
                $task->youtube_extend = serialize($channelData);
                $task->youtube_initial = $channelData->statistics->subscriberCount;
                $task->youtube = 0;
            }

            //Если Подписки и Nickname Канала

            if ($request->input('type_id') == 4 && $request->input('channel_name')) {
                $channelData = Youtube::getChannelByName($request->input('channel_name'));
                $task->youtube_extend = serialize($channelData);
                $task->youtube_initial = $channelData->statistics->subscriberCount;
                $task->youtube = 0;
            }
            //Если Лайки к комментам или ответы к комментам
            if ($request->input('type_id') == 7 || $request->input('type_id') == 201) {
                $task->extend = serialize($request->input('currentComment'));
            }

            //Если комменты или ответы к комментам
            if ($request->input('type_id') == 3 || $request->input('type_id') == 201) {
                if (Count($request->input('customCommentList')) > 1) {
                    $comments = $request->input('customCommentList');
                    foreach ($comments as $comment) {
                        if(empty($comment["comment_text"])){
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Error',
                                'errors' => [
                                    'Вы указали пустой комментарий',
                                ],
                            ], 422);
                        }
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Error',
                        'errors' => [
                            'Вы не указали комментарии',
                            'Укажите минимум 2 комментария'
                        ],
                    ], 422);
                }
            }


            $task->order = 0;
            $task->completion = null;
            $task->added = Carbon::now();
            $task->updated = Carbon::now();
            $task->deleted_at = null;
            $task->completed_at = null;
            $task->save();


            if ($task) {

                //Забираем баллы с баланса пользователя
                $user->decrement('balance', $task->total_cost);

                //referralSources
                $refs = $request->input('referralSources');

                foreach ($refs as $ref => $value) {
                    $refsorce = new \App\Models\TasksToReferralSources;
                    $refsorce->task_id = $task->id;
                    $refsorce->referral_source_id = $value['id'];
                    $refsorce->created_at = Carbon::now();
                    $refsorce->updated_at = Carbon::now();
                    $refsorce->save();
                }
                //Если комменты или ответы к комментам
                if ($request->input('type_id') == 3 || $request->input('type_id') == 201) {
                    if (Count($request->input('customCommentList')) > 1) {
                        $comments = $request->input('customCommentList');
                        foreach ($comments as $comment) {
                            $c = new \App\Models\TasksComments;
                            $c->task_id = $task->id;
                            $c->comment_text = $comment["comment_text"];
                            $c->save();
                        }
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Error',
                            'errors' => [
                                'Вы не указали комментарии',
                                'Укажите минимум 2 комментария'
                            ],
                        ], 422);
                    }
                }
                return $task;

            }

            return response()->json([
                'status' => 'error',
                'message' => 'Error',
                'errors' => ['Не доступа'],
            ], 422);

        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Error',
                'errors' => ['Не хватает баллов'],
            ], 422);
        }

    }

    public function update(Request $request)
    {

        //Проверяем запрос (Валидация)
        try {
            $this->validate(request(), [
                'name' => 'required|max:255',
                'task_id' => 'required',
                'type_id' => 'required',
                'category_id' => 'required',
                'total_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'action_cost' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'hour_limit' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'targetTime' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'referralSources' => 'required',
            ]);
        } catch (ValidationException $exception) {
            //В случае ошибки возвращаем запрос с ошибкой
            return response()->json([
                'status' => 'error',
                'message' => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }

        //Получаем авторизированного пользователя и его id
        $authUser = Auth::user();


        //получаем текущую задачу
        $task = Task::find($request->input('task_id'));

        $user = \App\Models\User::find($task->user_id);
        $user_id = $user->id;

        if ($task) {
            if ($task->user_id != $user_id && !$authUser->admin) {
                return "can't update this task";
            }

            if ($request->input('total_cost') - ($task->total_cost) > $user->balance) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error',
                    'errors' => ['Не хватает баллов'],
                ], 422);
            }
            if ($request->input('total_cost') < 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error',
                    'errors' => ['Не верно указанны баллы'],
                ], 422);
            }
            //Проверка времени

            $videoId = Youtube::parseVidFromURL($task->url);
            $apiVideoData = Youtube::getVideoInfo($videoId);

            //проверяем время

            $interval = new DateInterval($apiVideoData->contentDetails->duration);
            $h = $interval->format('%h');
            $s = $interval->format('%s');
            $m = $interval->format('%i');

            $seconds = $m * 60 + $s + $h*3600;

            //Максимальное время
            if ($request->input('targetTime') > $seconds) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error',
                    'errors' => [
                        'Задача привышаает время вашего ролика',
                    ],
                ], 422);
            }



            //Валидация комментов и ответов на комменты
            if ($request->input('type_id') == 3 || $request->input('type_id') == 201) {

                if (Count($request->input('customCommentList')) < 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Error',
                        'errors' => ['Вы не указали комментарии'],
                    ], 422);
                }
                if (Count($request->input('customCommentList')) < $request->input('total_cost') / $request->input('action_cost')) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Error',
                        'errors' => ['Количество комментов должно быть ровно количеству выполнений'],
                    ], 422);
                }
            }
            //удаляем текущие источники выполнения
            $TasksToReferralSources = TasksToReferralSources::where('task_id', $task->id)->get();

            foreach ($TasksToReferralSources as $r) {
                $r->delete();
            }


            //Если комменты
            if ($request->input('type_id') == 3 || $request->input('type_id') == 201) {
                //удаляем комментарии
                $comments = \App\Models\TasksComments::where('task_id', $task->id)->get();
                if (Count($comments) > 0) {
                    foreach ($comments as $comment) {
                        $comment->delete();
                    }
                }

                //Добавляем комментарии
                if (Count($request->input('customCommentList')) > 0) {
                    $comments = $request->input('customCommentList');
                    foreach ($comments as $comment) {
                        if(empty($comment["comment_text"])){
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Error',
                                'errors' => [
                                    'Вы указали пустой комментарий',
                                ],
                            ], 422);
                        }
                        $c = new \App\Models\TasksComments;
                        $c->task_id = $task->id;
                        $c->comment_text = $comment["comment_text"];
                        $c->save();
                    }
                }
            }

            //Обновляем задачу
            $task->name = $request->input('name');
            $task->user_id = $user_id;
            $task->action_cost = $request->input('action_cost');
            $task->hour_limit = $request->input('hour_limit');
            $task->targetTime = $request->input('targetTime');
            $task->videosCountLimit = $request->input('videosCountLimit');
            $task->channelAgeLimit = $request->input('channelAgeLimit');
            $task->description = $request->input('name');
            $task->updated = Carbon::now();

            //добавляем источники выполнения

            $refs = $request->input('referralSources');

            foreach ($refs as $ref => $value) {
                $refsorce = new \App\Models\TasksToReferralSources;
                $refsorce->task_id = $task->id;
                $refsorce->referral_source_id = $value['id'];
                $refsorce->created_at = Carbon::now();
                $refsorce->updated_at = Carbon::now();
                $refsorce->save();
            }
            $task->disabled = 0;
            //Обновляем задачу
            $task->update();

            //Забираем баллы с баланса пользователя
            if ($request->input('total_cost') > $task->total_cost) {
                $points = ($request->input('total_cost') - $task->total_cost);
                $task->total_cost = $request->input('total_cost');
                $task->update();
                $user->decrement('balance', $points);
            }

            //Возвращаем баллы на баланса пользователя
            if ($task){
                if ($request->input('total_cost') < $task->total_cost) {
                    $points = ($task->total_cost - $request->input('total_cost'));
                    $task->total_cost = $request->input('total_cost');
                    $task->update();
                    $user->increment('balance', $points);
                }
            }

            return $task;

        }

        return "Sorry";
        {
            return response()->json(['status' => 'error',
                'message' => 'Error',
                'errors' => ['Не хватает баллов'],], 422);
        }

    }
}
