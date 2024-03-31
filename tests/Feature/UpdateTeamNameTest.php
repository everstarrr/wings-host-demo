<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTeamNameTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_names_can_be_updated(): void
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $response = $this->put('/teams/'.$user->currentTeam->id, [
            'name' => 'Test Team',
        ]);

        $this->assertCount(1, $user->fresh()->ownedTeams);
        $this->assertEquals('Test Team', $user->currentTeam->fresh()->name);
    }
}
