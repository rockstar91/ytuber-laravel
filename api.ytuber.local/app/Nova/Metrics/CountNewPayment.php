<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class CountNewPayment extends Value
{
    public function name()
    {
        return 'Оплаты';
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->sum($request, \App\Models\Payments::where('status', 1), 'sum')->suffix('рублей')->withoutSuffixInflection()->format('0,0');;
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
            30 => __('Месяц'),
            60 => __('3 месяца'),
            90 => __('90 дней'),
            'TODAY' => __('Сегодня'),
            'MTD' => __('За месяц'),
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
        return 'count-new-payment';
    }
}
