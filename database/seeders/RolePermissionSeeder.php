<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $ownerRole = Role::create([
            'name' => 'owner'
        ]);

        $buyerRole = Role::create([
            'name' => 'buyer'
        ]);

        $user = User::create([
            'name' => 'Mas Daniel',
            'email' => 'daniel@owner.com',
            'password' => bcrypt('123123123')
        ]);

        $user->assignRole($ownerRole);

         // Verify role assignment
         dd($user->roles); // This should show the role(s) assigned to the user
    }
}
