<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();

        $admin = Role::create(['name' => 'Admin']);
        $user = Role::create(['name' => 'User']);

        $admin->givePermissionTo([
            'dashboard.index',
            'roles.read',
            'roles.write',
            'roles.create',
            'roles.delete',
            'users.read',
            'users.write',
            'users.create',
            'users.delete',
            'permissions.read',
            'permissions.write',
            'permissions.create',
            'permissions.delete',
        ]);

        $user->givePermissionTo([
            'dashboard.index',
            'users.read',
            'users.write',
            'users.create',
            'users.delete',
        ]);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info('Roles seeded successfully!');

    }
}
