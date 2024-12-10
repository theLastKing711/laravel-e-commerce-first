<?php

namespace App\Rules\Coupon\quantity\GreaterThanMinItemPerOrder;

use App\Models\Setting;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class GreaterThanMinItemPerOrderRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $ordered_items, Closure $fail): void
    {
        $settings_min_item_per_order = Setting::first()
            ->order_delivery_min_item_per_order;

        if ($ordered_items < $settings_min_item_per_order) {
            $fail(':attribute is invalid,order must at least have '.$settings_min_item_per_order.' items.');
        }

    }
}
