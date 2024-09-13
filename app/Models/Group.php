<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\GroupFactory factory($count = null, $state = [])
 * @method static Builder|Group newModelQuery()
 * @method static Builder|Group newQuery()
 * @method static Builder|Group query()
 * @method static Builder|Group whereCreatedAt($value)
 * @method static Builder|Group whereId($value)
 * @method static Builder|Group whereName($value)
 * @method static Builder|Group whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Group extends Model
{
    protected $guarded = ['id'];

    use HasFactory;

    public function users(): BelongsToMany
    {
        return $this->BelongsToMany(User::class);
    }

    /** @param Collection<int, int> $group_ids */
    /**
     * @param  array  $group_ids  ids of the groups to be used to fetch user_ids
     * @return Collection<int, int> list of user ids of the groups
     */
    public static function userIds(array $group_ids): Collection
    {
        return static::with('users')
            ->whereIn('id', $group_ids)
            ->get()
            ->map(fn (Group $group) => $group->users->pluck('id'))
            ->flatten();
    }
}
