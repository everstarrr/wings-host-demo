<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

namespace App\Console;

use App\Http\Controllers\BalanceController;
use App\Models\PanelServers;
use GuzzleHttp\Exception\GuzzleException;
use HCGCloud\Pterodactyl\Exceptions\InvaildApiTypeException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        /*$schedule->call(function () {
            try {
                return (new BalanceController)->daily_minus();
            } catch (\Exception|\Throwable $e) {
                Log::error($e);
                throw $e;
            }//->name('balance daily minus');
        })->name('daily_minus')->everyMinute();*/

        /*$schedule->call(function () {
            try {
                return (new BalanceController())->check_payments();
            } catch (\Throwable $e) {
                Log::error($e);
                return $e;
            }
        })->name('check_payments')->everyThreeMinutes();*/

        /*$schedule->call(function () {
            try {
                return PanelServers::whereOomDisabled(true)->forceFill(['oom_disabled' => false])->saveOrFail();
            } catch (\Throwable $e) {
                Log::error($e);
                return $e;
            }
        })->name('oom_enable')->everyTwoHours();*/

//        $schedule->call(function () {
//            DB::statement("ALTER TABLE transaction_history DROP id");
//            DB::statement("ALTER TABLE transaction_history AUTO_INCREMENT = 1");
//            DB::statement("ALTER TABLE transaction_history ADD `id` int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
//
//            /*$th_maxId = DB::table('transaction_history')->max('id');
//            DB::statement("ALTER TABLE transaction_history AUTO_INCREMENT=$th_maxId");*/
//            DB::statement("ANALYZE TABLE transaction_history");
//        })->name('normalize_ids')->everyThirtyMinutes();

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
