<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LandingPageSiteSettingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin', 'guru', 'siswa', 'kepala_jurusan'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }

    private function makeUser(string $role, array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        $user->assignRole($role);

        return $user;
    }

    public function test_superadmin_can_access_landing_page_management(): void
    {
        $superadmin = $this->makeUser('superadmin');
        $this->actingAs($superadmin);

        $response = $this->get(route('superadmin.landing-page.index'));

        $response->assertOk();
        $response->assertSee('Kelola Landing Page');
        $response->assertSee('1. Logo Landing Page');
        $response->assertSee('2. Logo Halaman Login');
        $response->assertSee('3. Logo Dashboard (Semua Role)');
    }

    public function test_non_superadmin_cannot_access_landing_page_management(): void
    {
        $siswa = $this->makeUser('siswa');
        $this->actingAs($siswa);

        $response = $this->get(route('superadmin.landing-page.index'));
        $response->assertForbidden();
    }

    public function test_superadmin_can_update_three_separate_logos(): void
    {
        Storage::fake('public');

        $superadmin = $this->makeUser('superadmin');
        $this->actingAs($superadmin);

        $logoLanding = UploadedFile::fake()->image('logo-landing.png', 100, 100);
        $logoLogin = UploadedFile::fake()->image('logo-login.png', 100, 100);
        $logoDashboard = UploadedFile::fake()->image('logo-dashboard.png', 100, 100);

        $response = $this->post(route('superadmin.landing-page.update-general'), [
            'site_name' => 'SIPBAR TEST',
            'site_subtitle' => 'SMKN 1 BANGSRI TEST',
            'site_title' => 'SIPBAR Title Test',
            'site_logo_landing' => $logoLanding,
            'site_logo_login' => $logoLogin,
            'site_logo_dashboard' => $logoDashboard,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $savedLanding = SiteSetting::get('site_logo_landing');
        $savedLogin = SiteSetting::get('site_logo_login');
        $savedDashboard = SiteSetting::get('site_logo_dashboard');

        $this->assertNotNull($savedLanding);
        $this->assertNotNull($savedLogin);
        $this->assertNotNull($savedDashboard);

        $this->assertStringStartsWith('/storage/site-logos/', $savedLanding);
        $this->assertStringStartsWith('/storage/site-logos/', $savedLogin);
        $this->assertStringStartsWith('/storage/site-logos/', $savedDashboard);

        // Verify files exist in storage
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $savedLanding));
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $savedLogin));
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $savedDashboard));
    }

    public function test_pages_render_respective_logos(): void
    {
        SiteSetting::set('site_logo_landing', '/images/landing-custom.png', 'image', 'general');
        SiteSetting::set('site_logo_login', '/images/login-custom.png', 'image', 'general');
        SiteSetting::set('site_logo_dashboard', '/images/dash-custom.png', 'image', 'general');

        // 1. Landing Page renders landing logo
        $response = $this->get(route('home'));
        $response->assertOk();
        $response->assertSee('/images/landing-custom.png');

        // 2. Login Page renders login logo
        $response = $this->get(route('login'));
        $response->assertOk();
        $response->assertSee('/images/login-custom.png');

        // 3. Superadmin dashboard renders dashboard logo
        $superadmin = $this->makeUser('superadmin');
        $this->actingAs($superadmin);
        $response = $this->get(route('superadmin.dashboard'));
        $response->assertOk();
        $response->assertSee('/images/dash-custom.png');
    }

    public function test_pages_render_sipbar_favicons(): void
    {
        // 1. Landing page includes favicon links
        $homeRes = $this->get(route('home'));
        $homeRes->assertOk();
        $homeRes->assertSee('favicon-32x32.png');
        $homeRes->assertSee('apple-touch-icon.png');

        // 2. Login page includes favicon links
        $loginRes = $this->get(route('login'));
        $loginRes->assertOk();
        $loginRes->assertSee('favicon-32x32.png');
        $loginRes->assertSee('apple-touch-icon.png');

        // 3. Superadmin dashboard includes favicon links
        $superadmin = $this->makeUser('superadmin');
        $this->actingAs($superadmin);
        $dashRes = $this->get(route('superadmin.dashboard'));
        $dashRes->assertOk();
        $dashRes->assertSee('favicon-32x32.png');
        $dashRes->assertSee('apple-touch-icon.png');
    }
}
