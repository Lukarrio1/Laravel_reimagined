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
            ['name' => 'can view export button'],
            ['name' => 'can import'],
            ['name' => 'can crud nodes'],
            ['name' => 'can view nodes delete button'],
            ['name' => 'can view nodes edit button'],
            ['name' => 'can view nodes edit or create form'],
            ['name' => 'can view nodes data table'],
            ['name' => 'can crud permissions',],
            ['name' => 'can view permissions delete button'],
            ['name' => 'can view permissions edit button'],
            ['name' => 'can view permissions edit or create form'],
            ['name' => 'can view permissions data table'],
            ['name' => 'can crud roles',],
            ['name' => 'can view roles delete button'],
            ['name' => 'can view roles edit button'],
            ['name' => 'can view roles edit or create form'],
            ['name' => 'can view roles data table'],
            ['name' => 'can crud users',],
            ['name' => 'can view users delete button'],
            ['name' => 'can edit users password'],
            ['name' => 'can view users edit button'],
            ['name' => 'can view users edit form'],
            ['name' => 'can view users data table'],
            ['name' => 'can view users assign roles button'],
            ['name' => 'can clear cache',],
            ['name' => 'can crud settings',],
            ['name' => 'can view settings delete button'],
            ['name' => 'can view settings edit or create form'],
            ['name' => 'can view settings data table'],
            ['name' => 'can crud tenant',],
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
