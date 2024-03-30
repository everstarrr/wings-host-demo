<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\NodesController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\PromocodesController;
use App\Http\Controllers\ServersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API v1
Route::group(['prefix' => "v1"], function () {
    // no auth
    Route::get('/', function () {
        return response()->json(["version" => "v1", "status" => "available"]);
    });

    // price calculator
    Route::post('/price/calc', function (Request $request) {
        return (new PlansController())->calc(input: $request->input());
    })->name('api.v1.price.calc');

    // auth
    Route::middleware([
        'auth:sanctum',
        'web'
    ])->withoutMiddleware(['api'])->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::get("/payments/balance", function () {
            return (new BalanceController())->get()->toJson();
        })->name('api.v1.payments.balance');

        Route::middleware('throttle:pay')->post('/payments/pay', function (Request $request) {
            return null;
        })->name('api.v1.payments.pay');

        Route::get('/payments/stats', [PlansController::class, 'calc_stats'])->name('api.v1.payments.stats');

        Route::get('/payments/transactions', function (Request $request) {
            return null;
        })->name('api.v1.payments.transactions');

        // promocode checker
        Route::post('/promocode/check', function (Request $request) {
            return (new PromocodesController())->check($request->get('promocode'));
        })->name('api.v1.promocode.check');

        // servers
        Route::group(['prefix' => 'servers'], function () {
            Route::get('/', [ServersController::class, 'get_for_adapter'])->name('api.v1.servers')->withoutMiddleware(['auth:sanctum']);
            Route::get('/wh', [ServersController::class, 'get_for_wh_adapter'])->name('api.v1.servers.wh');

            // manual suspend
            Route::post('/suspend/manual', [ServersController::class, 'manual_suspend'])->name('api.v1.servers.suspend.manual');

            // switch team
            Route::post('/team/switch', [ServersController::class, 'switch_team'])->name('api.v1.servers.team.switch');

            Route::delete('/delete', [ServersController::class, 'delete_adapter'])->name('api.v1.servers.delete');

            Route::get('/network/allocations', function () {
                return (new ServersController())->get_allocations()->toJson();
            })->name('api.v1.servers.network.allocations');

            Route::post('/network/primary', function (Request $request) {
                return (new ServersController())->set_primary_allocation($request->get('server_id'), $request->get('allocation_id'));
            })->name('api.v1.servers.network.primary');

            Route::put('/resources/edit', function (Request $request) {
                return (new ServersController())->edit_resources($request->get('ext_id'), req: $request);
            })->name('api.v1.servers.resources.edit');

            Route::post('/users/get', function (Request $request) {
                return (new ServersController())->get_subusers($request->get('ext_id'));
            })->name('api.v1.servers.users.get');

            Route::post('/price/calc/adapter', function (Request $request) {
                return (new PlansController())->calc_adapter(server_ext_id: $request->get('server_ext_id'), input: $request->input())->toJson();
            })->name('api.v1.price.calc.adapter');
        })->name('api.v1.servers');

        Route::group(['prefix' => 'nodes'], function () {
            Route::get('/', [NodesController::class, 'get_adapter'])->name('api.v1.nodes')->withoutMiddleware(['auth:sanctum']);
        })->name('api.v1.nodes');
    })->name('api.v1.auth');
})->name('api.v1');
