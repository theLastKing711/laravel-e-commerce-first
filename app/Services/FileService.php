<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Storage;

class FileService
{
    public function upload(string $directory, ?UploadedFile $file): ?string {

        if($file === null)
        {
            return null;
        }

        $categoryDirectory = 'category';

        Storage::put($categoryDirectory, $file);

        $categoryImageName = $file->hashName();

        return  $categoryImageName;
    }

    public function getWebLocation(string $directory, ?string $fileName): ?string
    {
        if($fileName === null) return null;
//        return Storage::url($directory . '/' . $fileName);
        $imageStoragePath = 'storage' . '/' . $directory . '/' . $fileName;
        return asset($imageStoragePath);
    }

}
