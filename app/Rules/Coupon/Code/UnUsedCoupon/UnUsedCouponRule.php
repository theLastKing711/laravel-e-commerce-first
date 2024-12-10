<?php

namespace App\Rules\Coupon\Code\UnUsedCoupon;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class UnUsedCouponRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = auth()->user();

        $is_coupon_used_by_user =
            $authenticatedUser
                ->coupons()
                ->where('coupon_id', $value)
                ->where('is_used', false)
                ->exists();

        if ($is_coupon_used_by_user) {
            $fail(':attribute is invalid, This coupon is already used');
        }

    }
}
