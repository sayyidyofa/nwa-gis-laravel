<?php

// seeder ini sdh direplace oleh LaratrustSeeder
use App\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends \Illuminate\Database\Seeder
{
    public function run() {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'user']);

        $role2 = Role::create(['name' => 'admin']);

        $role3 = Role::create(['name' => 'sadmin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = Factory(App\User::class)->create([
            'name' => 'Example User',
            'email' => 'user@example.com',
        ]);
        $user->assignRole($role1);

        $user = Factory(App\User::class)->create([
            'name' => 'Example Admin User',
            'email' => 'admin@example.com',
        ]);
        $user->assignRole($role2);

        $user = Factory(App\User::class)->create([
            'name' => 'Example Super-Admin User',
            'email' => 'sadmin@example.com',
        ]);
        $user->assignRole($role3);
    }
}
