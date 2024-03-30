<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

namespace App\Http\Controllers;

use App\Models\Promocodes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PromocodesController extends Controller
{
    public function check(string $code = null)
    {
        return null;
    }

    public function use(string $code)
    {
        return null;
    }
}
