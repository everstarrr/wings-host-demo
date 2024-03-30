<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

namespace App\Http\Controllers;

use App\Http\Controllers\WINGS_PAY_API\Payments;
use App\Jobs\UpdateWingsPayExtStatus;
use App\Models\Balance;
use App\Models\PanelServers;
use App\Models\Servers;
use App\Models\Team;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Notifications\PayPaymentConfirmed;
use App\Notifications\ServerSuspended;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class BalanceController extends Controller
{
    public function get(int $user = null)
    {
            return collect([
                'personal' => ['amount' => 100, 'currency' => "RUB"],
                'current' => ['amount' => 100, 'currency' => "RUB"],
                'owned' => ['amount' => 100, 'currency' => "RUB"],
                //'owned_sum' => $this->get_sum(),
                'all' => ['amount' => 100, 'currency' => "RUB"],
                //'all_sum' => $this->get_sum(strategy: 'all'),
                //'all_eo_sum' => $this->get_sum(strategy: 'all_except_owned'),
            ]);
    }

    public function get_db_for(Team|int $team)
    {
        return null;
    }


    public function get_amount(Team|Collection|\Illuminate\Support\Collection|array|int $teams)
    {
        $data = collect();
        return $data;
    }

    public function get_sum(array|Collection $teams = null, string $strategy = 'owned')
    {
        return null;
    }


    /**
     * @throws Throwable
     */
    public function write_transaction(float  $amount,
                                      string $uuid = null,
                                      int    $user_id = null,
                                      int    $server_id = null,
                                      int    $team_id = null,
                                      int    $to_team_id = null,
                                      float  $to_amount = null,
                                      float  $fee = null,
                                      string $currency = "RUB",
                                      string $to_currency = null,
                                      string $description = null,
                                      bool   $is_confirming = false)
    {
        return null;
    }

    public function get_transactions(string $uuid = null,
                                     int    $user_id = null,
                                     int    $server_id = null,
                                     int    $team_id = null,
                                     float  $amount = null,
                                     string $currency = null,
                                     string $description = null,
                                     bool   $is_confirming = false,
                                     bool   $paginate = false,
                                     int    $page = 1)
    {
        $transactions = null;
    }

    public function minus(Team|int $team, float $amount, bool $write_transaction = true, string $description = null)
    {
        return null;
    }

    public function plus(Team|int $team, float $amount, bool $write_transaction = true, string $description = null, bool $bonus = true)
    {
        return null;
    }

    public function create_payment(Request $req)
    {
        return ["error" => "failed to create WINGS PAY payment"];
    }

    public function check_payments()
    {
        return null;
    }

    public function daily_minus()
    {
        return null;
    }

    public function get_untransacted_days(Servers|int $server)
    {
        return null;
    }
}
