<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $adminRole = Role::create([
            'name' => 'admin',
            'description' => 'Admin for site management'
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@adminoid.com',
            'password' => Hash::make('password'),
        ]);

        $admin->roles()->attach($adminRole);
    }
}
