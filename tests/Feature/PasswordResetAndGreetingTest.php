<?php

namespace Tests\Feature;

use App\Livewire\AddStudent;
use App\Livewire\AddTeacher;
use App\Models\Category;
use App\Models\Classroom;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PasswordResetAndGreetingTest extends TestCase
{
    /**
     * Test login with sample existing Guru and Siswa using password "password"
     */
     public function test_existing_guru_and_siswa_can_login_with_new_default_password(): void
     {
         Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
         Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);

         $guru = User::whereHas('roles', function ($q) {
             $q->where('name', 'guru');
         })->first();

         if (!$guru) {
             $guru = User::factory()->create([
                 'name' => 'Guru Sample Test',
                 'email' => 'guru_sample_test@smkn1bangsri.sch.id',
                 'nip' => '198501012010011005',
                 'password' => Hash::make('old_password_guru'),
             ]);
             $guru->assignRole('guru');
         }

         $siswa = User::whereHas('roles', function ($q) {
             $q->where('name', 'siswa');
         })->first();

         if (!$siswa) {
             $siswa = User::factory()->create([
                 'name' => 'Siswa Sample Test',
                 'email' => 'siswa_sample_test@smkn1bangsri.sch.id',
                 'nis' => '45678901',
                 'password' => Hash::make('old_password_siswa'),
             ]);
             $siswa->assignRole('siswa');
         }

         // Run the seeder to test mass reset logic
         $this->artisan('sipbar:reset-default-password', ['--force' => true, '--no-backup' => true])
             ->assertSuccessful();

         $guru->refresh();
         $siswa->refresh();

         $this->assertTrue(Hash::check('password', $guru->password), 'Password Guru harus cocok dengan "password".');
         $this->assertTrue(Hash::check('password', $siswa->password), 'Password Siswa harus cocok dengan "password".');

         // 1. Test Login Guru
         $responseGuru = $this->post('/login', [
             'email' => $guru->email,
             'password' => 'password',
         ]);
         $responseGuru->assertRedirect();
         $this->assertAuthenticatedAs($guru);

         Auth::logout();

         // 2. Test Login Siswa
         $responseSiswa = $this->post('/login', [
             'email' => $siswa->email,
             'password' => 'password',
         ]);
         $responseSiswa->assertRedirect();
         $this->assertAuthenticatedAs($siswa);

         Auth::logout();
     }

    /**
     * Test creating a new student via AddStudent Livewire component
     */
    public function test_add_new_student_uses_password_default(): void
    {
        Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $admin = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->first();

        if (!$admin) {
            $admin = User::factory()->create(['email' => 'admin_test_pwd@sipbar.id']);
            $admin->assignRole('admin');
        }

        $uniqueNis = 'TEST' . rand(100000, 999999);

        Livewire::actingAs($admin)
            ->test(AddStudent::class)
            ->set('nis', $uniqueNis)
            ->set('name', 'Bambang Siswa Baru Setyawan')
            ->set('kelas', 'X TKJ 1')
            ->set('jurusan', 'Teknik Komputer dan Jaringan')
            ->set('tanggal_lahir', '2008-05-12')
            ->set('phone', '081234567891')
            ->set('alamat', 'Jl. Pendidikan No. 10')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSee('Password default: password');

        $newStudent = User::where('nis', $uniqueNis)->first();
        $this->assertNotNull($newStudent);
        $this->assertTrue(Hash::check('password', $newStudent->password));

        // Test Login with newly created student
        Auth::logout();
        $response = $this->post('/login', [
            'email' => $newStudent->email,
            'password' => 'password',
        ]);
        $response->assertRedirect();
        $this->assertAuthenticatedAs($newStudent);

        // Cleanup
        $newStudent->delete();
    }

    /**
     * Test creating a new teacher via AddTeacher Livewire component
     */
    public function test_add_new_teacher_uses_password_default(): void
    {
        Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $admin = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->first();

        if (!$admin) {
            $admin = User::factory()->create(['email' => 'admin_test_pwd2@sipbar.id']);
            $admin->assignRole('admin');
        }

        $uniqueNip = '1990' . rand(10000000, 99999999);

        Livewire::actingAs($admin)
            ->test(AddTeacher::class)
            ->set('nip', $uniqueNip)
            ->set('name', 'Dra. Siti Aminah, M.Pd')
            ->set('phone', '081298765432')
            ->set('jabatan', 'Guru Produktif')
            ->set('jurusan', 'Teknik Komputer dan Jaringan')
            ->set('tanggal_lahir', '1990-08-17')
            ->set('alamat', 'Jl. Guru Bangsri')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSee('Password default: password');

        $newTeacher = User::where('nip', $uniqueNip)->first();
        $this->assertNotNull($newTeacher);
        $this->assertTrue(Hash::check('password', $newTeacher->password));

        // Test Login with newly created teacher
        Auth::logout();
        $response = $this->post('/login', [
            'email' => $newTeacher->email,
            'password' => 'password',
        ]);
        $response->assertRedirect();
        $this->assertAuthenticatedAs($newTeacher);

        // Cleanup
        $newTeacher->delete();
    }

    /**
     * Test Teacher Dashboard Greeting displays Full Name
     */
    public function test_teacher_dashboard_greeting_displays_full_name(): void
    {
        Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);

        $teacherUser = User::factory()->create([
            'name' => 'Rima Ariona Nur Awalia, S.Kom',
            'email' => 'rima_test_greeting@smkn1bangsri.sch.id',
            'nip' => '199510102020122001',
        ]);
        $teacherUser->assignRole('guru');

        $response = $this->actingAs($teacherUser)->get(route('teacher.dashboard'));
        $response->assertStatus(200);

        // Must see full name in greeting, not just "Rima"
        $response->assertSee('Rima Ariona Nur Awalia, S.Kom');

        // Cleanup
        $teacherUser->delete();
    }
}
