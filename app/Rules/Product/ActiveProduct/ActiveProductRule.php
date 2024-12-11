<?php

namespace App\Rules\Product\ActiveProduct;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ActiveProductRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $id, Closure $fail): void
    {

        $isProductActive =
            Product::query()
                ->firstWhereId($id);
        // ->is_active;

        if (! $isProductActive) {
            $fail('The :attribute is invalid, product is currently not on sale.');
        }

    }
}
