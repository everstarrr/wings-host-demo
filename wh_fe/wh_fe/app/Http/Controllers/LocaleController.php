<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Update the authenticated user's current locale.
     *
     * @param  \Illuminate\Http\Request  $request
     */

    public function update(Request $request)
    {
        $locale = $request->string('locale', 'ru');

        if (!$request->user()->forceFill([
            'locale' => $locale
        ])->save()) {
            abort(403);
        }
    }
}
