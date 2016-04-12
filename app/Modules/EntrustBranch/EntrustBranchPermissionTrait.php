<?php namespace Ventamatic\Core\Modules\EntrustBranch;

use Config;

trait EntrustBranchPermissionTrait
{
    /**
     * Many-to-Many relations with role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function branchRoles()
    {
        return $this->belongsToMany(Config::get('entrust-branch.role'), 
            Config::get('entrust.permission_role_table'));
    }

    /**
     * Boot the permission model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the permission model uses soft deletes.
     *
     * @return void|bool
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function($permission) {
            if (!method_exists(Config::get('entrust-branch.permission'), 'bootSoftDeletes')) {
                $permission->branchRoles()->sync([]);
            }

            return true;
        });
    }
}