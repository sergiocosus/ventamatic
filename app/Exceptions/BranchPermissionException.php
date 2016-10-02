<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 17/04/16
 * Time: 12:07 AM
 */

namespace Ventamatic\Exceptions;


use Ventamatic\Core\Branch\Branch;
use Ventamatic\Core\User\Security\BranchPermission;

class BranchPermissionException extends \Exception
{
    public static function check($permission_name, Branch $branch)
    {
        if(!\Auth::user()->canInBranch($permission_name, $branch)) {
            $branchPermission = BranchPermission::whereName($permission_name)->first();
            throw new self($branchPermission->display_name. ' - ' . $branch->name);
        }
    }


}