<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SipbarCoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_is_accessible(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('SIPBAR');
    }

    public function test_inventory_page_requires_authentication(): void
    {
        $response = $this->get('/inventory');

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_inventory(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/inventory');

        $response->assertOk();
        $response->assertSee('Inventaris Barang');
    }
}
