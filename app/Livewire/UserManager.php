<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    public $users;
    public $roles;

    public $name = '';
    public $email = '';
    public $password = '';
    public $role = '';
    public $editingId = null;

    protected $rules = [
        'name' => 'required|string|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'role' => 'required|string|exists:roles,name',
    ];

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

    public function save(): void
    {
        $rules = $this->rules;

        if ($this->editingId) {
            $rules['email'] = 'required|email|unique:users,email,'.$this->editingId;
            $rules['password'] = 'nullable|string|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);
            $user->update($data);
        } else {
            $data['password'] = Hash::make($this->password);
            $user = User::create($data);
        }

        $user->syncRoles([$this->role]);

        $this->resetForm();
        $this->loadUsers();
        session()->flash('message', 'Pengguna berhasil disimpan.');
    }

    public function edit(int $id): void
    {
        $user = User::findOrFail($id);

        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()?->name ?? '';
        $this->password = '';
    }

    public function delete(int $id): void
    {
        $user = User::findOrFail($id);
        $user->delete();

        $this->loadUsers();
        session()->flash('message', 'Pengguna berhasil dihapus.');
    }

    public function resetForm(): void
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = '';
        $this->editingId = null;
    }
}
