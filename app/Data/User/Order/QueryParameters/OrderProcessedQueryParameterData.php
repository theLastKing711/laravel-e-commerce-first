<?php

namespace App\Data\User\Order\QueryParameters;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class OrderProcessedQueryParameterData extends Data
{
    public function __construct(
        public ?bool $is_order_processed,
    ) {
    }

}
