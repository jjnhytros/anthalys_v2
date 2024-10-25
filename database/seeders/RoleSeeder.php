<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Creazione dei ruoli
        $adminRole = Role::create(['name' => 'admin']);
        $governmentRole = Role::create(['name' => 'government']);
        $bankRole = Role::create(['name' => 'bank']);
        $supplierRole = Role::create(['name' => 'supplier']);
        $vendorRole = Role::create(['name' => 'vendor']);

        // Definizione dei permessi
        Permission::create(['name' => 'manage warehouse']);
        Permission::create(['name' => 'manage local market']);
        Permission::create(['name' => 'view reports']);

        // Assegna permessi ai ruoli (adatta in base alle esigenze)
        $adminRole->givePermissionTo(['manage warehouse', 'manage local market', 'view reports']);
        $governmentRole->givePermissionTo(['view reports']);
        $vendorRole->givePermissionTo(['manage local market']);
        $supplierRole->givePermissionTo(['manage warehouse']);
    }
}
