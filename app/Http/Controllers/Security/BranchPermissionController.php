<?php namespace Ventamatic\Http\Controllers\Security;



use Ventamatic\Core\User\Security\BranchPermission;
use Ventamatic\Http\Controllers\Controller;

class BranchPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function get()
    {
        return BranchPermission::all();
    }

    public function getBrand(BranchPermission $branchPermission)
    {
        return $branchPermission;
    }

}