<?php namespace Ventamatic\Http\Controllers\Security;



use Ventamatic\Core\User\Security\BranchPermission;
use Ventamatic\Http\Controllers\Controller;

class BranchPermissionController extends Controller
{
    public function get(BranchPermission $branchPermission = null)
    {
        if($branchPermission)
        {
            return $branchPermission;
        }

        return BranchPermission::all();
    }

}