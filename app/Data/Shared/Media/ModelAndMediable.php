<?php

namespace App\Data\Shared\Media;

use App\Interfaces\Mediable;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $medially
 * @property-read int|null $medially_count
 * @method static \Illuminate\Database\Eloquent\Builder|ModelAndMediable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelAndMediable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelAndMediable query()
 * @mixin \Eloquent
 */
class ModelAndMediable extends Model implements Mediable
{
    use MediaAlly;
}
