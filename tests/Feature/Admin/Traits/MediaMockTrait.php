<?php

namespace Tests\Feature\Admin\Traits;

use App\Data\Shared\File\UpdateFileData;
use App\Facades\MediaService;
use App\Interfaces\Mediable;
use Illuminate\Support\Collection;
use Mockery;

trait MediaMockTrait
{

    public function mockMediaCreate(): void
    {
        MediaService::shouldReceive('createMediaForModel')
            ->with(
                Mockery::type(Mediable::class),
                Mockery::type(Collection::class)
            )
            ->once();
    }

    public function mockMediaUpdate(): void
    {
        MediaService::shouldReceive('updateMediaForModel')
            ->with(
                Mockery::type(Mediable::class),
                Mockery::on(function ($arg) {
                    if (
                        $arg instanceof Collection &&
                        $arg->count() > 0 &&
                        $arg->first() instanceof UpdateFileData
                    ) {

                        return true;
                    }

                    return false;
                })
            )
            ->once();
    }

    public function mockMediaRemove(): void
    {
        MediaService::shouldReceive('removeAssociatedMediaForModel')
            ->with(Mockery::type(Mediable::class))
            ->once();
    }
}
