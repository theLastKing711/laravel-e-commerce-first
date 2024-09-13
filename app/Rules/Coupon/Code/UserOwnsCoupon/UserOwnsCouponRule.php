<?php

namespace App\Rules\Coupon\Code\UserOwnsCoupon;

use App\Models\Coupon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class UserOwnsCouponRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $authenticatedUser = auth()->user();

        $coupon = Coupon::where('code', $value)
            ->first();

        $is_coupon_for_user = $authenticatedUser
            ->coupons()
            ->where('coupon_id', $coupon->id)
            ->exists();

        if (! $is_coupon_for_user) {
            $fail(':attribute does not belong to the user');
        }
    }
}
