<?php

namespace App\Interfaces;

use Cloudinary\Api\Exception\ApiError;
use CloudinaryLabs\CloudinaryLaravel\Model\Media;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

/**
 * @phpstan-require-implements CloudinaryLabs\CloudinaryLaravel\MediaAlly;
 */
interface Mediable
{
    public function medially(): MorphMany;

    /**
     * Attach Media Files to a Model
     *
     * @throws Exception
     */
    public function attachMedia($file, $options = []): void;

    /**
     * Attach Rwmote Media Files to a Model
     *
     * @throws ApiError
     */
    public function attachRemoteMedia($remoteFile, $options = []): void;

    /**
     * Get all the Media files relating to a particular Model record
     */
    public function fetchAllMedia(): \Illuminate\Database\Eloquent\Collection;

    /**
     * Get the first Media file relating to a particular Model record
     */
    public function fetchFirstMedia();

    /**
     * Delete all/one/multiple file(s) associated with a particular Model record
     */
    public function detachMedia(Media|Collection|null $media = null): void;

    /**
     * Get the last Media file relating to a particular Model record
     */
    public function fetchLastMedia();

    /**
     * Update the Media files relating to a particular Model record
     *
     * @throws Exception
     */
    public function updateMedia($file, $options = []): void;

    /**
     * Update the Media files relating to a particular Model record (Specificially existing remote files)
     *
     * @throws ApiError
     */
    public function updateRemoteMedia($file, $options = []): void;
}
