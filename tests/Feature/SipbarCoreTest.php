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

    public function test_landing_hero_has_no_dashboard_cta_only_pelajari_lebih_lanjut(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        // Tombol CTA utama ("Dashboard") sudah dihapus (raw match, bukan escaped).
        $response->assertDontSee('class="hero-btn-main"', false);
        // Judul, subjudul & tombol "Pelajari Lebih Lanjut" tetap ada.
        $response->assertSee('hero-h1');
        $response->assertSee('hero-p');
        $response->assertSee('Pelajari Lebih Lanjut');
        $response->assertSee('class="hero-btn-alt"', false);
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
