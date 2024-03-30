<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'f_name' => ['required', 'string', 'max:150'],
            'l_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users')->ignore($user->id)],
            'telegram_id' => ['nullable', 'digits_between:1,255', Rule::unique('users')->ignore($user->id)],
            'locale' => ['required', 'string', 'in:ru,en'],
            'is_dark' => ['required', 'boolean'],
            'is_formal' => ['required', 'boolean'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'f_name' => $input['f_name'],
                'l_name' => $input['l_name'],
                'email' => $input['email'],
                'telegram_id' => $input['telegram_id'],
                'locale' => $input['locale'],
                'is_dark' => $input['is_dark'],
                'is_formal' => $input['is_formal'],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'f_name' => $input['f_name'],
            'l_name' => $input['l_name'],
            'email' => $input['email'],
            //'tg' => $input['tg'],
            'locale' => $input['locale'],
            //'is_dark' => $input['is_dark'],
            //'is_formal' => $input['is_formal'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
