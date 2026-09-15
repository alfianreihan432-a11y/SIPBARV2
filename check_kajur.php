<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Kepala Jurusan Users:\n";
echo "=====================\n\n";

$kajurs = \App\Models\User::whereHas('roles', function($q) {
    $q->where('name', 'kepala_jurusan');
})->get();

foreach ($kajurs as $kajur) {
    echo "ID: {$kajur->id}\n";
    echo "Name: {$kajur->name}\n";
    echo "Email: {$kajur->email}\n";
    echo "jurusan_id: {$kajur->jurusan_id}\n";
    
    // Manually load jurusan
    $kajur->load('jurusan');
    echo "Jurusan loaded: " . ($kajur->jurusan ? $kajur->jurusan->nama : 'NULL') . "\n";
    echo "---\n\n";
}

echo "\nJurusans Table:\n";
echo "================\n\n";
$jurusans = \App\Models\Jurusan::all();
foreach ($jurusans as $j) {
    echo "ID: {$j->id} | Nama: {$j->nama} | Kode: {$j->kode}\n";
}
