<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\Jetstream\UserProfileController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\ServersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/wp', function () {
    // dd((new \App\Http\Controllers\WINGS_PAY_API\Payments())->create("test_site", 10, "test@ya.ru")->all());
    //dd((new \App\Http\Controllers\WINGS_PAY_API\Payments())->get_null_ext_status());
    dd((new BalanceController())->check_payments());
});*/

Route::group(['prefix' => 'debug', 'name' => 'debug'], function () {
    Route::get('/dm', function () {
        dd((new BalanceController())->daily_minus());
    });

    Route::get('/cp', function () {
        dd((new BalanceController())->check_payments());
    });

    Route::get('/cpup/{node?}/{plan?}', function (int $node = null, int $plan = null) {
        dd((new PlansController())->get_cpu_price(node_ext_id: $node, plan: $plan));
    });

    Route::get('/cbns', function () {
        $amount = ["!x2 day price, showing as x1!" => (new PlansController())->calc("service", null, 1 * 100, 2 * 1024, 1 * 1024)] ?? 0.1;
        dd($amount);
    });


});


/*Route::post('/sentry', function (Request $request) {
    redirect()->away(config('sentry.dsn'));
})->name('sentry');*/


Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/services/minecraft', function () {
    return Inertia::render('Services/Minecraft', ['plan' => (new PlansController())->get_latest()]);
})->name('services.minecraft');

Route::get('/services/minecraft/{type}/{ram}/{cpu}/{disk}/{is_free?}', function ($type, $ram, $cpu, $disk, $is_free = false) {
    return Inertia::render('Services/Minecraft', ['plan' => (new PlansController())->get_latest(), 'type' => $type, 'ram' => $ram, 'cpu' => $cpu, 'disk' => $disk, 'is_free' => $is_free]);
})->name('services.minecraft.builder');

Route::get('/panel/{server_id?}', function (string $server_id = null) {
    if (!$server_id) {
        return Inertia::location(config('panel.url'));
    } else {
        return Inertia::location(config('panel.url') . "/server/$server_id");
    }
})->name('panel');

Route::get('/pay/{receipt_uuid?}', function (string $receipt_uuid = null) {
    return null;
})->name('pay');


Route::group(['prefix' => 'social', 'name' => 'social'], function () {
    Route::get('/telegram/{support?}', function (string $support = null) {
        $url = !$support ? "https://t.me/wings_host" : "https://t.me/wings_support";
        return Inertia::location($url);
    })->name('social.telegram');

    Route::get('/vk/{support?}', function (string $support = null) {
        $url = !$support ? "https://vk.com/wings.host" : "https://vk.me/wings.host";
        return Inertia::location($url);
    })->name('social.vk');

    Route::get('/discord', function () {
        return Inertia::location("https://discord.gg");
    })->name('social.discord');
});


// AUTH-REQUIRED ROUTES
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('dash')->group(function () {
    // dash - summary
    Route::get('/', [UserProfileController::class, 'show'])->name('dash');

    // payments
    Route::get('/payments', function (Request $request) {
        return Inertia::render('Dash/Show', [
            'toShow' => 'payments',
        ]);
    })->name('dash.payments');


    Route::get('/account', function (Request $request) {
        return (new UserProfileController())->show($request, 'account');
    })->name('dash.account');

    // servers
    Route::group(['prefix' => 'servers'], function () {
        Route::get('/', function () {
            $dash = (new ServersController())->get_dash(active_server: 'first');
            if ($active_server = $dash->get('active_server_call')) {
                return to_route('dash.servers.server', ["server" => $active_server]);
            }
            return Inertia::render('Servers/Show', (new ServersController())->get_dash());
        })->name('dash.servers');

        Route::get('/{server}', function ($server) {
            return Inertia::render('Servers/Show', (new ServersController())->get_dash($server));
        })->name('dash.servers.server');

        Route::get('/{server}/network', function ($server) {
            return Inertia::render('Servers/Show', (new ServersController())->get_dash($server, 'network'));
        })->name('dash.servers.server.network');

        Route::get('/{server}/resources', function ($server) {
            return Inertia::render('Servers/Show', (new ServersController())->get_dash($server, 'resources'));
        })->name('dash.servers.server.resources');

        Route::get('/{server}/users', function ($server) {
            return Inertia::render('Servers/Show', (new ServersController())->get_dash($server, 'users'));
        })->name('dash.servers.server.users');

        Route::get('/{server}/startup', function ($server) {
            return Inertia::render('Servers/Show', (new ServersController())->get_dash($server, 'startup'));
        })->name('dash.servers.server.startup');

        // SERVERS SELECTOR
        Route::get('/create', function () {
            return Inertia::render('Servers/Show', (new ServersController())->get_dash('create'));
        })->name('dash.servers.create');

        Route::get('/create/{service}', function ($service) {
            return Inertia::render('Servers/Show', (new ServersController())->get_dash($service));
        })->name('dash.servers.create.service')->withoutMiddleware(['auth:sanctum', 'verified']);

        Route::get('/create/{service}/builder/{type?}/{version?}/{subtype?}/{ram?}/{cpu?}/{disk?}/{name?}', function ($service,
                                                                                                                      string $type = "",
                                                                                                                      string $version = "",
                                                                                                                      string $subtype = "",
                                                                                                                      float $ram = 1,
                                                                                                                      float $cpu = 1,
                                                                                                                      float $disk = 1,
                                                                                                                      string $name = "") {
            return Inertia::render('Servers/Show', (new ServersController())->get_dash($service . ';' . $type . ';' . $version . ';' . $subtype . ';' . $ram . ';' . $cpu . ';' . $disk . ';' . $name));
        })->
        name('dash.servers.create.builder');

        Route::get('/create/{service}/pre-builder/{type?}/{version?}/{subtype?}/{ram?}/{cpu?}/{disk?}/{name?}', function ($service,
                                                                                                                      string $type = "",
                                                                                                                      string $version = "",
                                                                                                                      string $subtype = "",
                                                                                                                      float $ram = 1,
                                                                                                                      float $cpu = 1,
                                                                                                                      float $disk = 1,
                                                                                                                      string $name = "") {
            return Inertia::render('Servers/Show', (new ServersController())->get_dash($service . ';' . $type . ';' . $version . ';' . $subtype . ';' . $ram . ';' . $cpu . ';' . $disk . ';' . $name));
        })->
        name('dash.servers.create.pre-builder')->withoutMiddleware(['auth:sanctum', 'verified']);

        Route::post('/create', function (Request $request) {
            return (new ServersController())->create_adapter($request);
        })->name('dash.servers.create.post');
    });
});
