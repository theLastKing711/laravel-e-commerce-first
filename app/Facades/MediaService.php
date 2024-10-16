<?php


namespace App\Facades;

use Illuminate\Support\Facades\Facade;


//required for laravel-ide-helper auto-complete
/**
 * @mixin \App\Services\mediaService
 *@package App\Services\Facades
 *
 */
class MediaService extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return \App\Services\mediaService::class;
    }
}
