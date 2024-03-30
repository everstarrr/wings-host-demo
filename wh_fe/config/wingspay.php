<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Panel API settings
    |--------------------------------------------------------------------------
    |
    | Here you may specify panel API connection settings
    |
    */

    'url' => [
        'base' => env('PAY_URL', 'https://pay.wingshost.ru'),
        'api' => env('PAY_API_URL', env('PAY_URL').'/api/v1')
    ],
    'key' => env('PAY_API_KEY', 'wingspay_api_key'),
];
