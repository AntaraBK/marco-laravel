<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
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
         ];
 
          // Looping and Inserting Array's Permissions into Permission Table
         foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
          }
    }
}
