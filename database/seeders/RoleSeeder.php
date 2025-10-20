<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------
        // 1️⃣ Permisos de Administrador
        // -------------------
        $adminPermissions = [
            // Inicio
            'ver-inicio', 'ver-ventasHoyReport', 'ver-grafico-mes', 'ver-boxes-reporte', 'ver-tablas-reporte',

            // Ventas
            'crear-ventas', 'ver-ventas', 'modificar-ventas', 'eliminar-ventas', 'registrar-pago',

            // Categorías
            'crear-categorias', 'ver-categorias', 'modificar-categorias', 'eliminar-categorias',

            // Productos
            'crear-productos', 'ver-productos', 'modificar-productos', 'eliminar-productos', 'buscar-productos',

            // Cabañas/Mesas
            'crear-mesa', 'ver-mesa', 'abrir-mesa', 'cerrar-mesa', 'eliminar-mesa',

            // Cocina
            'ver-cocina', 'ver-ordenes', 'cambiar-estado-orden', 'cambiar-estado-productos', 'cambiar-estado-orden-lista',

            // Factura
            'imprimir-ticket',

            // Usuarios
            'crear-usuarios', 'ver-usuarios', 'modificar-usuarios', 'eliminar-usuarios',

            // Roles
            'crear-roles', 'ver-roles', 'modificar-roles', 'eliminar-roles',
        ];

        // Crear permisos si no existen
        foreach ($adminPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear rol administrador y asignar todos los permisos
        $adminRole = Role::firstOrCreate(['name' => 'administrador']);
        $adminRole->givePermissionTo($adminPermissions);

        // -------------------
        // Permisos de Mesero
        // -------------------
        $meseroPermissions = [
            'ver-mesa', 'abrir-mesa', 'cerrar-mesa',
            'ver-ordenes', 'ver-cocina',
            'crear-ventas', 'ver-ventas', 'imprimir-ticket',
            'cambiar-estado-orden-lista'
        ];

        $meseroRole = Role::firstOrCreate(['name' => 'mesero']);
        $meseroRole->givePermissionTo($meseroPermissions);

        // -------------------
        // Permisos de Cocinero
        // -------------------
        $cocineroPermissions = [
            'ver-cocina', 'ver-ordenes', 'cambiar-estado-orden', 'cambiar-estado-productos',
        ];

        $cocineroRole = Role::firstOrCreate(['name' => 'cocinero']);
        $cocineroRole->givePermissionTo($cocineroPermissions);

        // -------------------
        // Usuario administrador inicial
        // -------------------
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@elcortez.com',
            'password' => bcrypt('password'),
        ])->assignRole('administrador');
    }
}
