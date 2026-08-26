<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = ['super-admin', 'admin', 'petugas', 'guru', 'siswa'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        Permission::firstOrCreate(['name' => 'manage inventory']);
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manage borrowings']);

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@sipbar.sch.id'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('admin123'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('super-admin');
        $admin->assignRole('admin');

        // Guru (gunakan email dummy, NIP disimpan di name sementara)
        $guru = User::firstOrCreate(
            ['email' => '198505@sipbar.sch.id'],
            [
                'name' => 'Budi Santoso',
                'password' => bcrypt('guru123'),
                'nip' => '198505',
                'email_verified_at' => now(),
            ]
        );
        $guru->assignRole('guru');

        // Siswa
        $siswa = User::firstOrCreate(
            ['email' => '4692@sipbar.sch.id'],
            [
                'name' => 'Ahmad Fauzi',
                'password' => bcrypt('siswa123'),
                'nis' => '4692',
                'email_verified_at' => now(),
            ]
        );
        $siswa->assignRole('siswa');

        $categories = [
            ['name' => 'Laptop', 'icon' => '💻', 'color' => '#2563eb', 'description' => 'Perangkat elektronik'],
            ['name' => 'Meja', 'icon' => '🪑', 'color' => '#0f766e', 'description' => 'Perabot sekolah'],
            ['name' => 'Proyektor', 'icon' => '📽️', 'color' => '#7c3aed', 'description' => 'Media pembelajaran'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $supplier = Supplier::create([
            'name' => 'CV Sumber Ilmu',
            'address' => 'Bandung',
            'email' => 'sales@sumberilmu.test',
            'phone' => '081234567890',
        ]);

        $location = Location::create([
            'building' => 'Gedung A',
            'floor' => '2',
            'room' => 'R-201',
        ]);

        Item::create([
            'code' => 'BRG-001',
            'inventory_number' => 'INV-0001',
            'name' => 'Laptop Lenovo ThinkPad',
            'description' => 'Laptop untuk kebutuhan administrasi sekolah',
            'category_id' => Category::first()->id,
            'location_id' => $location->id,
            'supplier_id' => $supplier->id,
            'brand' => 'Lenovo',
            'type' => 'ThinkPad',
            'purchase_year' => 2024,
            'price' => 14500000,
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 1,
            'barcode' => 'INV-0001',
        ]);
    }
}
