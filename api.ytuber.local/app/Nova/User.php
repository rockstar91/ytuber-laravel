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
use \App\Nova\Metrics\CountNewUser;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'username';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'username', 'email',
    ];
    public static function label()
    {
        return 'Пользователи';
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


            ExternalImage::make('avatar', function () {
                $channel = $this->channel;
                if($channel){
                    return 'https://api.ytuber.ru/images/'. $channel . '.jpg';
                }
                else{
                    return 'https://api.ytuber.ru/images/user.png';
                }

            })->height(88),

            Text::make('username')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('channel')
                ->sortable()
                ->rules( 'max:255'),

            Number::make('balance')
                ->sortable(),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),
            HasMany::make('Tasks','tasks','App\Nova\Task'),
            HasMany::make('Каналы','account','App\Nova\Account'),
            Boolean::make('banned')
                ->trueValue('1')
                ->falseValue('0')
                ->sortable(),

            Boolean::make('disabled')
                ->trueValue('1')
                ->falseValue('0'),

            Boolean::make('active')
                ->trueValue('1')
                ->falseValue('0'),
                
            Number::make('done')
                ->sortable(),

            Number::make('failed')
                ->sortable(),
            Number::make('bot_detection')
                ->sortable(),
            
            Boolean::make('admin')
                ->trueValue('1')
                ->falseValue('0'),

            Boolean::make('moderator')
                ->trueValue('1')
                ->falseValue('0'),
            DateTime::make('Регистрация','created_at'),
            HasMany::make('Выполнения','completions','\App\Nova\Completion'),
            HasMany::make('Оплаты','payment','\App\Nova\Payment'),
            HasMany::make('Перводы полученные','recipients','\App\Nova\Transaction'),
            HasMany::make('Перводы отправленные','transactions','\App\Nova\Transaction'),
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
        return [
            new CountNewUser()
        ];
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
