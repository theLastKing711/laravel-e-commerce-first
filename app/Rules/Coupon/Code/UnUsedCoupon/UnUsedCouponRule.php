<?php

namespace App\Rules\Coupon\Code\UnUsedCoupon;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class UnUsedCouponRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $code, Closure $fail): void
    {
        /** @var User $authenticatedUser */
        $authenticatedUser =
            User::query()
                ->whereHas('coupons', function (Builder $query) use ($code) {
                    $query
                        ->where('code', $code)
                        ->where('is_used', true);
                })
                ->firstWhereId(21);
        // $authenticatedUser = auth()->user();

        $is_coupon_used_by_user = (bool) $authenticatedUser;
        // $authenticatedUser
        //     ->coupons()
        //     ->where('coupon_id', $value)
        //     ->where('is_used', false)
        //     ->exists();

        if ($is_coupon_used_by_user) {
            $fail(':attribute is invalid, This coupon is already used');
        }

    }
}
