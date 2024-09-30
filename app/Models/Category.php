<?php

namespace App\Models;

use App\Interfaces\Mediable;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 *
 *
 * @method
 * @property int $id
 * @property string|null $name
 * @property string|null $image
 * @property string|null $hash
 * @property int|null $is_special
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $children
 * @property-read int|null $children_count
 * @property-read Category|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\CategoryFactory factory($count = null, $state = [])
 * @method static Builder|Category hasParents(array $ids)
 * @method static Builder|Category isChild()
 * @method static Builder|Category isParent()
 * @method static Builder|Category latest()
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereHash($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereImage($value)
 * @method static Builder|Category whereIsSpecial($value)
 * @method static Builder|Category whereName($value)
 * @method static Builder|Category whereParentId($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \CloudinaryLabs\CloudinaryLaravel\Model\Media> $medially
 * @property-read int|null $medially_count
 * @mixin \Eloquent
 */
class Category extends Model implements Mediable
{
    use HasFactory, MediaAlly;

    protected $guarded = ['id'];

    public function children(): HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'category_product',
            'category_id',
            'product_id'
        );
    }

    public function scopeIsParent(Builder $query): void
    {
        $query->where('parent_id', null);
    }

    public function scopeIsChild(Builder $query): void
    {
        $query->whereNot('parent_id', null);
    }

    public function scopeHasParents(Builder $query, array $ids): void
    {
        $query->whereIn('parent_id', $ids);
    }

    public function scopeLatest(Builder $query): void
    {
        $query->orderByDesc('created_at');
    }
}
