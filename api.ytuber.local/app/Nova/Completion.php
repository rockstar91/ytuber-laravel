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
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;

class Completion extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Completions::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];
    public static function label()
    {
        return 'Выполнения';
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
            BelongsTo::make('User'),
            BelongsTo::make('Task type','TaskType','App\Nova\TaskType')->display('name'),
            BelongsTo::make('Task'),
            Number::make('цена','action_cost')
                ->sortable(),
            BelongsTo::make('account'),
            Boolean::make('status')
                ->trueValue('4')
                ->falseValue('5'),
            Number::make('Статус','status')->sortable(),
            Number::make('Было','youtube'),
            Number::make('Стало','youtube_now'),
            DateTime::make('Создано','created_at')
                ->sortable(),
            DateTime::make('Обновлено','updated_at')
                ->sortable(),
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
        return [new Actions\CheckWorkAction];
    }
}
