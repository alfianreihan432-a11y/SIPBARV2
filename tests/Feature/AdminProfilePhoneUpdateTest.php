<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminProfilePhoneUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'guru']);
    }

    public function test_admin_can_save_phone_number(): void
    {
        $admin = User::factory()->create([
            'email' => 'admintu@smkn1bangsri.sch.id',
            'name' => 'Admin TU',
        ]);
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->patch(route('settings.profile.update'), [
                'name' => $admin->name,
                'email' => $admin->email,
                'phone' => '081234567890',
            ])
            ->assertSessionHas('status', 'profile-updated');

        $admin->refresh();
        $this->assertEquals('6281234567890', $admin->phone);
    }

    public function test_phone_is_normalized_to_62_format(): void
    {
        $admin = User::factory()->create([
            'email' => 'admintu@smkn1bangsri.sch.id',
            'name' => 'Admin TU',
        ]);
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->patch(route('settings.profile.update'), [
                'name' => $admin->name,
                'email' => $admin->email,
                'phone' => '081234567890',
            ]);

        $admin->refresh();
        $this->assertEquals('6281234567890', $admin->phone);
    }

    public function test_phone_with_plus62_is_normalized(): void
    {
        $admin = User::factory()->create([
            'email' => 'admintu@smkn1bangsri.sch.id',
            'name' => 'Admin TU',
        ]);
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->patch(route('settings.profile.update'), [
                'name' => $admin->name,
                'email' => $admin->email,
                'phone' => '+6281234567890',
            ]);

        $admin->refresh();
        $this->assertEquals('6281234567890', $admin->phone);
    }

    public function test_phone_displayed_as_08_format_in_form(): void
    {
        $admin = User::factory()->create([
            'email' => 'admintu@smkn1bangsri.sch.id',
            'name' => 'Admin TU',
            'phone' => '6281234567890',
        ]);
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)
            ->get(route('profile.edit'));

        $response->assertSee('081234567890');
    }

    public function test_invalid_phone_format_is_rejected(): void
    {
        $admin = User::factory()->create([
            'email' => 'admintu@smkn1bangsri.sch.id',
            'name' => 'Admin TU',
        ]);
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->patch(route('settings.profile.update'), [
                'name' => $admin->name,
                'email' => $admin->email,
                'phone' => '12345', // Too short
            ])
            ->assertSessionHasErrors(['phone']);
    }

    public function test_phone_can_be_cleared(): void
    {
        $admin = User::factory()->create([
            'email' => 'admintu@smkn1bangsri.sch.id',
            'name' => 'Admin TU',
            'phone' => '6281234567890',
        ]);
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->patch(route('settings.profile.update'), [
                'name' => $admin->name,
                'email' => $admin->email,
                'phone' => '',
            ])
            ->assertSessionHas('status', 'profile-updated');

        $admin->refresh();
        $this->assertNull($admin->phone);
    }

    public function test_user_cannot_update_another_users_phone(): void
    {
        $admin = User::factory()->create([
            'email' => 'admintu@smkn1bangsri.sch.id',
            'name' => 'Admin TU',
        ]);
        $admin->assignRole('admin');

        $guru = User::factory()->create([
            'email' => '198505@smkn1bangsri.sch.id',
            'name' => 'Budi Santoso',
        ]);
        $guru->assignRole('guru');

        $this->actingAs($admin)
            ->patch(route('settings.profile.update'), [
                'name' => $admin->name,
                'email' => $admin->email,
                'phone' => '081234567890',
            ]);

        $guru->refresh();
        $this->assertNull($guru->phone);
    }

    public function test_phone_with_spaces_and_hyphens_is_normalized(): void
    {
        $admin = User::factory()->create([
            'email' => 'admintu@smkn1bangsri.sch.id',
            'name' => 'Admin TU',
        ]);
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->patch(route('settings.profile.update'), [
                'name' => $admin->name,
                'email' => $admin->email,
                'phone' => '0812-3456-7890',
            ]);

        $admin->refresh();
        $this->assertEquals('6281234567890', $admin->phone);
    }
}
