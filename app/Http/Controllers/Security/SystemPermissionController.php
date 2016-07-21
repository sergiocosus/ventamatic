<?php namespace Ventamatic\Http\Controllers\Security;


use Ventamatic\Core\User\Security\Permission;
use Ventamatic\Http\Controllers\Controller;

class SystemPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function get()
    {
        return Permission::all();
    }

    public function getPermission(Permission $permission)
    {
        return $permission;
    }
}