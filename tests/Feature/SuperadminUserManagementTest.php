<?php

namespace Tests\Feature;

use App\Livewire\UserManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SuperadminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin', 'guru', 'siswa'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }

    private function makeUser(string $role, array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        $user->assignRole($role);

        return $user;
    }

    public function test_superadmin_users_page_shows_full_access_not_read_only(): void
    {
        $superadmin = $this->makeUser('superadmin');
        $this->actingAs($superadmin);

        $response = $this->get(route('superadmin.users'));

        $response->assertOk();
        $response->assertSee('Kelola Akun Pengguna');
        $response->assertDontSee('Mode baca saja');
        $response->assertDontSee('(Read Only)');
    }

    public function test_superadmin_can_open_edit_form_for_any_user(): void
    {
        $superadmin = $this->makeUser('superadmin');
        $this->actingAs($superadmin);

        $siswa = $this->makeUser('siswa', ['name' => 'Budi Siswa', 'nis' => '12345']);

        Livewire::test(UserManager::class)
            ->call('edit', $siswa->id)
            ->assertSet('editingId', $siswa->id)
            ->assertSet('name', 'Budi Siswa');
    }

    public function test_edit_does_not_404_when_active_tab_is_kelas_or_ekstra(): void
    {
        $superadmin = $this->makeUser('superadmin');
        $this->actingAs($superadmin);

        $siswa = $this->makeUser('siswa', ['nis' => '77777']);

        // Sebelum fix: edit() mem-pass ID user ke ClassRoom::findOrFail() → 404
        Livewire::test(UserManager::class)
            ->call('setTab', 'kelas')
            ->call('edit', $siswa->id)
            ->assertSet('editingId', $siswa->id);

        Livewire::test(UserManager::class)
            ->call('setTab', 'ekstra')
            ->call('edit', $siswa->id)
            ->assertSet('editingId', $siswa->id);
    }

    public function test_superadmin_can_change_user_role_and_it_persists(): void
    {
        $superadmin = $this->makeUser('superadmin');
        $this->actingAs($superadmin);

        $siswa = $this->makeUser('siswa', ['nis' => '99999']);

        Livewire::test(UserManager::class)
            ->call('edit', $siswa->id)
            ->set('editRole', 'guru')
            ->set('nip', '198405142010011001')
            ->set('jabatan', 'Guru Matematika')
            ->set('phone', '081234567890')
            ->call('save')
            ->assertHasNoErrors();

        $siswa->refresh();
        $this->assertTrue($siswa->hasRole('guru'));
        $this->assertFalse($siswa->hasRole('siswa'));
    }

    public function test_superadmin_editing_each_record_type_opens_matching_tab(): void
    {
        $superadmin = $this->makeUser('superadmin');
        $this->actingAs($superadmin);

        $siswa = $this->makeUser('siswa', ['name' => 'Siswa Edit', 'nis' => '202401']);
        $guru = $this->makeUser('guru', ['name' => 'Guru Edit', 'nip' => '198405142010011004']);

        Livewire::test(UserManager::class)
            ->call('edit', $siswa->id)
            ->assertSet('activeTab', 'siswa')
            ->assertSet('editingId', $siswa->id);

        Livewire::test(UserManager::class)
            ->call('edit', $guru->id)
            ->assertSet('activeTab', 'guru')
            ->assertSet('editingId', $guru->id);

        $kelas = \App\Models\ClassRoom::create([
            'name' => 'XII RPL 1',
            'class_leader_name' => 'Adit',
            'class_leader_nis' => '1001',
            'homeroom_teacher' => 'Pak Dosen',
        ]);

        Livewire::test(UserManager::class)
            ->call('setTab', 'kelas')
            ->call('edit', $kelas->id)
            ->assertSet('activeTab', 'kelas')
            ->assertSet('editingId', $kelas->id);

        $ekstra = \App\Models\Extracurricular::create([
            'name' => 'Pramuka',
            'description' => 'Ketua Pramuka',
            'pembina' => 'Bu Guru',
        ]);

        Livewire::test(UserManager::class)
            ->call('setTab', 'ekstra')
            ->call('edit', $ekstra->id)
            ->assertSet('activeTab', 'ekstra')
            ->assertSet('editingId', $ekstra->id);
    }

    public function test_admin_cannot_edit_admin_or_superadmin_accounts(): void
    {
        $admin = $this->makeUser('admin');
        $this->actingAs($admin);

        $target = $this->makeUser('admin', ['name' => 'Admin Lain']);

        Livewire::test(UserManager::class)
            ->call('edit', $target->id)
            ->assertSet('editingId', null);
    }

    public function test_admin_cannot_change_role_of_other_users(): void
    {
        $admin = $this->makeUser('admin');
        $this->actingAs($admin);

        $siswa = $this->makeUser('siswa', ['nis' => '55555']);

        // Admin mencoba mengubah role siswa menjadi admin — harus ditolak server-side
        Livewire::test(UserManager::class)
            ->call('edit', $siswa->id)
            ->set('editRole', 'admin')
            ->call('save')
            ->assertHasNoErrors();

        $siswa->refresh();
        $this->assertTrue($siswa->hasRole('siswa'));
        $this->assertFalse($siswa->hasRole('admin'));
    }

    public function test_non_admin_roles_cannot_delete_users(): void
    {
        $guru = $this->makeUser('guru');
        $this->actingAs($guru);

        $siswa = $this->makeUser('siswa', ['nis' => '32123']);

        Livewire::test(UserManager::class)
            ->call('delete', $siswa->id);

        $this->assertDatabaseHas('users', ['id' => $siswa->id]);
    }

    public function test_superadmin_editing_superadmin_account_does_not_demote_role(): void
    {
        $superadmin = $this->makeUser('superadmin');
        $this->actingAs($superadmin);

        $other = $this->makeUser('superadmin', ['name' => 'Superadmin Dua']);

        Livewire::test(UserManager::class)
            ->call('edit', $other->id)
            ->set('name', 'Superadmin Dua Revisi')
            ->call('save')
            ->assertHasNoErrors();

        $other->refresh();
        $this->assertEquals('Superadmin Dua Revisi', $other->name);
        $this->assertTrue($other->hasRole('superadmin'));
    }

    // ──────────────────────────────────────────────────────────────────
    // ROUTING & MIDDLEWARE (requirement #2 & #3)
    // ─────────────────────────────────────────────────────────────────

    public function test_superadmin_users_create_route_is_registered(): void
    {
        $superadmin = $this->makeUser('superadmin');
        $this->actingAs($superadmin);

        $this->get(route('superadmin.users.create'))->assertOk();
    }

    public function test_edit_route_opens_edit_form_instead_of_404(): void
    {
        $superadmin = $this->makeUser('superadmin');
        $this->actingAs($superadmin);

        $guru = $this->makeUser('guru', ['name' => 'Guru Deep Link']);

        $response = $this->get(route('superadmin.users.edit', ['userId' => $guru->id]));

        $response->assertOk();
        // Form edit benar-benar terbuka (badge "Mode Edit" + tombol simpan perubahan),
        // bukan halaman 404 di dalam modal Livewire.
        $response->assertSee('Mode Edit');
        $response->assertSee('Simpan Perubahan');
        $response->assertSee('Guru Deep Link');
    }

    public function test_superadmin_user_page_hides_admin_creation_tab_but_keeps_role_selector(): void
    {
        $superadmin = $this->makeUser('superadmin');
        $this->actingAs($superadmin);

        $siswa = $this->makeUser('siswa', ['name' => 'Siswa Role Selector', 'nis' => '424242']);

        $response = $this->get(route('superadmin.users.edit', ['userId' => $siswa->id]));

        $response->assertOk();
        $response->assertDontSee('Tambah Admin');
        $response->assertSee('Peran Pengguna (Role)');
    }

    public function test_admin_cannot_access_superadmin_user_management(): void
    {
        $admin = $this->makeUser('admin');
        $this->actingAs($admin);

        $this->get(route('superadmin.users'))->assertForbidden();
        $this->get(route('superadmin.users.create'))->assertForbidden();
    }

    public function test_admin_user_page_has_no_role_change_controls(): void
    {
        $admin = $this->makeUser('admin');
        $this->actingAs($admin);

        $response = $this->get(route('users.index'));

        $response->assertOk();
        $response->assertDontSee('Tambah Admin');
        $response->assertDontSee('Peran Pengguna (Role)');
    }
}