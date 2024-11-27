<?php

namespace App\Data\Shared;

/**
 * @template Pivot
 */
class PivotContainer
{
    /** @param Pivot $pivot */
    public function __construct(
        public $pivot,
    ) {
    }
}
