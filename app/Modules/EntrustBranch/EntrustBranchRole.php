<?php namespace Ventamatic\Core\Modules\EntrustBranch;

use Illuminate\Database\Eloquent\Model;
use Ventamatic\Core\User\EntrustForBranch\EntrustBranchRoleTrait;
use Zizaco\Entrust\Contracts\EntrustRoleInterface;

abstract class EntrustBranchRole extends Model implements EntrustRoleInterface
{
    use EntrustBranchRoleTrait;
}