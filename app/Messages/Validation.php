<?php

namespace App\Messages;

$userProductValidationAr = [
    'quantity' => [
        'out_of_stock' => [
            'المنتج حاليا غير متوفر',
        ],
    ],
];

$userProductValidationEn = [
    'quantity' => [
        'out_of_stock' => [
            'Product is currently not available',
        ],
    ],
];

$userValidation = [
    'ar' => [
        'products' => $userProductValidationAr,
        'item' => 'true'
    ],
    'en' => [
        'products' => $userProductValidationEn,
        'item' => 'true'
    ],
];

$userValidationEn = $userValidation['en'];

$userValidationAr = $userValidation['ar'];

class Validation
{

    static $userEn = $userValidationEn;

    static $userAr = $userValidationAr;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
}



