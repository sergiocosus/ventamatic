<?php namespace Ventamatic\Modules\EntrustBranch;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\Contracts\EntrustRoleInterface;

abstract class EntrustBranchRole extends Model implements EntrustRoleInterface
{
    use EntrustBranchRoleTrait;
}