<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Role;
use App\Models\User;
use App\Models\UserPackages;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->truncate();

        // Admin
        $adminUser = User::create([
            'name' => 'admin',
            'password' => bcrypt('12345678'),
            'email' => 'admin@gmail.com',
        ]);

        $adminUser->assignRole(1);

    }
}
