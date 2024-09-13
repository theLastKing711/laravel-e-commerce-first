<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Storage;

class FileService
{
    public static function upload(string $directory, ?UploadedFile $file): ?string
    {

        if ($file === null) {
            return null;
        }

        if (! Storage::exists($directory)) {
            Storage::makeDirectory($directory, 0777, true, true);
        }
        Storage::put($directory, $file);

        $imageHashName = $file->hashName();

        return $imageHashName;
    }

    //    public function update(string $directory, string? $fileName): ?string {
    //
    //        $storedImagePath = 'storage/' . $directory . '/' . $fileName;
    //
    //        if(Storage::exists($storedImagePath) && $fileName !== null)
    //        {
    //
    //        }
    //
    //        if($file === null)
    //        {
    //            return null;
    //        }
    //
    //        Storage::put($directory, $file);
    //
    //        $imageHashName = $file->hashName();
    //
    //        return  $imageHashName;
    //    }

    public static function delete(string $directory, string $filename): bool
    {
        $imagePath = 'storage'.'/'.$directory.'/'.$filename;

        return Storage::delete($imagePath);
    }

    public static function getWebLocation(string $directory, ?string $fileName): ?string
    {
        if ($fileName === null) {
            return null;
        }
        //        return Storage::url($directory . '/' . $fileName);
        $imageStoragePath = 'storage'.'/'.$directory.'/'.$fileName;

        return asset($imageStoragePath);
    }
}
