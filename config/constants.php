<?php

//user validation
//user product valdiation
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
//end user product validation

$userValidation = [
    'ar' => [
        'product' => $userProductValidationAr,
    ],
    'en' => [
        'product' => $userProductValidationEn,
    ],
];

$userValidationEn = $userValidation['en'];

$userValidationAr = $userValidation['ar'];
//end user validation

return [
    'local_week_days' => [
        '0' => '2',
        '1' => '3',
        '2' => '4',
        '3' => '5',
        '4' => '6',
        '5' => '1',
    ],
    'validation' => [
        'user' => [
            'ar' => $userValidationAr,
            'en' => $userValidationEn,
        ],
    ],

];
