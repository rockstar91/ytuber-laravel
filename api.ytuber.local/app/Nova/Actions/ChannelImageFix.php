<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use App\Models\YoutubeChannels;
use App\Http\Controllers\ServiceWorkController;
use App\Nova\Actions\Select;

class ChannelImageFix extends Action
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
            ServiceWorkController::downloadChannelImg($model->id);
        }
        return Action::message('Картинки обновлены!');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
/*        $channels = YoutubeChannels::all()->pluck('id');
        return [
            Select::make('id')->options($channels)
        ];*/
    }
}
