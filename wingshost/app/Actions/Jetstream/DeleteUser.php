<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

namespace App\Actions\Jetstream;

use App\Http\Controllers\ServersController;
use App\Models\PanelUser;
use App\Models\Servers;
use App\Models\Team;
use App\Models\User;
use HCGCloud\Pterodactyl\Exceptions\InvaildApiTypeException;
use HCGCloud\Pterodactyl\Pterodactyl;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * The team deleter implementation.
     *
     * @var \Laravel\Jetstream\Contracts\DeletesTeams
     */
    protected $deletesTeams;

    /**
     * Create a new action instance.
     */
    public function __construct(DeletesTeams $deletesTeams)
    {
        $this->deletesTeams = $deletesTeams;
    }

    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            $this->deletePanelUser($user);
            $this->deleteTeams($user);
            $user->deleteProfilePhoto();
            $user->tokens->each->delete();
            $user->delete();
        });
    }

    /**
     * Delete the teams and team associations attached to the user.
     */
    protected function deleteTeams(User $user): void
    {
        $user->teams()->detach();

        $user->ownedTeams->each(function (Team $team) {
            $this->deletesTeams->delete($team);
        });
    }

    /**
     * @throws InvaildApiTypeException
     */
    protected function deletePanelUser(User $user): void
    {
        $panel = new Pterodactyl(config('panel.url'), config('panel.api_app_key'), 'application');
        $panel_id = PanelUser::whereUsername($user->u_name);
        if ($panel_id->first()) {
            $panel_id = $panel_id->first()->id;
        } else {
            return;
        }
        foreach ((new ServersController())->get_for($user->id, id_only: true) as $server) {
            if ((new ServersController())->check_auth($server)) {
                $panel->servers->delete($server);
                Servers::whereExtId($server)->first()->delete();
            }
        }

        $panel->users->delete($panel_id);
    }
}
