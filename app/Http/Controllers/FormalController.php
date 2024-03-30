<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormalController extends Controller
{
    /**
     * Update the authenticated user's current locale.
     *
     * @param  \Illuminate\Http\Request  $request
     */

    public function update(Request $request)
    {
        $is_formal = $request->string('is_formal', 1);

        if (!$request->user()->forceFill([
            'is_formal' => $is_formal
        ])->save()) {
            abort(403);
        }
    }
}
