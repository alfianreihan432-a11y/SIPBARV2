<?php

namespace App\Livewire;

use App\Models\Classroom;
use App\Models\User;
use Livewire\Component;

class AddClassroom extends Component
{
    public $name;
    public $kode;
    public $class_leader_id;
    public $class_advisor_id;
    public $class_advisor_phone;
    public $is_pkl = false;

    public $students;
    public $teachers;

    protected $rules = [
        'name' => 'required|string|unique:classes,name',
        'kode' => 'required|string|unique:classes,kode',
        'class_leader_id' => 'nullable|exists:users,id',
        'class_advisor_id' => 'nullable|exists:users,id',
        'class_advisor_phone' => 'nullable|string|min:9|max:20',
        'is_pkl' => 'boolean',
    ];

    public function mount()
    {
        $this->students = User::whereHas('roles', function($query) {
            $query->where('name', 'siswa');
        })->get();
        
        $this->teachers = User::whereHas('roles', function($query) {
            $query->where('name', 'guru');
        })->get();
    }

    public function submit()
    {
        $this->validate();

        try {
            Classroom::create([
                'name' => $this->name,
                'kode' => $this->kode,
                'class_leader_id' => $this->class_leader_id,
                'class_advisor_id' => $this->class_advisor_id,
                'class_advisor_phone' => $this->class_advisor_phone,
                'is_pkl' => $this->is_pkl,
                'status' => 1,
            ]);

            session()->flash('success', 'Kelas berhasil ditambahkan.');

            $this->reset(['name', 'kode', 'class_leader_id', 'class_advisor_id', 'class_advisor_phone', 'is_pkl']);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menambahkan kelas: ' . $e->getMessage());
            \Log::error('AddClassroom error: ' . $e->getMessage(), [
                'data' => [
                    'name' => $this->name,
                    'kode' => $this->kode,
                    'class_leader_id' => $this->class_leader_id,
                    'class_advisor_id' => $this->class_advisor_id,
                    'class_advisor_phone' => $this->class_advisor_phone,
                    'is_pkl' => $this->is_pkl,
                ]
            ]);
        }
    }

    public function render()
    {
        return view('livewire.add-classroom');
    }
}
