<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin_role = Role::create(['name' => 'admin']);
        $admin_permission = Permission::create(['name' => 'manage users']);

        $admin_role->givePermissionTo($admin_permission);
        $admin_permission->assignRole($admin_role);

        $user = User::find(1);
        $user->assignRole($admin_role);

        $student_role = Role::create(['name'=>'student']);
    }
}
