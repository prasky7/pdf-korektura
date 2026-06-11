<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Title;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $editorRole = Role::create(['name' => 'Editor']);
        $grafikRole = Role::create(['name' => 'Grafik']);
        $korektorRole = Role::create(['name' => 'Korektor']);

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@localhost',
            'username' => 'admin',
            'password' => bcrypt('admin'),
        ]);
        $admin->assignRole($adminRole);

        Title::create(['name' => 'MFDnes', 'description' => 'Mladá fronta Dnes']);
        Title::create(['name' => 'Metro', 'description' => 'Metro']);
        Title::create(['name' => 'Magazín 1', 'description' => 'Magazín 1']);
    }
}
