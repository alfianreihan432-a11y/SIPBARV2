<?php

namespace App\Livewire;

use App\Models\Classroom;
use App\Models\Extracurricular;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    protected $listeners = ['userUpdated' => 'loadUsers'];

    public $classes;
    public $extracurriculars;
    public $users;
    public $roles;
    public $classrooms;
    public $filterClassroom = '';

    // Active tab: 'siswa' | 'guru' | 'kelas' | 'ekstra'
    public $activeTab = 'siswa';

    public $editingId = null;

    // ── Role pengguna (hanya Superadmin yang boleh mengubah) ──
    public $editRole = '';

    // ── Shared ──
    public $name = '';
    public $phone = '';

    // ── Siswa only ──
    public $nis = '';
    public $kelas = '';
    public $jurusan = '';
    public $classroom_id = '';

    // ── Guru only ──
    public $nip = '';
    public $jabatan = '';

    // ── Internal ──
    public $email = '';
    public $password = '';

    // ── Kelas fields ──
    public $nama_kelas = '';
    public $ketua_kelas = '';
    public $nis_ketua = '';
    public $wali_kelas = '';

    // ── Ekstra fields ──
    public $nama_ekstra = '';
    public $ketua_ekstra = '';
    public $pembina_ekstra = '';

    public $alamat = '';

    // ── SIJUNA Sync State ──
    public $sijunaLoading = false;
    public $sijunaMessage = '';
    public $sijunaSuccess = false;

    // ── SiPintu Sync Status ──
    public $syncStatus = null;
    public $syncMessage = '';
    public $syncStats = null;
    public $isSyncRunning = false;

    public function fetchSijunaData(): void
    {
        $this->sijunaLoading = true;
        $this->sijunaMessage = '';
        $this->sijunaSuccess = false;

        $sipintu = app(\App\Services\SipintuService::class);

        if ($this->activeTab === 'siswa') {
            if (empty($this->nis)) {
                $this->sijunaMessage = 'Masukkan NIS terlebih dahulu untuk mencari data siswa dari SIJUNA.';
                $this->sijunaLoading = false;
                return;
            }

            $result = $sipintu->getStudents(nis: trim($this->nis));

            if ($result['success'] && !empty($result['data'])) {
                $student = is_array($result['data'][0] ?? null)
                    ? $result['data'][0]
                    : $result['data'];

                $this->name = $student['name']
                    ?? $student['nama']
                    ?? $this->name;

                $this->kelas = $student['kelas']
                    ?? $student['class']
                    ?? $this->kelas;

                $this->jurusan = $student['jurusan']
                    ?? $student['major']
                    ?? $this->jurusan;

                $this->sijunaSuccess = true;
                $this->sijunaMessage =
                    'Data siswa "' . $this->name . '" berhasil diimpor dari SIJUNA!';
            } else {
                $this->sijunaMessage =
                    $result['error']
                    ?? 'Data siswa dengan NIS ' . $this->nis . ' tidak ditemukan di SIJUNA.';
            }
        } elseif ($this->activeTab === 'guru') {
            if (empty($this->nip)) {
                $this->sijunaMessage = 'Masukkan NIP terlebih dahulu untuk mencari data guru dari SIJUNA.';
                $this->sijunaLoading = false;
                return;
            }

            $result = $sipintu->getTeachers(nip: trim($this->nip));

            if ($result['success'] && !empty($result['data'])) {
                $teacher = is_array($result['data'][0] ?? null)
                    ? $result['data'][0]
                    : $result['data'];

                $this->name = $teacher['name']
                    ?? $teacher['nama']
                    ?? $this->name;

                $this->jabatan = $teacher['jabatan']
                    ?? $teacher['position']
                    ?? $this->jabatan;

                $this->phone = $teacher['phone']
                    ?? $teacher['no_hp']
                    ?? $this->phone;

                $this->sijunaSuccess = true;
                $this->sijunaMessage =
                    'Data guru "' . $this->name . '" berhasil diimpor dari SIJUNA!';
            } else {
                $this->sijunaMessage =
                    $result['error']
                    ?? 'Data guru dengan NIP ' . $this->nip . ' tidak ditemukan di SIJUNA.';
            }
        }

        $this->sijunaLoading = false;
    }

    public function mount(?int $editId = null): void
    {
        $this->loadUsers();
        $this->loadRoles();
        $this->loadClassrooms();
        $this->loadClassesAndExtras();
        $this->loadSyncStatus();

        // Deep-link dari route superadmin.users.edit → langsung buka form edit.
        if ($editId) {
            $this->edit((int) $editId);
        }
    }

    // ══════════════════════════════════════════════════════════════════
    // OTORISASI
    //  - Superadmin : akses penuh (tambah, edit, hapus, ubah role).
    //  - Admin      : boleh kelola akun siswa/guru, TIDAK boleh ubah role
    //                 pengguna lain dan tidak boleh menyentuh akun
    //                 admin/superadmin.
    //  - Role lain  : tidak boleh mengelola akun sama sekali.
    // ══════════════════════════════════════════════════════════════════

    protected function isSuperadmin(): bool
    {
        return auth()->check() && auth()->user()->hasRole('superadmin');
    }

    protected function canManageUsers(): bool
    {
        return auth()->check()
            && auth()->user()->hasAnyRole(['admin', 'superadmin']);
    }

    protected function canChangeRoles(): bool
    {
        return $this->isSuperadmin();
    }

    protected function userRoleSlug(?User $user): string
    {
        return strtolower($user?->roles->first()?->name ?? '');
    }

    /**
     * Apakah user yang login boleh mengubah/menghapus akun $user?
     */
    protected function canManageUserRecord(User $user): bool
    {
        if ($this->isSuperadmin()) {
            return true;
        }

        if (! $this->canManageUsers()) {
            return false;
        }

        // Admin tidak boleh mengubah akun admin/superadmin.
        return ! in_array(
            $this->userRoleSlug($user),
            ['admin', 'superadmin', 'super-admin'],
            true
        );
    }

    /**
     * Tab form yang boleh dipilih sesuai role user yang login.
     */
    protected function allowedTabs(): array
    {
        return $this->isSuperadmin()
            ? ['siswa', 'guru', 'kelas', 'ekstra']
            : ['siswa', 'guru', 'kelas', 'ekstra'];
    }

    public function loadUsers(): void
    {
        $query = User::with('roles', 'classroom')->latest();

        if ($this->filterClassroom) {
            // Filter by kelas field directly (since dropdown now uses kelas values)
            $query->where('kelas', $this->filterClassroom);
        }

        $this->users = $query->get();
    }

    public function updatedFilterClassroom(): void
    {
        $this->loadUsers();
    }

    public function loadRoles(): void
    {
        $this->roles = Role::orderBy('name')->pluck('name');
    }

    public function loadClassrooms(): void
    {
        // Load distinct kelas values from users table for dropdown filter
        $this->classrooms = User::whereNotNull('kelas')
            ->where('kelas', '!=', '')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas', 'kelas');
    }

    public function loadClassesAndExtras(): void
    {
        $this->classes = ClassRoom::latest()->get();
        $this->extracurriculars = Extracurricular::latest()->get();
    }

    public function setTab(string $tab): void
    {
        if (! in_array($tab, $this->allowedTabs(), true)) {
            session()->flash(
                'error',
                'Hanya Superadmin yang dapat menambahkan atau mengubah akun dengan peran ini.'
            );

            return;
        }

        $this->activeTab = $tab;
        $this->resetForm();
    }

    /**
     * Saat Superadmin mengganti role pada form edit, sesuaikan field yang tampil.
     */
    public function updatedEditRole($value): void
    {
        if (! $this->canChangeRoles() || ! $this->editingId) {
            return;
        }

        $this->activeTab = match (strtolower((string) $value)) {
            'guru' => 'guru',

            default => 'siswa',
        };
    }

    public function save(): void
    {
        \Log::info('save() called', [
            'activeTab' => $this->activeTab
        ]);

        if ($this->activeTab === 'kelas') {
            $this->saveKelas();
            return;
        }

        if ($this->activeTab === 'ekstra') {
            $this->saveEkstra();
            return;
        }

        if (! $this->canManageUsers()) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengelola akun pengguna.');
            return;
        }

        $editingUser = $this->editingId
            ? User::with('roles')->find($this->editingId)
            : null;

        if ($this->editingId && ! $editingUser) {
            session()->flash('error', 'Pengguna tidak ditemukan.');
            return;
        }

        if ($editingUser && ! $this->canManageUserRecord($editingUser)) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengubah akun ini.');
            return;
        }

        $existingRole = $this->userRoleSlug($editingUser);

        // ── Tentukan role yang akan disimpan ──
        if ($editingUser) {
            if (! $this->canChangeRoles()) {
                $attempted = strtolower((string) $this->editRole);

                if ($attempted !== '' && $attempted !== $existingRole) {
                    session()->flash('error', 'Hanya Superadmin yang dapat mengubah peran pengguna.');
                    return;
                }

                $role = $existingRole !== '' ? $existingRole : $this->activeTab;
            } else {
                $role = $this->editRole !== ''
                    ? strtolower((string) $this->editRole)
                    : $existingRole;
            }
        } else {
            $role = strtolower((string) $this->activeTab);

            if (! $this->canChangeRoles() && ! in_array($role, ['siswa', 'guru'], true)) {
                session()->flash('error', 'Hanya Superadmin yang dapat menambahkan akun dengan peran ini.');
                return;
            }
        }

        if (! in_array($role, ['siswa', 'guru', 'admin', 'superadmin', 'super-admin'], true)) {
            $role = 'siswa';
        }

        $rules = [
            'name' => 'required|string|min:2',
        ];

        if ($role === 'siswa') {
            $rules['nis'] = 'required|string|max:20';
            $rules['kelas'] = 'required|string|max:50';

            if (Schema::hasColumn('users', 'jurusan')) {
                $rules['jurusan'] = 'required|string|max:50';
            }
        } elseif ($role === 'guru') {
            $rules['phone'] = 'required|string|min:9|max:20';
            $rules['nip'] = 'required|string|max:30';
            $rules['jabatan'] = 'required|string|max:50';
        } elseif (! $editingUser) {
            $rules['email'] = 'required|email|unique:users,email';
        }

        $this->validate($rules);

        if (! $editingUser) {
            if ($role === 'siswa') {
                $slug = Str::slug($this->name, '.');
                $base = strtolower(trim($this->nis ?: $slug));

                $this->email = $base . '@smkn1bangsri.sch.id';
                $this->password = 'password';
            } elseif ($role === 'guru') {
                $this->email = User::generateTeacherEmail(
                    $this->nip,
                    $this->tanggal_lahir ?? null
                );

                $this->password = 'password';
            } else {
                // Admin / Superadmin: email tetap dapat diisi manual.
                $this->email = $this->email !== ''
                    ? $this->email
                    : Str::slug($this->name, '.') . '@smkn1bangsri.sch.id';

                $this->password = $role === 'admin' ? 'admin123' : 'superadmin123';
            }

            if (User::where('email', $this->email)->exists()) {
                session()->flash(
                    'error',
                    'Email ' . $this->email . ' sudah terdaftar.'
                );

                return;
            }

            if ($role === 'siswa' && User::where('nis', $this->nis)->exists()) {
                session()->flash(
                    'error',
                    'NIS ' . $this->nis . ' sudah terdaftar di sistem.'
                );

                return;
            }

            if ($role === 'guru' && User::where('nip', $this->nip)->exists()) {
                session()->flash(
                    'error',
                    'NIP ' . $this->nip . ' sudah terdaftar di sistem.'
                );

                return;
            }
        }

        $data = [
            'name' => $this->name,
            'email_verified_at' => now(),
        ];

        if ($role === 'siswa') {
            $data['nis'] = $this->nis;
            $data['kelas'] = $this->kelas;

            if (Schema::hasColumn('users', 'jurusan')) {
                $data['jurusan'] = $this->jurusan;
            }

            $data['classroom_id'] = $this->classroom_id ?: null;
        } elseif ($role === 'guru') {
            $data['phone'] = $this->phone;
            $data['nip'] = $this->nip;
            $data['jabatan'] = $this->jabatan;
        }

        if ($editingUser) {
            $editingUser->update($data);
            $user = $editingUser;
        } else {
            $data['email'] = $this->email;
            $data['password'] = Hash::make($this->password);

            $user = User::create($data);
        }

        $user->syncRoles([$role]);

        $generatedEmail = $this->email;
        $generatedPassword = $this->password;
        $wasEditing = (bool) $editingUser;

        $this->resetForm();
        $this->loadUsers();

        session()->flash(
            'message',
            $wasEditing
                ? 'Data pengguna berhasil diperbarui.'
                : 'Pengguna berhasil ditambahkan.'
        );

        if (!$wasEditing) {
            session()->flash('generated_email', $generatedEmail);
            session()->flash('generated_password', $generatedPassword);
        }
    }

    /**
     * Buka form edit untuk baris yang diklik.
     *
     * PENTING: baris tabel pengguna selalu mengirim ID user. Sebelumnya ID user
     * dipaksa ke ClassRoom::findOrFail()/Extracurricular::findOrFail() saat tab
     * "Kelas"/"Ekstra" aktif sehingga Livewire membalas 404 (modal "404 Not Found").
     * Sekarang: ID user dicari terlebih dahulu, lalu fallback ke kelas/ekstra.
     */
    public function edit(int $id): void
    {
        if (! $this->canManageUsers()) {
            session()->flash('error', 'Anda tidak memiliki izin untuk mengedit akun pengguna.');

            return;
        }

        // ── 1) Pada tab kelas / ekstra, prioritas selalu ke model yang sedang dibuka. ──
        if ($this->activeTab === 'kelas') {
            $kelas = ClassRoom::find($id);

            if ($kelas) {
                $this->resetForm();
                $this->activeTab = 'kelas';

                $this->editingId = $kelas->id;
                $this->nama_kelas = $kelas->name;
                $this->ketua_kelas = $kelas->class_leader_name;
                $this->nis_ketua = $kelas->class_leader_nis;
                $this->wali_kelas = $kelas->homeroom_teacher;

                return;
            }
        }

        if ($this->activeTab === 'ekstra') {
            $ekstra = Extracurricular::find($id);

            if ($ekstra) {
                $this->resetForm();
                $this->activeTab = 'ekstra';

                $this->editingId = $ekstra->id;
                $this->nama_ekstra = $ekstra->name;
                $this->ketua_ekstra = $ekstra->description;
                $this->pembina_ekstra = $ekstra->pembina;

                return;
            }
        }

        // ── 2) Akun pengguna (siswa / guru) ──
        $user = User::with('roles')->find($id);

        if ($user) {
            if (! $this->canManageUserRecord($user)) {
                session()->flash('error', 'Anda tidak memiliki izin untuk mengubah akun ini.');

                return;
            }

            $this->fillUserForm($user);

            return;
        }

        // ── 3) Tidak ditemukan → pesan ramah (bukan 404) ──
        session()->flash(
            'error',
            'Data dengan ID ' . $id . ' tidak ditemukan pada tab "' . ucfirst($this->activeTab) . '".'
        );
    }

    /**
     * Isi form dengan data akun pengguna yang akan diedit.
     */
    protected function fillUserForm(User $user): void
    {
        $roleSlug = $this->userRoleSlug($user) ?: 'siswa';

        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->phone = $user->phone ?? '';
        $this->nis = $user->nis ?? '';
        $this->kelas = $user->kelas ?? '';
        $this->jurusan = (string) ($user->getRawOriginal('jurusan') ?? '');
        $this->classroom_id = $user->classroom_id ?? '';
        $this->nip = $user->nip ?? '';
        $this->jabatan = $user->jabatan ?? '';
        $this->email = $user->email;
        $this->editRole = $roleSlug;

        $this->activeTab = match ($roleSlug) {
            'guru' => 'guru',
            'siswa' => 'siswa',
            default => 'siswa',
        };
    }

    public function delete(int $id): void
    {
        if (! $this->canManageUsers()) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus akun pengguna.');

            return;
        }

        $user = User::with('roles')->find($id);

        if (! $user) {
            session()->flash('error', 'Pengguna tidak ditemukan.');

            return;
        }

        if (! $this->canManageUserRecord($user)) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus akun ini.');

            return;
        }

        if ((int) auth()->id() === (int) $user->id) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');

            return;
        }

        $user->delete();

        $this->loadUsers();

        session()->flash(
            'message',
            'Pengguna berhasil dihapus.'
        );
    }

    public function deleteClass(int $id): void
    {
        if (! $this->canManageUsers()) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus data kelas.');

            return;
        }

        $class = ClassRoom::find($id);

        if (! $class) {
            session()->flash('error', 'Data kelas dengan ID ' . $id . ' tidak ditemukan.');

            return;
        }

        $class->delete();

        $this->loadClassesAndExtras();

        session()->flash(
            'message',
            'Kelas berhasil dihapus.'
        );
    }

    public function deleteEkstra(int $id): void
    {
        if (! $this->canManageUsers()) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menghapus data ekstrakurikuler.');

            return;
        }

        $ekstra = Extracurricular::find($id);

        if (! $ekstra) {
            session()->flash('error', 'Data ekstrakurikuler dengan ID ' . $id . ' tidak ditemukan.');

            return;
        }

        $ekstra->delete();

        $this->loadClassesAndExtras();

        session()->flash(
            'message',
            'Ekstrakurikuler berhasil dihapus.'
        );
    }

    public function resetForm(): void
    {
        $this->editingId = null;

        $this->name = '';
        $this->phone = '';
        $this->nis = '';
        $this->kelas = '';
        $this->jurusan = '';
        $this->classroom_id = '';
        $this->nip = '';
        $this->jabatan = '';
        $this->email = '';
        $this->password = '';
        $this->editRole = '';

        $this->sijunaMessage = '';
        $this->sijunaSuccess = false;
        $this->sijunaLoading = false;

        $this->nama_kelas = '';
        $this->ketua_kelas = '';
        $this->nis_ketua = '';
        $this->wali_kelas = '';

        $this->nama_ekstra = '';
        $this->ketua_ekstra = '';
        $this->pembina_ekstra = '';
    }

    // ── SiPintu Sync ──

    public function syncFromSipintu(): void
    {
        \App\Jobs\SyncSipintuUsersJob::dispatch(
            forceRefresh: true,
            batchSize: 100,
            chunkSize: 200
        );

        $this->syncStatus = 'running';
        $this->syncMessage = 'Sinkronisasi dimulai di background...';
        $this->isSyncRunning = true;

        session()->flash(
            'message',
            'Sinkronisasi dimulai di background. Proses akan berjalan beberapa menit.'
        );
    }

    public function loadSyncStatus(): void
    {
        $status = \Illuminate\Support\Facades\Cache::get(
            'sipintu_sync_status'
        );

        if ($status) {
            $this->syncStatus = $status['status'];
            $this->syncMessage = $status['message'];
            $this->syncStats = $status['stats'] ?? null;
            $this->isSyncRunning = $status['status'] === 'running';

            if ($this->syncStatus === 'completed') {
                $this->loadUsers();
            }
        } else {
            $this->syncStatus = null;
            $this->syncMessage = '';
            $this->syncStats = null;
            $this->isSyncRunning = false;
        }
    }

    // ── KELAS ──

    private function saveKelas(): void
    {
        \Log::info('saveKelas called', [
            'nama_kelas' => $this->nama_kelas,
            'ketua_kelas' => $this->ketua_kelas,
            'nis_ketua' => $this->nis_ketua,
            'wali_kelas' => $this->wali_kelas,
            'editingId' => $this->editingId,
        ]);

        $this->validate([
            'nama_kelas' =>
                'required|string|max:100|unique:classes,name,' .
                ($this->editingId ?: 'NULL'),

            'ketua_kelas' => 'required|string|max:100',
            'nis_ketua' => 'required|string|max:20',
            'wali_kelas' => 'required|string|max:100',
        ]);

        $wasEditing = (bool) $this->editingId;

        if ($wasEditing) {
            $class = ClassRoom::findOrFail($this->editingId);

            $class->update([
                'name' => $this->nama_kelas,
                'class_leader_name' => $this->ketua_kelas,
                'class_leader_nis' => $this->nis_ketua,
                'homeroom_teacher' => $this->wali_kelas,
            ]);
        } else {
            $class = ClassRoom::create([
                'name' => $this->nama_kelas,
                'class_leader_name' => $this->ketua_kelas,
                'class_leader_nis' => $this->nis_ketua,
                'homeroom_teacher' => $this->wali_kelas,
            ]);

            $classSlug = strtolower(
                str_replace(' ', '-', $this->nama_kelas)
            );

            User::create([
                'name' => $this->nama_kelas,
                'email' => $classSlug . '@smkn1bangsri.sch.id',
                'password' => bcrypt($classSlug . '123'),
            ])->assignRole('siswa');
        }

        $className = $class->name;

        $this->resetForm();
        $this->loadClassesAndExtras();

        if ($wasEditing) {
            session()->flash(
                'message',
                'Data kelas berhasil diperbarui.'
            );
        } else {
            $classSlug = strtolower(
                str_replace(' ', '-', $className)
            );

            session()->flash(
                'message',
                'Kelas berhasil ditambahkan.'
            );

            session()->flash('credentials', [
                'email' => $classSlug . '@smkn1bangsri.sch.id',
                'password' => $classSlug . '123',
            ]);
        }
    }

    // ── EKSTRA ──

    private function saveEkstra(): void
    {
        \Log::info('saveEkstra called', [
            'nama_ekstra' => $this->nama_ekstra,
            'ketua_ekstra' => $this->ketua_ekstra,
            'pembina_ekstra' => $this->pembina_ekstra,
            'editingId' => $this->editingId,
        ]);

        $this->validate([
            'nama_ekstra' =>
                'required|string|max:100|unique:extracurriculars,name,' .
                ($this->editingId ?: 'NULL'),

            'ketua_ekstra' => 'nullable|string|max:100',
            'pembina_ekstra' => 'nullable|string|max:100',
        ]);

        $wasEditing = (bool) $this->editingId;

        if ($wasEditing) {
            $ekstra = Extracurricular::findOrFail($this->editingId);

            $ekstra->update([
                'name' => $this->nama_ekstra,
                'description' => $this->ketua_ekstra,
                'pembina' => $this->pembina_ekstra,
            ]);

            \Log::info(
                'Extracurricular updated',
                ['id' => $this->editingId]
            );
        } else {
            $ekstra = Extracurricular::create([
                'name' => $this->nama_ekstra,
                'description' => $this->ketua_ekstra,
                'pembina' => $this->pembina_ekstra,
            ]);

            \Log::info(
                'Extracurricular created',
                [
                    'id' => $ekstra->id,
                    'name' => $ekstra->name
                ]
            );

            $ekstraSlug = strtolower(
                str_replace(' ', '-', $this->nama_ekstra)
            );

            $user = User::create([
                'name' => $this->nama_ekstra,
                'email' => $ekstraSlug . '@smkn1bangsri.sch.id',
                'password' => bcrypt($ekstraSlug . '123'),
            ]);

            $user->assignRole('siswa');

            \Log::info(
                'User created for extracurricular',
                [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]
            );
        }

        $ekstraName = $ekstra->name;

        $this->resetForm();
        $this->loadClassesAndExtras();

        if ($wasEditing) {
            session()->flash(
                'message',
                'Data ekstrakurikuler berhasil diperbarui.'
            );
        } else {
            $ekstraSlug = strtolower(
                str_replace(' ', '-', $ekstraName)
            );

            session()->flash(
                'message',
                'Ekstrakurikuler berhasil ditambahkan.'
            );

            session()->flash('credentials', [
                'email' => $ekstraSlug . '@smkn1bangsri.sch.id',
                'password' => $ekstraSlug . '123',
            ]);
        }
    }

    public function render()
    {
        $siswas = $this->users->filter(
            fn ($u) => $u->hasRole('siswa')
        );

        $gurus = $this->users->filter(
            fn ($u) => $u->hasRole('guru')
        );

        $classes = ClassRoom::latest()->get();

        $extracurriculars = Extracurricular::latest()->get();

        return view('livewire.user-manager', [
            'siswas' => $siswas,
            'gurus' => $gurus,
            'classes' => $classes,
            'extracurriculars' => $extracurriculars,
            'canManageUsers' => $this->canManageUsers(),
            'canChangeRoles' => $this->canChangeRoles(),
            'isSuperadmin' => $this->isSuperadmin(),
        ]);
    }
}