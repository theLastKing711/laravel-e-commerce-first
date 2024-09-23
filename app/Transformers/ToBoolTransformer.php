<?php

namespace App\Transformers;

use Illuminate\Support\Facades\Log;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class ToBoolTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): bool
    {

        if ($value === 'true' || $value === 'TRUE') {
            return true;
        }

        if ($value === 'false' || $value === 'FALSE') {
            return false;
        }

        return $value;
    }
}
