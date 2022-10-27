<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userAdmin = User::create([
            'email' => 'fadloer@gmail.com',
            'first_name' => 'Admin',
            'last_name' => 'Travel',
            'password' => Hash::make('123258'),
            'phone' => '085226262601',
            'status' => 'aktif',
            'gender' => 'l', //l. male, p. female
            'role' => 'admin',
        ]);

        $userMember = User::create([
            'email' => 'f4dlur@gmail.com',
            'first_name' => 'Member',
            'last_name' => 'Travel',
            'password' => Hash::make('123258'),
            'phone' => '085226262601',
            'status' => 'aktif',
            'gender' => 'l', //l. male, p. female
            'role' => 'member',
        ]);

        $itemRoleAdmin = Role::where('name', 'admin')->first();
        $itemRoleMember = Role::where('name', 'member')->first();
        if (!$itemRoleAdmin) {
            $itemRoleAdmin = Role::create([
                'name' => 'admin',
                'description' => 'Admin'
            ]);
        }

        if (!$itemRoleMember) {
            $itemRoleMember = Role::create([
                'name' => 'member',
                'description' => 'Member',
            ]);
        }

        RoleUser::create([
            'user_id' => $userAdmin->id,
            'role_id' => $itemRoleAdmin->id
        ]);

        RoleUser::create([
            'user_id' => $userMember->id,
            'role_id' => $itemRoleMember->id
        ]);
    }
}
