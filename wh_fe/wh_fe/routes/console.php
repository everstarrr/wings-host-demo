<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

use App\Http\Controllers\BalanceController;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('balance:minus:username {username} {amount} {transaction_data=null}', function (string $username, string $amount, string $transaction_data = null) {
    $amount = floatval($amount);
    $user = \App\Models\User::whereUName(strtolower($username))->first()?->personalTeam()->id;
    if ($user) {
        $get_amount = (new BalanceController())->get_amount($user);
        $current_amount = $get_amount->get("amount");
        $new_amount = $current_amount - $amount;
        $currency = $get_amount->get("currency");
        $minus = (new BalanceController())->minus($user, amount: $amount, write_transaction: $transaction_data, description: $transaction_data);
        if ($minus) {
            $this->comment("Successfully debited $amount from $username's balance.");
            $this->comment("Old amount: $current_amount $currency");
            $this->comment("New amount: $new_amount $currency");
        } else {
            $this->comment("Failed to debit $amount from $username's balance.");
        }
    } else {
        $this->comment("User with username \"$username\" not found.");
    }
})->purpose("Debit amount from user's personal balance.");

Artisan::command('balance:plus:username {username} {amount} {bonus=true} {transaction_data=null}', function (string $username, string $amount, string $bonus = "true", string $transaction_data = null) {
    $amount = floatval($amount);
    if ($bonus == "true") $bonus = true;
    else $bonus = false;
    $user = \App\Models\User::whereUName(strtolower($username))->first()?->personalTeam()->id;
    if ($user) {
        $get_amount = (new BalanceController())->get_amount($user);
        $current_amount = $get_amount->get("amount");
        $new_amount = $current_amount + $amount;
        $currency = $get_amount->get("currency");
        $minus = (new BalanceController())->plus($user, $amount, write_transaction: $transaction_data, description: $transaction_data, bonus: $bonus);
        if ($minus) {
            $this->comment("Successfully added $amount to $username's balance.");
            $this->comment("Old amount: $current_amount $currency");
            $this->comment("New amount: $new_amount $currency");
        } else {
            $this->comment("Failed to add $amount to $username's balance.");
        }
    } else {
        $this->comment("User with username \"$username\" not found.");
    }
})->purpose("Add amount to user's personal balance.");
