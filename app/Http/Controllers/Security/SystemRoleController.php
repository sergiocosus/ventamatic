<?php namespace Ventamatic\Http\Controllers\Security;



use Illuminate\Http\Request;
use Ventamatic\Core\User\Security\Permission;
use Ventamatic\Core\User\Security\Role;
use Ventamatic\Http\Controllers\Controller;

class SystemRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function get()
    {
        $this->can('role-get');

        $roles = Role::all();

        return $this->success(compact('roles'));
    }

    public function getRole(Role $role)
    {
        $this->can('role-get-detail');

        $role->load('permissions');

        return $this->success(compact('role'));
    }

    public function post(Request $request)
    {
        $this->can('role-create');

        $role = Role::create($request->all());

        $role->permissions()
            ->sync($request->get('permissions',[]));
        $role->load('permissions');

        return $this->success(compact('role'));
    }

    public function delete(Request $request, Role $role)
    {
        $this->can('role-delete');

        if($role->delete()){
            return $this->success();
        }else{
           return $this->error();
        }
    }

    public function put(Request $request, Role $role)
    {
        $this->can('role-edit');

        $role->fill($request->all());
        $role->update();

        $role->permissions()
            ->sync($request->get('permissions',[]));
        $role->load('permissions');

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