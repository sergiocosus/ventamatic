<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 17/04/16
 * Time: 12:07 AM
 */

namespace Ventamatic\Exceptions;


use Ventamatic\Core\User\Security\Permission;

class PermissionException extends \Exception
{
    public static function check($permission_name)
    {
        if(!\Entrust::can($permission_name)) {
            $permission = Permission::whereName($permission_name)->first();
            throw new self($permission->display_name);
        }
    }


}