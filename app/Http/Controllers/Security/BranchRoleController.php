<?php namespace Ventamatic\Http\Controllers\Security;


use Ventamatic\Core\User\Security\BranchPermission;
use Ventamatic\Core\User\Security\BranchRole;
use Ventamatic\Http\Controllers\Controller;

class BranchRoleController extends Controller
{
    public function get(BranchRole $branchRole = null)
    {
        if($branchRole)
        {
            return $branchRole;
        }

        return BranchRole::all();
    }

    public function post(Request $request)
    {
        /* TODO Fill this method*/
    }

    public function delete(Request $request, BranchRole $branchRole)
    {
        /* TODO Fill this method*/
    }

    public function put(Request $request, BranchRole $branchRole)
    {
        /* TODO Fill this method*/
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