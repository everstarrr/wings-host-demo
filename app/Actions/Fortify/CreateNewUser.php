<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

namespace App\Actions\Fortify;

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\PlansController;
use App\Models\Balance;
use App\Models\Team;
use App\Models\User;
use App\Notifications\UserRegistered;
use HCGCloud\Pterodactyl\Exceptions\InvaildApiTypeException;
use HCGCloud\Pterodactyl\Pterodactyl;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param array<string, string> $input
     * @throws \Throwable
     */
    public function create(array $input): User
    {
        $input["u_name"] = strtolower($input["u_name"]);
        Validator::make($input, [
            'f_name' => ['required', 'string', 'max:150'],
            'l_name' => ['required', 'string', 'max:150'],
            'u_name' => ['required', 'string', 'min:4', 'max:32', 'regex:/^[a-z0-9]([\w\.-]+)[a-z0-9]$/', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return DB::transaction(function () use ($input) {
            return tap(User::create([
                'f_name' => $input['f_name'],
                'l_name' => $input['l_name'],
                'u_name' => strtolower($input['u_name']),
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]), function (User $user) use ($input) {
                $this->createTeam($user);
            });
        });
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->u_name, 2)[0],
            'personal_team' => true,
        ]));
    }
}
