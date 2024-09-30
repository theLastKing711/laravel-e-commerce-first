<?php

namespace App\Transformers;

use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

class MillesecondsToDateTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): array
    {

        $date_collection = collect($value);

        $dates = $date_collection->map(fn ($value) => date('Y-m-d', $value / 1000));

        return $dates->toArray();
    }
}
