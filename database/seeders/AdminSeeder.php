<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // membuat role
        $role_super_admin = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $role_admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $role_sekretariat = Role::create(['name' => 'sekretariat', 'guard_name' => 'web']);
        $role_bendahara = Role::create(['name' => 'bendahara', 'guard_name' => 'web']);
        $role_hankamtib = Role::create(['name' => 'hankamtib', 'guard_name' => 'web']);
        $role_madin = Role::create(['name' => 'madin', 'guard_name' => 'web']);
        $role_tamu = Role::create(['name' => 'tamu', 'guard_name' => 'web']);
        $role_santri_baru = Role::create(['name' => 'santri_baru', 'guard_name' => 'web']);
        $role_santri = Role::create(['name' => 'santri', 'guard_name' => 'web']);
        $role_alumni = Role::create(['name' => 'alumni', 'guard_name' => 'web']);
        $role_asatidz = Role::create(['name' => 'asatidz', 'guard_name' => 'web']);

        // membuat permission
        $a[0] = Permission::create(['name' => 'admin.santri.show', 'guard_name' => 'web']);
        $a[1] = Permission::create(['name' => 'admin.santri.edit', 'guard_name' => 'web']);
        $a[2] = Permission::create(['name' => 'admin.santri.delete', 'guard_name' => 'web']);

        // mendaftarkan role ke permission
        $role_admin->givePermissionTo($a);
    }
}
