<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_metric_cards_have_full_width_flex_container(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('flex items-center justify-between w-full');
    }

    public function test_dashboard_displays_top_services_without_group_by_sql_error(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $service = \App\Models\Service::create([
            'name' => 'Rumah Cluster',
            'property_type' => 'rumah',
            'location' => 'Bandung',
            'price' => 500000000,
            'status' => 'available',
            'description' => 'Test property',
        ]);

        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'type' => 'income',
            'amount' => 500000000,
            'transaction_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Rumah Cluster');
    }
}
