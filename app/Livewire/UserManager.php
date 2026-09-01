<?php

namespace App\Livewire;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    protected $listeners = ['userUpdated' => 'loadUsers'];

    public $users;
    public $roles;
    public $classrooms;
    public $filterClassroom = '';

    // Active tab: 'siswa' | 'guru'
    public $activeTab = 'siswa';

    public $editingId = null;

    // ── Shared ──
    public $name = '';
    public $phone = '';

    // ── Siswa only ──
    public $nis   = '';
    public $kelas = '';
    public $jurusan = '';
    public $classroom_id = '';

    // ── Guru only ──
    public $nip = '';
    public $jabatan = '';

    // ── Internal (auto-generated, not shown) ──
    public $email    = '';
    public $password = '';
    public $alamat   = '';

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
                $student = is_array($result['data'][0] ?? null) ? $result['data'][0] : $result['data'];
                $this->name           = $student['name']          ?? $student['nama']          ?? $this->name;
                $this->kelas          = $student['kelas']         ?? $student['class']         ?? $this->kelas;
                $this->jurusan        = $student['jurusan']       ?? $student['major']         ?? $this->jurusan;

                $this->sijunaSuccess = true;
                $this->sijunaMessage = 'Data siswa "' . $this->name . '" berhasil diimpor dari SIJUNA!';
            } else {
                $this->sijunaMessage = $result['error'] ?? 'Data siswa dengan NIS ' . $this->nis . ' tidak ditemukan di SIJUNA.';
            }
        } elseif ($this->activeTab === 'guru') {
            if (empty($this->nip)) {
                $this->sijunaMessage = 'Masukkan NIP terlebih dahulu untuk mencari data guru dari SIJUNA.';
                $this->sijunaLoading = false;
                return;
            }

            $result = $sipintu->getTeachers(nip: trim($this->nip));

            if ($result['success'] && !empty($result['data'])) {
                $teacher = is_array($result['data'][0] ?? null) ? $result['data'][0] : $result['data'];
                $this->name           = $teacher['name']          ?? $teacher['nama']          ?? $this->name;
                $this->jabatan        = $teacher['jabatan']       ?? $teacher['position']      ?? $this->jabatan;
                $this->phone          = $teacher['phone']         ?? $teacher['no_hp']        ?? $this->phone;
                
                $this->sijunaSuccess = true;
                $this->sijunaMessage = 'Data guru "' . $this->name . '" berhasil diimpor dari SIJUNA!';
            } else {
                $this->sijunaMessage = $result['error'] ?? 'Data guru dengan NIP ' . $this->nip . ' tidak ditemukan di SIJUNA.';
            }
        }

        $this->sijunaLoading = false;
    }

    public function mount(): void
    {
        $this->loadUsers();
        $this->loadRoles();
        $this->loadClassrooms();
        $this->loadSyncStatus();
    }

    public function render()
    {
        return view('livewire.user-manager');
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

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetForm();
    }

    public function save(): void
    {
        $role = $this->activeTab;

        // Dynamic validation
        $rules = [
            'name' => 'required|string|min:2',
        ];

        if ($role === 'siswa') {
            $rules['nis']     = 'required|string|max:20';
            $rules['kelas']   = 'required|string|max:50';
            $rules['jurusan'] = 'required|string|max:50';
        } elseif ($role === 'guru') {
            $rules['phone']   = 'required|string|min:9|max:20';
            $rules['nip']     = 'required|string|max:30';
            $rules['jabatan'] = 'required|string|max:50';
        }

        $this->validate($rules);

        // Auto-generate email & password if creating new
        if (! $this->editingId) {
            $slug  = Str::slug($this->name, '.');
            $base  = strtolower($role === 'siswa' ? trim($this->nis ?: $slug) : trim($this->nip ?: $slug));
            $this->email    = $base . '@sipbar.sch.id';
            $this->password = $role === 'siswa' ? 'siswa123' : 'guru123';

            // Check if email already exists
            if (User::where('email', $this->email)->exists()) {
                session()->flash('error', 'Email ' . $this->email . ' sudah terdaftar. Pengguna dengan ' . ($role === 'siswa' ? 'NIS' : 'NIP') . ' ini mungkin sudah ada di sistem.');
                return;
            }

            // Check if NIS/NIP already exists
            if ($role === 'siswa' && User::where('nis', $this->nis)->exists()) {
                session()->flash('error', 'NIS ' . $this->nis . ' sudah terdaftar di sistem.');
                return;
            }
            if ($role === 'guru' && User::where('nip', $this->nip)->exists()) {
                session()->flash('error', 'NIP ' . $this->nip . ' sudah terdaftar di sistem.');
                return;
            }
        }

        $data = [
            'name'              => $this->name,
            'email_verified_at' => now(), // siswa & guru tidak butuh verifikasi email
        ];

        if ($role === 'siswa') {
            $data['nis']     = $this->nis;
            $data['kelas']   = $this->kelas;
            $data['jurusan'] = $this->jurusan;
            $data['classroom_id'] = $this->classroom_id ?: null;
        } elseif ($role === 'guru') {
            $data['phone']   = $this->phone;
            $data['nip']     = $this->nip;
            $data['jabatan'] = $this->jabatan;
        }

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);
            $user->update($data);
        } else {
            $data['email']    = $this->email;
            $data['password'] = Hash::make($this->password);
            $user = User::create($data);
        }

        $user->syncRoles([$role]);

        $this->resetForm();
        $this->loadUsers();

        session()->flash('message', $this->editingId
            ? 'Data pengguna berhasil diperbarui.'
            : 'Pengguna berhasil ditambahkan. Email: ' . $this->email . ' | Password: ' . $this->password
        );

        // Re-flash with generated creds before reset clears them
        session()->flash('generated_email',    $this->email);
        session()->flash('generated_password', $this->password);
    }

    public function edit(int $id): void
    {
        $user = User::with('roles')->findOrFail($id);

        $this->editingId      = $user->id;
        $this->name           = $user->name;
        $this->phone          = $user->phone ?? '';
        $this->nis            = $user->nis   ?? '';
        $this->kelas          = $user->kelas ?? '';
        $this->jurusan        = $user->jurusan ?? '';
        $this->classroom_id   = $user->classroom_id ?? '';
        $this->nip            = $user->nip   ?? '';
        $this->jabatan        = $user->jabatan ?? '';
        $this->email          = $user->email;

        $roleSlug = $user->roles->first()?->name ?? 'siswa';
        $this->activeTab = in_array($roleSlug, ['guru']) ? 'guru' : 'siswa';
    }

    public function delete(int $id): void
    {
        User::findOrFail($id)->delete();
        $this->loadUsers();
        session()->flash('message', 'Pengguna berhasil dihapus.');
    }

    public function resetForm(): void
    {
        $this->editingId     = null;
        $this->name          = '';
        $this->phone         = '';
        $this->nis           = '';
        $this->kelas         = '';
        $this->jurusan       = '';
        $this->classroom_id  = '';
        $this->nip           = '';
        $this->jabatan       = '';
        $this->email         = '';
        $this->password      = '';
        $this->sijunaMessage = '';
        $this->sijunaSuccess = false;
        $this->sijunaLoading = false;
    }

    public function syncFromSipintu(): void
    {
        // Dispatch job to background queue
        \App\Jobs\SyncSipintuUsersJob::dispatch(forceRefresh: true, batchSize: 100, chunkSize: 200);

        // Update status immediately
        $this->syncStatus = 'running';
        $this->syncMessage = 'Sinkronisasi dimulai di background...';
        $this->isSyncRunning = true;

        session()->flash('message', 'Sinkronisasi dimulai di background. Proses akan berjalan beberapa menit.');
    }

    public function loadSyncStatus(): void
    {
        $status = \Illuminate\Support\Facades\Cache::get('sipintu_sync_status');

        if ($status) {
            $this->syncStatus = $status['status'];
            $this->syncMessage = $status['message'];
            $this->syncStats = $status['stats'] ?? null;
            $this->isSyncRunning = $status['status'] === 'running';

            // If sync is completed, reload users
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
}
