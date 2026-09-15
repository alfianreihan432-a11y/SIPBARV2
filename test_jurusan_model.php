<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Jurusan Model:\n";
echo "======================\n\n";

$jurusan = new \App\Models\Jurusan();
echo "Table name: " . $jurusan->getTable() . "\n";
echo "Primary key: " . $jurusan->getKeyName() . "\n";
echo "\n";

echo "Fetching Jurusan with ID 1:\n";
$jur = \App\Models\Jurusan::find(1);
if ($jur) {
    echo "  Found: ID={$jur->id}, nama={$jur->nama}, kode={$jur->kode}\n";
} else {
    echo "  Not found\n";
}
echo "\n";

echo "Testing User->jurusan relationship:\n";
$user = \App\Models\User::find(20);
echo "  User: {$user->name}\n";
echo "  User jurusan_id: " . ($user->jurusan_id ?? 'NULL') . "\n";

// Get the relationship instance
$relation = $user->jurusan();
echo "  Relationship class: " . get_class($relation) . "\n";
echo "  Foreign key: " . $relation->getForeignKeyName() . "\n";
echo "  Owner key: " . $relation->getOwnerKeyName() . "\n";
echo "  Related table: " . $relation->getRelated()->getTable() . "\n";

// Execute the relationship query
$result = $relation->first();
echo "  Query result: " . ($result ? "Found (ID={$result->id}, nama={$result->nama})" : "NULL") . "\n";
echo "\n";

echo "SQL Query:\n";
echo "  " . $relation->toSql() . "\n";
echo "  Bindings: " . json_encode($relation->getBindings()) . "\n";
