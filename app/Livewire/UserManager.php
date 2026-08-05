<?php

namespace App\Livewire;

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

    // Active tab: 'siswa' | 'guru'
    public $activeTab = 'siswa';

    public $editingId = null;

    // ── Shared ──
    public $name = '';
    public $tanggal_lahir = '';
    public $phone = '';

    // ── Siswa only ──
    public $nis   = '';
    public $kelas = '';
    public $jurusan = '';

    // ── Guru only ──
    public $nip = '';
    public $jabatan = '';

    // ── Internal (auto-generated, not shown) ──
    public $email    = '';
    public $password = '';
    public $alamat   = '';

    public function mount(): void
    {
        $this->loadUsers();
        $this->loadRoles();
    }

    public function render()
    {
        return view('livewire.user-manager');
    }

    public function loadUsers(): void
    {
        $this->users = User::with('roles')->latest()->get();
    }

    public function loadRoles(): void
    {
        $this->roles = Role::orderBy('name')->pluck('name');
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
            'name'          => 'required|string|min:2',
            'tanggal_lahir' => 'required|date',
            'phone'         => 'required|string|min:9|max:20',
        ];

        if ($role === 'siswa') {
            $rules['nis']     = 'required|string|max:20';
            $rules['kelas']   = 'required|string|max:50';
            $rules['jurusan'] = 'required|string|max:50';
        } elseif ($role === 'guru') {
            $rules['nip']     = 'required|string|max:30';
            $rules['jabatan'] = 'required|string|max:50';
        }

        $this->validate($rules);

        // Auto-generate email & password if creating new
        if (! $this->editingId) {
            $slug  = Str::slug($this->name, '.');
            $base  = strtolower($role === 'siswa' ? ($this->nis ?: $slug) : ($this->nip ?: $slug));
            $this->email    = $base . '@sipbar.sch.id';
            $this->password = $role === 'siswa' ? 'siswa' . $this->nis : 'guru' . $this->nip;
        }

        $data = [
            'name'              => $this->name,
            'tanggal_lahir'     => $this->tanggal_lahir,
            'phone'             => $this->phone,
            'email_verified_at' => now(), // siswa & guru tidak butuh verifikasi email
        ];

        if ($role === 'siswa') {
            $data['nis']     = $this->nis;
            $data['kelas']   = $this->kelas;
            $data['jurusan'] = $this->jurusan;
        } elseif ($role === 'guru') {
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
        $this->tanggal_lahir = $user->tanggal_lahir ?? '';
        $this->phone          = $user->phone ?? '';
        $this->nis            = $user->nis   ?? '';
        $this->kelas          = $user->kelas ?? '';
        $this->jurusan        = $user->jurusan ?? '';
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
        $this->tanggal_lahir = '';
        $this->phone          = '';
        $this->nis           = '';
        $this->kelas         = '';
        $this->jurusan       = '';
        $this->nip           = '';
        $this->jabatan       = '';
        $this->email         = '';
        $this->password      = '';
    }
}
