<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\Text;
use Chaseconey\ExternalImage\ExternalImage;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;

class Task extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Task::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
    ];
    public static function label()
    {
        return 'Задачи';
    }
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {

        return [
            ID::make()->sortable(),

            //ExternalImage::make('avatar')
             //   ->avatar()
            //->updateRules('nullable'),

            ExternalImage::make('url', function () {
                $url = $this->url;
                $getUrl = parse_url($url);
                if (Str::contains($getUrl['host'],'www.youtube.com')){
                    if(Str::contains($getUrl['path'],'channel')){
                        return 'https://api.ytuber.ru/images'. str::replace('/channel','',$getUrl['path']) . '.jpg';
                    }
                    else{
                        $avatar = str_replace('v=','',$getUrl['query']);
                        return 'https://img.youtube.com/vi/' . $avatar . '/default.jpg';
                    }

                }
                else{
                    $avatar = str_replace('/','',$getUrl['path']);
                    return 'https://img.youtube.com/vi/' . $avatar . '/default.jpg';
                }


            }),

            BelongsTo::make('Task type','TaskType','App\Nova\TaskType')->sortable()->display('name'),
            BelongsTo::make('user'),

/*            Text::make('url', function () {
                $url = $this->url;
                $getUrl = parse_url($url);
                if (Str::contains($getUrl['host'],'www.youtube.com')){
                    if(Str::contains($getUrl['path'],'channel')){
                        return $getUrl['path'];
                    }
                    else{
                        $video_id = str_replace('v=','',$getUrl['query']);
                        return $video_id;
                    }

                }
                else{
                    $video_id = str_replace('/','',$getUrl['path']);
                    return $video_id;
                }


            }),*/

            Text::make('name')
                ->sortable()
                ->rules('required', 'max:255')->displayUsing(function($object) {
                    return Str::limit($object, 30);}),
            Text::make('url', function ($object) {
                return '<a target="_blank" href="'.$object->url.'">' .Str::Replace('https://www.youtube.com/watch?v=','',$object->url) .'</a>';
            })
                ->asHtml(),

            Number::make('Баланс','total_cost')
                ->sortable(),

            Number::make('Цена','action_cost')
                ->sortable(),

            Number::make('Время','targetTime')
                ->sortable(),
            Number::make('Выполнений','action_done')
                ->sortable(),
            Number::make('Ютуб','youtube_initial')
                ->sortable(),
            Number::make('action_fail')
                ->sortable(),
            HasMany::make('Комментарии','Comments','\App\Nova\TasksComment'),
            HasMany::make('Выполнения','Completions','\App\Nova\Completion'),
            Boolean::make('disabled')
                ->trueValue('1')
                ->falseValue('0'),
            Code::make('extend')->json()

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new Actions\DeleteTaskAction,
            new Actions\FixMyotubeUrl,
            new Actions\FixWWWyotubeUrl,
        ];
    }
}
