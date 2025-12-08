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
        $kassubag = User::create([
            'name' => 'kassubag',
            'email' => 'kassubag@katingankab.go.id',
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
        ]);
        $kassubag->assignRole('kassubag');

        $sekdis = User::create([
            'name' => 'sekdis',
            'email' => 'sekdis@katingankab.go.id',
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
        ]);
        $sekdis->assignRole('sekdis');

        $kadis = User::create([
            'name' => 'kadis',
            'email' => 'kadis@katingankab.go.id',
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
        ]);
        $kadis->assignRole('kadis');
    }
}
