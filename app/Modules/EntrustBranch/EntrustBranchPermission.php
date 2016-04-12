<?php namespace Ventamatic\Core\Modules\EntrustBranch;


use Config;
use Zizaco\Entrust\EntrustPermission;

abstract class EntrustBranchPermission extends EntrustPermission
{
    use EntrustBranchPermissionTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('entrust-branch.permissions_table');
    }
}