<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['email' => 'superadmin@fstvlist.test', 'name' => 'Super Admin',  'role' => 'super_admin'],
            ['email' => 'admin@fstvlist.test',      'name' => 'Admin',         'role' => 'admin'],
            ['email' => 'validator@fstvlist.test',  'name' => 'Gate Validator','role' => 'validator'],
            ['email' => 'customer@fstvlist.test',   'name' => 'Customer Demo', 'role' => 'customer'],
        ];

        foreach ($accounts as $account) {
            $user = User::firstOrCreate(
                ['email' => $account['email']],
                ['name' => $account['name'], 'password' => Hash::make('password')]
            );
            $user->syncRoles([$account['role']]);
        }
    }
}
;