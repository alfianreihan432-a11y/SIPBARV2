<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Classroom;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AddStudent extends Component
{
    public $nis;
    public $name;
    public $kelas;
    public $jurusan;
    public $tanggal_lahir;
    public $phone;
    public $alamat;
    public $classroom_id;

    public $classrooms;

    protected $rules = [
        'nis' => 'required|string|unique:users,nis',
        'name' => 'required|string|min:2',
        'kelas' => 'required|string',
        'jurusan' => 'required|string',
        'tanggal_lahir' => 'required|date',
        'phone' => 'required|string|min:9|max:20',
        'alamat' => 'nullable|string',
        'classroom_id' => 'nullable|exists:classrooms,id',
    ];

    public function mount()
    {
        $this->classrooms = Classroom::where('status', 1)->get();
    }

    public function submit()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'nis' => $this->nis,
            'kelas' => $this->kelas,
            'jurusan' => $this->jurusan,
            'tanggal_lahir' => $this->tanggal_lahir,
            'phone' => $this->phone,
            'alamat' => $this->alamat,
            'classroom_id' => $this->classroom_id,
            'password' => Hash::make('siswa' . $this->nis),
            'email' => $this->nis . '@sipbar.sch.id',
            'email_verified_at' => now(),
        ]);

        $user->assignRole('siswa');

        session()->flash('success', 'Siswa berhasil ditambahkan. Password default: siswa' . $this->nis);

        $this->reset(['nis', 'name', 'kelas', 'jurusan', 'tanggal_lahir', 'phone', 'alamat', 'classroom_id']);
    }

    public function render()
    {
        return view('livewire.add-student');
    }
}
