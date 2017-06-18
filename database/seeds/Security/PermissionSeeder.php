<?php

use Illuminate\Database\Seeder;
use Ventamatic\Core\User\Security\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            //Users
            [
                'name' => 'user-get',
                'display_name' => 'Obtener usuarios',
                'description' => '',
            ],
            [
                'name' => 'user-get-detail',
                'display_name' => 'Obtener detalle de usuario',
                'description' => '',
            ],
            [
                'name' => 'user-create',
                'display_name' => 'Crear usuarios',
                'description' => '',
            ],
            [
                'name' => 'user-delete',
                'display_name' => 'Eliminar usuarios',
                'description' => '',
            ],
            [
                'name' => 'user-edit',
                'display_name' => 'Editar usuarios',
                'description' => '',
            ],



            // Productos

            [
                'name' => 'product-get',
                'display_name' => 'Obtener productos',
                'description' => '',
            ],
            [
                'name' => 'product-get-detail',
                'display_name' => 'Obtener detalle del producto',
                'description' => '',
            ],
            [
                'name' => 'product-create',
                'display_name' => 'Crear producto',
                'description' => '',
            ],
            [
                'name' => 'product-delete',
                'display_name' => 'Eliminar producto',
                'description' => '',
            ],
            [
                'name' => 'product-edit',
                'display_name' => 'Editar producto',
                'description' => '',
            ],

            // Clientes

            [
                'name' => 'client-get',
                'display_name' => 'Obtener clientes',
                'description' => '',
            ],
            [
                'name' => 'client-get-detail',
                'display_name' => 'Obtener detalle del cliente',
                'description' => '',
            ],
            [
                'name' => 'client-create',
                'display_name' => 'Crear cliente',
                'description' => '',
            ],
            [
                'name' => 'client-delete',
                'display_name' => 'Eliminar cliente',
                'description' => '',
            ],
            [
                'name' => 'client-edit',
                'display_name' => 'Editar cliente',
                'description' => '',
            ],

            // Proveedores

            [
                'name' => 'supplier-get',
                'display_name' => 'Obtener proveedores',
                'description' => '',
            ],
            [
                'name' => 'supplier-get-detail',
                'display_name' => 'Obtener detalle del proveedor',
                'description' => '',
            ],
            [
                'name' => 'supplier-create',
                'display_name' => 'Crear proveedor',
                'description' => '',
            ],
            [
                'name' => 'supplier-delete',
                'display_name' => 'Eliminar proveedor',
                'description' => '',
            ],
            [
                'name' => 'supplier-edit',
                'display_name' => 'Editar proveedor',
                'description' => '',
            ],

            // Categorias de proveedores

            [
                'name' => 'supplier-category-get',
                'display_name' => 'Obtener categorias de proveedor',
                'description' => '',
            ],
            [
                'name' => 'supplier-category-create',
                'display_name' => 'Crear categoria de  proveedor',
                'description' => '',
            ],
            [
                'name' => 'supplier-category-delete',
                'display_name' => 'Eliminar categoria de  proveedor',
                'description' => '',
            ],
            [
                'name' => 'supplier-category-edit',
                'display_name' => 'Editar categoria de  proveedor',
                'description' => '',
            ],

            // Marcas

            [
                'name' => 'brand-get',
                'display_name' => 'Obtener marcas',
                'description' => '',
            ],
            [
                'name' => 'brand-create',
                'display_name' => 'Crear marca',
                'description' => '',
            ],
            [
                'name' => 'brand-delete',
                'display_name' => 'Eliminar marca',
                'description' => '',
            ],
            [
                'name' => 'brand-edit',
                'display_name' => 'Editar marca',
                'description' => '',
            ],

            // Categorias de producto

            [
                'name' => 'category-get',
                'display_name' => 'Obtener categorias de producto',
                'description' => '',
            ],
            [
                'name' => 'category-create',
                'display_name' => 'Crear categoria de producto',
                'description' => '',
            ],
            [
                'name' => 'category-delete',
                'display_name' => 'Eliminar categoria de producto',
                'description' => '',
            ],
            [
                'name' => 'category-edit',
                'display_name' => 'Editar categoria de producto',
                'description' => '',
            ],

            // Roles

            [
                'name' => 'role-get',
                'display_name' => 'Obtener roles',
                'description' => '',
            ],
            [
                'name' => 'role-get-detail',
                'display_name' => 'Obtener detalle del rol',
                'description' => '',
            ],
            [
                'name' => 'role-create',
                'display_name' => 'Crear rol',
                'description' => '',
            ],
            [
                'name' => 'role-delete',
                'display_name' => 'Eliminar rol',
                'description' => '',
            ],
            [
                'name' => 'role-edit',
                'display_name' => 'Editar rol',
                'description' => '',
            ],

            // Branch role

            [
                'name' => 'branch-role-get',
                'display_name' => 'Obtener roles de sucursal',
                'description' => '',
            ],
            [
                'name' => 'branch-role-get-detail',
                'display_name' => 'Obtener detalle del rol de sucursal',
                'description' => '',
            ],
            [
                'name' => 'branch-role-create',
                'display_name' => 'Crear rol de sucursal',
                'description' => '',
            ],
            [
                'name' => 'branch-role-delete',
                'display_name' => 'Eliminar rol de sucursal',
                'description' => '',
            ],
            [
                'name' => 'branch-role-edit',
                'display_name' => 'Editar rol de sucursal',
                'description' => '',
            ],

            //Roles to users
            [
                'name' => 'user-role-assign',
                'display_name' => 'Asignar rol a usuarios',
                'description' => '',
            ],
        ];

        foreach ($data as $movementType)
        {
            Permission::create($movementType);
        }
    }
}
