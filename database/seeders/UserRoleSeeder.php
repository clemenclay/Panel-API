<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        // Asignar rol 'admin' al primer usuario
        $user = User::first();
        $role = Role::firstWhere('name', 'admin');

        if ($user && $role) {
            $user->assignRole($role);
        }
    }
}
