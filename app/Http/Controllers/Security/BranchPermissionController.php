<?php namespace Ventamatic\Http\Controllers\Security;



use Ventamatic\Core\User\Security\BranchPermission;
use Ventamatic\Http\Controllers\Controller;

class BranchPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getAll()
    {
        $branch_permissions = BranchPermission::all();

        return $this->success(compact('branch_permissions'));
    }

    public function get(BranchPermission $branch_permission)
    {
        return $this->success(compact('branch_permission'));
    }

}