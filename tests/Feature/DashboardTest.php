<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function admin_can_access_dashboard()
    {

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
             ->get('/dashboard')
             ->assertStatus(200);
    }

    public function guest_cannot_access_dashboard()
    {
        $this->get('/dashboard')
             ->assertRedirect('/login');
    }
}
