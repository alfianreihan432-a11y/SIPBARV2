<?php

namespace App\Livewire;

use App\Models\Extracurricular;
use Livewire\Component;

class AddExtracurricular extends Component
{
    public $name;
    public $kode;
    public $description;
    public $pembina;
    public $pembina_phone;
    public $jadwal;
    public $status = 1;

    protected $rules = [
        'name' => 'required|string|unique:extracurriculars,name',
        'kode' => 'nullable|string',
        'description' => 'nullable|string',
        'pembina' => 'nullable|string',
        'pembina_phone' => 'nullable|string|min:9|max:20',
        'jadwal' => 'nullable|string',
        'status' => 'required|integer',
    ];

    public function submit()
    {
        $this->validate();

        Extracurricular::create([
            'name' => $this->name,
            'kode' => $this->kode,
            'description' => $this->description,
            'pembina' => $this->pembina,
            'pembina_phone' => $this->pembina_phone,
            'jadwal' => $this->jadwal,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Ekstrakurikuler berhasil ditambahkan.');

        $this->reset(['name', 'kode', 'description', 'pembina', 'pembina_phone', 'jadwal', 'status']);
    }

    public function render()
    {
        return view('livewire.add-extracurricular');
    }
}
