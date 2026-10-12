<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgenTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_fetch_agent_data_for_edit(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $agent = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@alamsari.com',
            'password' => bcrypt('password'),
            'role' => 'agen',
            'bank_account' => '1234567890',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($admin)->getJson(route('agen.show', $agent->id));

        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'Budi Santoso',
            'email' => 'budi@alamsari.com',
            'bank_account' => '1234567890',
        ]);
    }
}
