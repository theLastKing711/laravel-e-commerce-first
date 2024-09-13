<?php

namespace App\Rules\DuringWorkHours;

use App\Models\WorkDays;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class DuringWorkHoursRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $local_week_numbers = config('constants.local_week_days');

        $current_local_day_of_week = $local_week_numbers[Carbon::today()->dayOfWeek];

        $today_as_work_day = WorkDays::where('number', $current_local_day_of_week)
            ->first();

        $is_today_vacation = $today_as_work_day->is_vacation;

        if ($is_today_vacation) {
            $fail('The :attribute is invalid, cannot order on a vacation day.');
        }

        $today_work_time_from = $today_as_work_day->from;

        $today_work_time_to = $today_as_work_day->to;

        $delivery_time = Carbon::parse($value)->format('H:i:s');

        $is_delivery_before_work_hours = $delivery_time <= $today_work_time_from;

        $is_delivery_past_work_hours = $delivery_time >= $today_work_time_to;

        $is_delivery_time_outside_work_hours = $is_delivery_before_work_hours || $is_delivery_past_work_hours;

        if ($is_delivery_time_outside_work_hours) {
            $fail('The :attribute is invalid, cannot order during non-work hours.');
        }

    }
}
