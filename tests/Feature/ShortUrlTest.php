<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;

    public function superadmin_cannot_create_short_url()
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->post('/urls', [
                'original_url' => 'https://google.com'
            ])
            ->assertStatus(403);
    }

    public function admin_can_create_short_url()
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/urls', [
                'original_url' => 'https://google.com'
            ])
            ->assertRedirect('/urls');
    }

    public function member_can_create_short_url()
    {
        $member = User::factory()->member()->create();

        $this->actingAs($member)
            ->post('/urls', [
                'original_url' => 'https://google.com'
            ])
            ->assertRedirect('/urls');
    }

    public function admin_cannot_see_urls_from_own_company()
    {
        $admin = User::factory()->admin()->create(['company_id' => 1]);

        ShortUrl::factory()->create(['company_id' => 1]);

        $this->actingAs($admin)
            ->get('/urls')
            ->assertDontSee('/r/');
    }

    public function member_cannot_see_own_urls()
    {
        $member = User::factory()->member()->create(['company_id' => 1]);

        $url = ShortUrl::factory()->create([
            'created_by' => $member->id
        ]);

        $this->actingAs($member)
            ->get('/urls')
            ->assertDontSee($url->short_code);
    }

    public function short_url_is_publicly_resolvable()
    {
        $url = ShortUrl::factory()->create([
            'short_code' => 'abc123',
            'original_url' => 'https://example.com'
        ]);

        $this->get('/s/' . $url->short_code)
            ->assertRedirect('https://example.com');
    }
}
