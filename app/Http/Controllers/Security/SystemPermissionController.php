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
        $permissions = Permission::all();

        return $this->success(compact('permissions'));
    }

    public function getPermission(Permission $permission)
    {
        return $this->success(compact('permission'));
    }
}