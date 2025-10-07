<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Categorias
            'create-categories',
            'read-categories',
            'update-categories',
            'delete-categories',

            // Productos
            'create-products',
            'read-products',
            'update-products',
            'delete-products',

            // Ventas
            'create-sales',
            'read-sales',
            'update-sales',
            'delete-sales',

            // Cabañas/Mesas
            'create-cabin',
            'read-cabin',
            'open-cabin',
            'close-cabin',
            'delete-cabin',

            // Cocina
            'read-kitchen',
            'read-orders',
            'change-order-status',
            'change-product-status',

            // Reports

            'read-ventasHoyReport',
            'read-month-graph',
            'read-boxes-report',
            'read-tables-report',

            // Factura
            'print-invoice',

            // Usuarios
            'create-users',
            'read-users',
            'update-users',
            'delete-users',

            // Roles
            'create-roles',
            'read-roles',
            'update-roles',
            'delete-roles',

            // Permissions
            'create-permissions',
            'read-permissions',
            'update-permissions',
            'delete-permissions',
        ];

        foreach($permissions as $permission){
            Permission::create(['name' => $permission]);
        }

        Role::create(['name' => 'superadmin'])->givePermissionTo(Permission::all());

        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'useradmin@gmail.com',
            'password' => bcrypt('password'),
        ])->assignRole('superadmin');
    }
}
