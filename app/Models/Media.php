<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $medially_type
 * @property int $medially_id
 * @property string $file_url
 * @property string $file_name
 * @property string|null $file_type
 * @property int $size
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\MediaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereFileUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereMediallyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereMediallyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUpdatedAt($value)
 * @property-read Model|\Eloquent $medially
 * @mixin \Eloquent
 */
class Media extends \CloudinaryLabs\CloudinaryLaravel\Model\Media
{
    // we did made this custom media class that ovveride cloudinary's
    //to allow usage of factory for the model
    //and possibly add more features for the model in the future
    use HasFactory;

    protected $table = 'media';
}
