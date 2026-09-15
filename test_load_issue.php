<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing load() vs with():\n";
echo "=========================\n\n";

echo "Test 1: Fresh query with with('jurusan'):\n";
$user1 = \App\Models\User::with('jurusan')->find(20);
echo "  jurusan property: " . ($user1->jurusan ? $user1->jurusan->nama : 'NULL') . "\n";
echo "  relationLoaded: " . ($user1->relationLoaded('jurusan') ? 'yes' : 'no') . "\n";
echo "\n";

echo "Test 2: Fresh query without eager loading, then load():\n";
$user2 = \App\Models\User::find(20);
echo "  Before load - relationLoaded: " . ($user2->relationLoaded('jurusan') ? 'yes' : 'no') . "\n";
$user2->load('jurusan');
echo "  After load - relationLoaded: " . ($user2->relationLoaded('jurusan') ? 'yes' : 'no') . "\n";
echo "  jurusan property: " . ($user2->jurusan ? $user2->jurusan->nama : 'NULL') . "\n";
echo "\n";

echo "Test 3: Get via relationship method:\n";
$user3 = \App\Models\User::find(20);
$jurusan = $user3->jurusan()->first();
echo "  Via method: " . ($jurusan ? $jurusan->nama : 'NULL') . "\n";
echo "  Via property: " . ($user3->jurusan ? $user3->jurusan->nama : 'NULL') . "\n";
echo "\n";

echo "Test 4: Check attributes:\n";
$user4 = \App\Models\User::with('jurusan')->find(20);
echo "  Attributes: " . json_encode($user4->getAttributes(), JSON_PRETTY_PRINT) . "\n";
echo "  Relations: " . json_encode($user4->getRelations(), JSON_PRETTY_PRINT) . "\n";
