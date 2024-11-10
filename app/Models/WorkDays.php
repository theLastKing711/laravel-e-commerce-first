<?php

namespace App\Models;

use App\Enum\Day;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property Day $name
 * @method static \Database\Factories\WorkDaysFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays query()
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $number
 * @property int $is_vacation
 * @property string $from
 * @property string $to
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays whereIsVacation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WorkDays extends Model
{
    use HasFactory;

    // protected function name(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (?int $value) => $value === null ? null : Day::from($value),
    //         set: function (Day $value) {
    //             return $value->value;
    //         }
    //     );
    // }

    //int when saved to db, and enum when retrieved from database
    protected function casts(): array
    {
        return [
            'name' => Day::class,
        ];
    }
}
