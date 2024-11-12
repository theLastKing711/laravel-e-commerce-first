<?php

namespace App\Rules\DuringWorkHours;

use App\Rules\Product\ActiveProduct\ActiveProductRule;
use Attribute;
use Spatie\LaravelData\Attributes\Validation\CustomValidationAttribute;
use Spatie\LaravelData\Support\Validation\ValidationPath;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class DuringWorkHours extends CustomValidationAttribute
{
    /**
     * @return array<object|string>|object|string
     */
    public function getRules(ValidationPath $path): array|object|string
    {
        return [new ActiveProductRule()];
    }
}
