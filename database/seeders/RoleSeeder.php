<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'superadmin' => ['create post', 'edit post', 'delete post', 'view post'],
            'admin' => ['create post', 'edit post', 'delete post', 'view post'],
            'kassubag' => ['create post', 'edit post', 'view post'],
            'sekdis' => ['create post', 'edit post', 'view post'],
            'kadis' => ['create post', 'edit post', 'view post'],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::create(['name' => $roleName]);
            $role->givePermissionTo($permissions);
        }
    }
}
