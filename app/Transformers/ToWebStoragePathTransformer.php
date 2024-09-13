<?php

namespace App\Transformers;

use App\Services\FileService;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;


// if the value of the property is null
// it doesn't transfom
//// otherwise it return the web path of the property
///  with the combination of the $folder passed property
/// and the value of the property $value
class ToWebStoragePathTransformer implements Transformer
{
    public function __construct(private readonly string $folder)
    {
    }

    public function transform(DataProperty $property, mixed $value, TransformationContext $context): string
    {
        Log::info('folder name {folder} ', ['folder' => $this->folder]);

        return FileService::getWebLocation($this->folder, $value);
    }
}
