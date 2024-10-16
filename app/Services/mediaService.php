<?php

namespace App\Services;

use App\Data\Shared\File\UpdateFileData;
use App\Interfaces\Mediable;
use Cloudinary;
use Cloudinary\Api\Exception\ApiError;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Log;

class mediaService
{
    /**
     * Delete all/one/multiple file(s) associated with a particular Model record
     *
     * @param  UpdateFileData[]|Collection<UpdateFileData>  $request_files
     * @return void
     *
     * @throws ApiError
     */
    public function updateMediaForModel(Mediable&Model $model, array|Collection $request_files)
    {

        $file_to_update_ids = $request_files->pluck('uid');

        $medias_to_delete = $model->medially()
            ->whereNotIn('id', $file_to_update_ids)
            ->get();

        Log::info('media ids to delete {ids}', ['ids' => $medias_to_delete]);

        $model->detachMedia($medias_to_delete);

        $existing_media_ids = $model->medially()
            ->pluck('id');

        $media_to_add_urls = $request_files->filter(
            fn ($file) => ! $existing_media_ids->contains($file->uid)
        )->pluck('url');

        foreach ($media_to_add_urls as &$media_url) {
            $model->attachRemoteMedia($media_url);
        }
    }

    /**
     * Delete all/one/multiple file(s) associated with a particular Model record
     *
     * @param  int[]|Collection<int, int>  $public_ids
     * @return void
     *
     * @throws ApiError
     */
    public function createMediaForModel(Mediable&Model $model, array|Collection $public_ids)
    {

        foreach ($public_ids as &$public_id) {

            $cloud_image = Cloudinary::getImage($public_id);

            $cloud_image_url = $cloud_image->toUrl();

            $model->attachRemoteMedia($cloud_image_url);
        }
    }

    /**
     * Delete all/one/multiple file(s) associated with a particular Model record
     */
    public function removeAssociatedMediaForModel(Mediable&Model $model): void
    {
        Log::info('hello world');
        $model->detachMedia();
    }
}
