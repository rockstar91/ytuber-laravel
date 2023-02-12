<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;

class TasksComment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\TasksComments::class;

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
        return 'Комментарии';
    }
    public static function singularLabel()
    {
        return 'Комментарии';
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
            BelongsTo::make('Task'),
            Text::make('comment_text')
                ->sortable()
                ->rules('required', 'max:255'),
            Number::make('account_id')->sortable(),
            Number::make('status')->sortable(),
            DateTime::make('created_at')->sortable(),
            DateTime::make('updated_at')->sortable(),
            HasOne::make('Account'),
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
            new Actions\MakeCommentFree,
        ];
    }
}
