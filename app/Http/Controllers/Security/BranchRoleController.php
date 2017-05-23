<?php namespace Ventamatic\Http\Controllers\Security;


use Illuminate\Http\Request;
use Ventamatic\Core\User\Security\BranchPermission;
use Ventamatic\Core\User\Security\BranchRole;
use Ventamatic\Http\Controllers\Controller;

class BranchRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get()
    {
        $this->can('branch-role-get');

        $branch_roles = BranchRole::all();

        return $this->success(compact('branch_roles'));
    }

    public function getBranchRole(BranchRole $branch_role)
    {
        $this->can('branch-role-get-detail');

        $branch_role->load('branchPermissions');

        return $this->success(compact('branch_role'));
    }

    public function post(Request $request)
    {
        $this->can('branch-role-create');

        $branch_role = BranchRole::create($request->all());

        $branch_role->branchPermissions()
            ->sync($request->get('branch_permissions'));
        return $this->success(compact('branch_role'));
    }

    public function delete(Request $request, BranchRole $branch_role)
    {
        $this->can('branch-role-delete');

        $branch_role->isProtected();

        if($branch_role->delete()){
            return $this->success();
        }else{
           return  $this->error();
        }
    }

    public function put(Request $request, BranchRole $branch_role)
    {
        $this->can('branch-role-edit');

        $branch_role->isProtected();
        $branch_role->fill($request->all());
        $branch_role->update();

        $branch_role->branchPermissions()
            ->sync($request->get('branch_permissions'));

        return $this->success(compact('branch_ole'));
    }

    public function putPermission(BranchRole $branch_role,
                                  BranchPermission $branchPermission)
    {
        /* TODO Fill this method*/
    }

    public function deletePermission(BranchRole $branch_role,
                                     BranchPermission $branchPermission)
    {
        /* TODO Fill this method*/
    }
}