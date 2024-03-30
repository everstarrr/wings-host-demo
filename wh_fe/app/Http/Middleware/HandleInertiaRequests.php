<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

namespace App\Http\Middleware;

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\ServersController;
use App\Models\Balance;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        if ($user) {
            $locale = $user->locale;
        } else {
            $locale = config('app.locale');
        }

        return array_merge(parent::share($request), [
            'ver' => config('app.version'),
            'lang' => $locale,
            'fallbackLang' => config('app.fallback_locale'),
            //'wh_servers' => fn () => (new ServersController())->get_wh(),
            //'panel_servers' => fn() => (new ServersController())->get_for(), // TODO: pass data to needed component (summary)
        ]);
    }
}
