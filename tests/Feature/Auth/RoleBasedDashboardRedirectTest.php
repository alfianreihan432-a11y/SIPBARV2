<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleBasedDashboardRedirectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'kepala_jurusan', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
    }

    public function test_student_logged_in_can_access_student_dashboard(): void
    {
        $user = User::factory()->create();
        $user->assignRole('siswa');

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewIs('dashboard-siswa');
    }

    public function test_guru_logged_in_can_access_guru_dashboard(): void
    {
        $user = User::factory()->create();
        $user->assignRole('guru');

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertViewIs('dashboard-guru');
    }

    public function test_kepala_jurusan_logged_in_can_access_kajur_dashboard(): void
    {
        $user = User::factory()->create();
        $user->assignRole('kepala_jurusan');

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('kajur.dashboard'));
    }

    public function test_superadmin_logged_in_can_access_superadmin_dashboard(): void
    {
        $user = User::factory()->create();
        $user->assignRole('superadmin');

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('superadmin.dashboard'));
    }
}
