<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Direct Query Check:\n";
echo "===================\n\n";

// Check jurusans table
$jurusans = DB::table('jurusans')->get();
echo "Jurusans in database:\n";
foreach ($jurusans as $j) {
    echo "  ID: {$j->id} | Nama: {$j->nama} | Kode: {$j->kode}\n";
}
echo "\n";

// Check users with jurusan_id
$users = DB::table('users')->whereNotNull('jurusan_id')->get(['id', 'name', 'email', 'jurusan_id']);
echo "Users with jurusan_id:\n";
foreach ($users as $u) {
    echo "  User ID: {$u->id} | Name: {$u->name} | jurusan_id: {$u->jurusan_id}\n";
}
echo "\n";

// Try manual join
echo "Manual JOIN test:\n";
$result = DB::table('users')
    ->join('jurusans', 'users.jurusan_id', '=', 'jurusans.id')
    ->where('users.id', 20)
    ->select('users.*', 'jurusans.nama as jurusan_nama', 'jurusans.kode as jurusan_kode')
    ->first();

if ($result) {
    echo "  User: {$result->name}\n";
    echo "  Jurusan: {$result->jurusan_nama} ({$result->jurusan_kode})\n";
} else {
    echo "  No match found\n";
}
echo "\n";

// Check using Eloquent
echo "Eloquent relationship test:\n";
$user = \App\Models\User::find(20);
echo "  User ID: {$user->id}\n";
echo "  User jurusan_id: {$user->jurusan_id}\n";
echo "  Jurusan (before load): " . ($user->relationLoaded('jurusan') ? 'loaded' : 'not loaded') . "\n";

$user->load('jurusan');
echo "  Jurusan (after load): " . ($user->relationLoaded('jurusan') ? 'loaded' : 'not loaded') . "\n";
echo "  Jurusan object: " . ($user->jurusan ? 'exists' : 'null') . "\n";

if ($user->jurusan) {
    echo "  Jurusan name: {$user->jurusan->nama}\n";
} else {
    echo "  Jurusan is NULL\n";
    
    // Debug: check if jurusan_id actually exists in jurusans table
    $jurExists = DB::table('jurusans')->where('id', $user->jurusan_id)->exists();
    echo "  Does jurusan_id={$user->jurusan_id} exist in jurusans table? " . ($jurExists ? 'YES' : 'NO') . "\n";
}
