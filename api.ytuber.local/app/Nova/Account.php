<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\Text;
use Chaseconey\ExternalImage\ExternalImage;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Illuminate\Support\Str;

class Account extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Account::class;

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
        'title',
    ];
    public static function label()
    {
        return 'Каналы';
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
            ID::make(__('ID'), 'id')->sortable(),
            ExternalImage::make('url', function () {
                $url = $this->url;
                return 'https://api.ytuber.ru/images/'. str::replace('https://www.youtube.com/channel/','',$url) . '.jpg';
            }),
            BelongsTo::make('user'),
            Text::make('Заголовок','title')->displayUsing(function($object) {
                return Str::limit($object, 60);}),
            Text::make('Описание','description')->displayUsing(function($object) {
                return Str::limit($object, 60);}),
            Boolean::make('active')
                ->trueValue('1')
                ->falseValue('0'),
            Number::make('Роликов','videoCount'),
            Number::make('Просмотров','viewCount'),
            DateTime::make('Создан','publishedAt'),
            HasMany::make('TasksComments')
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
        return [];
    }
}
