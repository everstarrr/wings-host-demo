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

class CreateTeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_teams_can_be_created(): void
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $response = $this->post('/teams', [
            'name' => 'Test Team',
        ]);

        $this->assertCount(2, $user->fresh()->ownedTeams);
        $this->assertEquals('Test Team', $user->fresh()->ownedTeams()->latest('id')->first()->name);
    }
}
