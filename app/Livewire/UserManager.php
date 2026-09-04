<?php

namespace App\Livewire;

use App\Models\Classroom;
use App\Models\Extracurricular;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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

    public function mount(): void
    {
        $this->loadUsers();
        $this->loadRoles();
        $this->loadClassrooms();
        $this->loadClassesAndExtras();
        $this->loadSyncStatus();
    }

    public function loadUsers(): void
    {
        $query = User::with('roles', 'classroom')->latest();

        if ($this->filterClassroom) {
            $query->where('classroom_id', $this->filterClassroom);
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
        $this->classrooms = Classroom::orderBy('name')->pluck('name', 'id');
    }

    public function loadClassesAndExtras(): void
    {
        $this->classes = ClassRoom::latest()->get();
        $this->extracurriculars = Extracurricular::latest()->get();
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetForm();
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

        $role = $this->activeTab;

        $rules = [
            'name' => 'required|string|min:2',
        ];

        if ($role === 'siswa') {
            $rules['nis'] = 'required|string|max:20';
            $rules['kelas'] = 'required|string|max:50';
            $rules['jurusan'] = 'required|string|max:50';
        } elseif ($role === 'guru') {
            $rules['phone'] = 'required|string|min:9|max:20';
            $rules['nip'] = 'required|string|max:30';
            $rules['jabatan'] = 'required|string|max:50';
        }

        $this->validate($rules);

        if (!$this->editingId) {
            if ($role === 'siswa') {
                $slug = Str::slug($this->name, '.');
                $base = strtolower(trim($this->nis ?: $slug));

                $this->email = $base . '@sipbar.sch.id';
                $this->password = 'siswa123';
            } else {
                $this->email = User::generateTeacherEmail(
                    $this->nip,
                    $this->tanggal_lahir ?? null
                );

                $this->password = 'guru123';
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
            $data['jurusan'] = $this->jurusan;
            $data['classroom_id'] = $this->classroom_id ?: null;
        } elseif ($role === 'guru') {
            $data['phone'] = $this->phone;
            $data['nip'] = $this->nip;
            $data['jabatan'] = $this->jabatan;
        }

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);
            $user->update($data);
        } else {
            $data['email'] = $this->email;
            $data['password'] = Hash::make($this->password);

            $user = User::create($data);
        }

        $user->syncRoles([$role]);

        $generatedEmail = $this->email;
        $generatedPassword = $this->password;
        $wasEditing = (bool) $this->editingId;

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

    public function edit(int $id): void
    {
        if ($this->activeTab === 'kelas') {
            $kelas = ClassRoom::findOrFail($id);

            $this->editingId = $kelas->id;
            $this->nama_kelas = $kelas->name;
            $this->ketua_kelas = $kelas->class_leader_name;
            $this->nis_ketua = $kelas->class_leader_nis;
            $this->wali_kelas = $kelas->homeroom_teacher;

            return;
        }

        if ($this->activeTab === 'ekstra') {
            $ekstra = Extracurricular::findOrFail($id);

            $this->editingId = $ekstra->id;
            $this->nama_ekstra = $ekstra->name;
            $this->ketua_ekstra = $ekstra->description;
            $this->pembina_ekstra = $ekstra->pembina;

            return;
        }

        $user = User::with('roles')->findOrFail($id);

        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->phone = $user->phone ?? '';
        $this->nis = $user->nis ?? '';
        $this->kelas = $user->kelas ?? '';
        $this->jurusan = $user->jurusan ?? '';
        $this->classroom_id = $user->classroom_id ?? '';
        $this->nip = $user->nip ?? '';
        $this->jabatan = $user->jabatan ?? '';
        $this->email = $user->email;

        $roleSlug = $user->roles->first()?->name ?? 'siswa';

        $this->activeTab = in_array($roleSlug, ['guru'])
            ? 'guru'
            : 'siswa';
    }

    public function delete(int $id): void
    {
        User::findOrFail($id)->delete();

        $this->loadUsers();

        session()->flash(
            'message',
            'Pengguna berhasil dihapus.'
        );
    }

    public function deleteClass(int $id): void
    {
        $class = ClassRoom::findOrFail($id);
        $class->delete();

        $this->loadClassesAndExtras();

        session()->flash(
            'message',
            'Kelas berhasil dihapus.'
        );
    }

    public function deleteEkstra(int $id): void
    {
        $ekstra = Extracurricular::findOrFail($id);
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
                'email' => $classSlug . '@sipbar.sch.id',
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
                'email' => $classSlug . '@sipbar.sch.id',
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
                'email' => $ekstraSlug . '@sipbar.sch.id',
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
                'email' => $ekstraSlug . '@sipbar.sch.id',
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
        ]);
    }
}