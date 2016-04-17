<?php namespace Ventamatic\Http\Controllers\Security;



use Illuminate\Http\Request;
use Ventamatic\Core\User\Security\Permission;
use Ventamatic\Core\User\Security\Role;
use Ventamatic\Http\Controllers\Controller;

class SystemRoleController extends Controller
{
    public function get()
    {
        return Role::all();
    }

    public function getRole(Role $role)
    {
        return $role;
    }

    public function post(Request $request)
    {
        /* TODO Fill this method*/
    }

    public function delete(Request $request, Role $role)
    {
        /* TODO Fill this method*/
    }

    public function put(Request $request, Role $role)
    {
        /* TODO Fill this method*/
    }

    public function putPermission(Role $role, Permission $permission)
    {
        /* TODO Fill this method*/
    }

    public function deletePermission(Role $role, Permission $permission)
    {
        /* TODO Fill this method*/
    }
}