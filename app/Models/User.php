<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enum\Auth\RolesEnum;
use App\Enum\Gender;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * 
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed|null $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property Gender $gender
 * @property string|null $number
 * @property string|null $dial_code
 * @property string|null $account_registration_step
 * @property string|null $code
 * @property string|null $temp_number
 * @property string|null $temp_dial_code
 * @property string|null $temp_code
 * @property string|null $username
 * @property float|null $lat
 * @property float|null $lon
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Coupon> $coupons
 * @property-read int|null $coupons_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $driver_orders
 * @property-read int|null $driver_orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Group> $groups
 * @property-read int|null $groups_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static Builder|User like(string $column, string $value)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User permission($permissions, $without = false)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null, $without = false)
 * @method static Builder|User whereAccountRegistrationStep($value)
 * @method static Builder|User whereCode($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDialCode($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereGender($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLat($value)
 * @method static Builder|User whereLon($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User whereNumber($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereTempCode($value)
 * @method static Builder|User whereTempDialCode($value)
 * @method static Builder|User whereTempNumber($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUsername($value)
 * @method static Builder|User withoutPermission($permissions)
 * @method static Builder|User withoutRole($roles, $guard = null)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $favouriteProducts
 * @property-read int|null $favourite_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|User isUser()
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    //User section
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    //User section
    public function driver_orders(): HasMany
    {
        return $this->hasMany(Order::class, 'driver_id');
    }

    public function groups(): BelongsToMany
    {
        return $this->BelongsToMany(Group::class);
    }

    public function coupons(): BelongsToMany
    {
        return $this->BelongsToMany(Coupon::class);
    }

    public function notifications(): BelongsToMany
    {
        return $this->belongsToMany(Notification::class);
    }

    public function favouriteProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'user_favourite_product');
    }

    public function routeNotificationForWhatsApp()
    {
        return $this->number;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function gender(): Attribute
    {
        return Attribute::make(
            get: fn (?int $value) => $value === null ? null : Gender::from($value),
            set: function (Gender $value) {
                return $value->value;
            }
        );
    }

    public function scopeLike(Builder $query, string $column, string $value): void
    {
        $query->where($column, 'LIKE', '%'.$value.'%');
    }

    public function scopeIsUser(Builder $query): void
    {
        $user_role = RolesEnum::USER->value;
        $query->role($user_role);
    }
}
