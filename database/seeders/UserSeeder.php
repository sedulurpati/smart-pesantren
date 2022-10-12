<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $u = User::create([
            'name' => 'Malik',
            'email' => '085',
            'password' => Hash::make('password')
        ]);
        $u->assignRole('admin');

        $users = User::factory(30)->create();
        $role = Role::findByName('santri_baru');
        $role->users()->attach($users);
    }
}
