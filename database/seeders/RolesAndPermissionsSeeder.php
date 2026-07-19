<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Citizen
            'view schemes', 'apply schemes', 'chat sathi',
            'manage own documents', 'manage own family',
            'view own applications', 'file grievance',

            // Seva Mitra
            'view Seva Mitra Dashboard', 'manage csc customers',
            'use Seva Mitra Toolkit', 'view csc earnings',
            'accept sathi tasks', 'access customer documents',

            // Sathi Agent
            'view sathi panel', 'manage sathi tasks',
            'view citizen profiles', 'escalate to specialist',
            'assign tasks to Seva Mitras',

            // Specialist
            'view specialist panel', 'handle pro cases',
            'view full citizen profiles',

            // Admin
            'manage all users', 'approve agents', 'reject agents',
            'suspend agents', 'manage schemes database',
            'manage opportunities database', 'view platform analytics',
            'manage roles', 'create sathi agents',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name'       => $permission,
                'guard_name' => 'sanctum',
            ]);
        }

        // Citizen
        $citizen = Role::firstOrCreate(['name' => 'citizen', 'guard_name' => 'sanctum']);
        $citizen->givePermissionTo([
            'view schemes', 'apply schemes', 'chat sathi',
            'manage own documents', 'manage own family',
            'view own applications', 'file grievance',
        ]);

        // Seva Mitra
        $csc = Role::firstOrCreate(['name' => 'seva_mitra', 'guard_name' => 'sanctum']);
        $csc->givePermissionTo([
            'view schemes', 'view Seva Mitra Dashboard', 'manage csc customers',
            'use Seva Mitra Toolkit', 'view csc earnings',
            'accept sathi tasks', 'access customer documents',
        ]);

        // Sathi Agent
        $sathi = Role::firstOrCreate(['name' => 'sathi_agent', 'guard_name' => 'sanctum']);
        $sathi->givePermissionTo([
            'view sathi panel', 'manage sathi tasks',
            'view citizen profiles', 'escalate to specialist',
            'assign tasks to Seva Mitras',
        ]);

        // Specialist
        $specialist = Role::firstOrCreate(['name' => 'specialist', 'guard_name' => 'sanctum']);
        $specialist->givePermissionTo([
            'view specialist panel', 'handle pro cases',
            'view full citizen profiles',
        ]);

        // Admin — gets everything
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'sanctum']);
        $admin->givePermissionTo(Permission::all());

        $this->command->info('✅ 5 roles and 28 permissions seeded!');
    }
}
