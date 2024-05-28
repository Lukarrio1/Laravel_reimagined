<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TenantRoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'can export'],
            ['name' => 'can import'],
            ['name' => 'can crud nodes'],
            ['name' => 'can crud permissions',],
            ['name' => 'can crud roles',],
            ['name' => 'can crud users',],
            ['name' => 'can clear cache',],
            ['name' => 'can crud settings',],
        ];

        // $role = ' api owner';

        // $role = Role::create(['name' => $role]);
        $super_admin = Role::create(['name' => "Super Admin"]);

        foreach ($permissions as $permission) {
            $permission = Permission::create($permission);
            // $role->givePermissionTo($permission->name);
            $super_admin->givePermissionTo($permission->name);
        }

        Setting::updateOrCreate(['key' => 'admin_role'], ['properties' => $super_admin->name . '_' . $super_admin->id]);

        $super_admin_user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin123')
        ]);
        $super_admin_user->assignRole($super_admin);
    }
}
