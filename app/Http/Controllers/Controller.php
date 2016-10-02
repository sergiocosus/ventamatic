<?php

namespace Ventamatic\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Exceptions\BranchPermissionException;
use Ventamatic\Exceptions\PermissionException;
use Ventamatic\Http\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function success($data = null)
    {
        return Response::success($data);
    }

    protected function fail($data = null)
    {
        return Response::fail($data);
    }

    protected function error($code = 500, $message = "",  $data = null)
    {
        return Response::error($code, $message, $data);
    }

    protected function can($permission_name)
    {
        PermissionException::check($permission_name);
    }

    protected function canOnBranch($permission_name, Branch $branch)
    {
        BranchPermissionException::check($permission_name, $branch);
    }
}
