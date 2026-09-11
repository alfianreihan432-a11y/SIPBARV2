<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create admin role if not exists
        Role::firstOrCreate(['name' => 'admin']);

        // Disable old admin account if it exists
        $oldAdmin = User::where('email', 'admin@smkn1bangsri.sch.id')->first();
        if ($oldAdmin) {
            // Change email to make it invalid
            $oldAdmin->update([
                'email' => 'admin_old_disabled@smkn1bangsri.sch.id',
                'email_verified_at' => null,
            ]);
            $this->command->info('Old admin account disabled: admin@smkn1bangsri.sch.id');
        }

        // Create/update admin user with new credentials
        $admin = User::updateOrCreate(
            ['email' => 'admintu@smkn1bangsri.sch.id'],
            [
                'name' => 'Admin TU',
                'password' => bcrypt('admintu123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign admin role
        $admin->assignRole('admin');

        $this->command->info('Admin user created/updated: admintu@smkn1bangsri.sch.id / admintu123');

        // Create superadmin role if not exists
        Role::firstOrCreate(['name' => 'superadmin']);

        // Create superadmin user with all required fields
        $superadmin = User::updateOrCreate(
            ['email' => 'superadmin@smkn1bangsri.sch.id'],
            [
                'name' => 'Superadmin',
                'password' => bcrypt('superadmin123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign superadmin role
        $superadmin->assignRole('superadmin');

        $this->command->info('Superadmin user created/updated: superadmin@smkn1bangsri.sch.id / superadmin123');
    }
}
