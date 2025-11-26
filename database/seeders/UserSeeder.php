<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userSuperAdmin = User::create([
            'name' => 'superadmin',
            'email' => 'superadmin@katingankab.go.id',
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
        ]);
        $userSuperAdmin->assignRole('superadmin');

        $userAdmin = User::create([
            'name' => 'admin',
            'email' => 'admin@katingankab.go.id',
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
        ]);
        $userAdmin->assignRole('admin');
    }
}
