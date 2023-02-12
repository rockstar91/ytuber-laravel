<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class CountNewUser extends Value
{
    public $name = "Новые польователи";
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, \App\Models\User::where('active',1)->where('disabled',0));
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            1 => __('За день'),
            7 => __('Неделя'),
            30 => __('30 Дней'),
            60 => __('60 Дней'),
            365 => __('365 Дней'),
            'TODAY' => __('Сегодня'),
            'MTD' => __('В течении месяца'),
            'QTD' => __('За пол года'),
            'YTD' => __('За год'),
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'count-new-user';
    }
}
