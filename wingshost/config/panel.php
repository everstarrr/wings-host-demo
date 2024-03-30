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

    'url' => env('PANEL_URL', 'https://panel.wingshost.ru'),
    'api_app_key' => env('PANEL_API_APP_KEY', 'app_key'),
    'api_client_key' => env('PANEL_API_CLIENT_KEY', 'client_key'),

];
