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
        $role = Role::create($request->all());
        return $this->success(compact('role'));
    }

    public function delete(Request $request, Role $role)
    {
        if($role->delete()){
            return $this->success();
        }else{
           return $this->error();
        }
    }

    public function put(Request $request, Role $role)
    {
        $role->fill($request->all());
        $role->update();
        return $this->success(compact('role'));
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