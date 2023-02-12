<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use App\Jobs\TaskLikeChecker;
use App\Jobs\TaskSubsrcibeChecker;
use App\Jobs\TaskCommentLikeChecker;
use App\Jobs\TaskCommentAnwserChecker;
use App\Jobs\TaskCommentChecker;

class CheckWorkAction extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            $user = \App\Models\User::find($model->user_id);
            $task = \App\Models\Task::find($model->task_id);
            //Если тип лайки то запускаем Проверку Лайков
            if($task && $task->TaskType->name == "YT Лайки"){
                dispatch(new TaskLikeChecker($user,$task));
            }
            //Если тип Подписки то запускаем Проверку Подписок
            if($task && $task->TaskType->name == "YT Подписчики"){
                dispatch(new TaskSubsrcibeChecker($user,$task));
            }
            //Если тип лайки к комментам то запускаем Проверку Лайков комментов
            if($task && $task->TaskType->name == "YT Лайки к комментам"){
                dispatch(new TaskCommentLikeChecker($user,$task));
            }
            //Если тип ответы к комментам то запускаем Проверку Ответов комментов
            if($task && $task->TaskType->name == "YT Ответы к комментариям"){
                dispatch(new TaskCommentAnwserChecker($user,$task));
            }
            //Если тип Комментарии то запускаем Проверку комментов
            if($task && $task->TaskType->name == "YT Комментарии"){
                dispatch(new TaskCommentChecker($user,$task));
            }

        }
        return Action::message('Задачи отправлены на проверку!');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
