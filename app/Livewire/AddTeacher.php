<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AddTeacher extends Component
{
    public $nip;
    public $name;
    public $phone;
    public $jabatan;
    public $jurusan;
    public $tanggal_lahir;
    public $alamat;

    protected $rules = [
        'nip' => 'required|string|unique:users,nip',
        'name' => 'required|string|min:2',
        'phone' => 'required|string|min:9|max:20',
        'jabatan' => 'required|string',
        'jurusan' => 'required|string',
        'tanggal_lahir' => 'required|date',
        'alamat' => 'nullable|string',
    ];

    public function submit()
    {
        $this->validate();

        try {
            $jurusanId = null;
            if (!empty($this->jurusan)) {
                $j = \App\Models\Jurusan::where('nama', $this->jurusan)->orWhere('kode', $this->jurusan)->first();
                if (!$j) {
                    $j = \App\Models\Jurusan::create(['nama' => $this->jurusan, 'kode' => strtoupper(substr($this->jurusan, 0, 4))]);
                }
                $jurusanId = $j->id;
            }

            $user = User::create([
                'name' => $this->name,
                'nip' => $this->nip,
                'phone' => $this->phone,
                'jabatan' => $this->jabatan,
                'jurusan_id' => $jurusanId,
                'tanggal_lahir' => $this->tanggal_lahir,
                'alamat' => $this->alamat,
                'password' => Hash::make('password'),
                'email' => $this->nip . '@smkn1bangsri.sch.id',
                'email_verified_at' => now(),
            ]);

            $user->assignRole('guru');

            session()->flash('success', 'Guru berhasil ditambahkan. Password default: password');

            $this->reset(['nip', 'name', 'phone', 'jabatan', 'jurusan', 'tanggal_lahir', 'alamat']);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menambahkan guru: ' . $e->getMessage());
            \Log::error('AddTeacher error: ' . $e->getMessage(), [
                'data' => [
                    'name' => $this->name,
                    'nip' => $this->nip,
                    'phone' => $this->phone,
                    'jabatan' => $this->jabatan,
                    'jurusan' => $this->jurusan,
                    'tanggal_lahir' => $this->tanggal_lahir,
                    'alamat' => $this->alamat,
                ]
            ]);
        }
    }

    public function render()
    {
        return view('livewire.add-teacher');
    }
}
