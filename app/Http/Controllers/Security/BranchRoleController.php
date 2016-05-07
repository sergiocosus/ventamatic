<?php namespace Ventamatic\Http\Controllers\Security;


use Ventamatic\Core\User\Security\BranchPermission;
use Ventamatic\Core\User\Security\BranchRole;
use Ventamatic\Http\Controllers\Controller;

class BranchRoleController extends Controller
{
    public function get()
    {
        return BranchRole::all();
    }

    public function getBranchRole(BranchRole $branchRole)
    {
        return $branchRole;
    }

    public function post(Request $request)
    {
        $branchRole = BranchRole::create($request->all());
        return compact('branchRole');
    }

    public function delete(Request $request, BranchRole $branchRole)
    {
        if($branchRole->delete()){
            return ['success'=>true];
        }else{
            \Response::json(['success'=>false], 500);
        }
    }

    public function put(Request $request, BranchRole $branchRole)
    {
        $branchRole->fill($request->all());
        $branchRole->update();
        return compact('branchRole');
    }

    public function putPermission(BranchRole $branchRole,
                                  BranchPermission $branchPermission)
    {
        /* TODO Fill this method*/
    }

    public function deletePermission(BranchRole $branchRole,
                                     BranchPermission $branchPermission)
    {
        /* TODO Fill this method*/
    }
}