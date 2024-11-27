<?php

namespace App\Data\Shared;

use Illuminate\Database\Eloquent\Collection;

/**
 * @template Model
 * @template Pivot
 *
 * @extends Collection<int, Model&PivotContainer<Pivot>>
 */
class ModelwithPivotCollection extends Collection
{
}
