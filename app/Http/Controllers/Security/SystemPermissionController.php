<?php namespace Ventamatic\Http\Controllers\Security;


use Ventamatic\Core\User\Security\Permission;
use Ventamatic\Http\Controllers\Controller;

class SystemPermissionController extends Controller
{
    public function get(Permission $permission = null)
    {
        if($permission)
        {
            return $permission;
        }

        return Permission::all();
    }
}