<?php namespace Ventamatic\Modules\EntrustBranch;
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 11/04/16
 * Time: 12:22 AM
 */



use Cache;
use Config;
use Zizaco\Entrust\Traits\EntrustRoleTrait;

trait EntrustBranchRoleTrait 
{
    use EntrustRoleTrait;

    //Big block of caching functionality.
    public function cachedPermissions()
    {
        $rolePrimaryKey = $this->primaryKey;
        $cacheKey = 'entrust-branch_permissions_for_role_'.$this->$rolePrimaryKey;
        return Cache::tags(Config::get('entrust-branch.permission_role_table'))->remember($cacheKey, Config::get('cache.ttl'), function () {
            return $this->perms()->get();
        });
    }
    public function save(array $options = [])
    {   //both inserts and updates
        $result = parent::save($options);
        Cache::tags(Config::get('entrust-branch.permission_role_table'))->flush();
        return $result;
    }
    public function delete(array $options = [])
    {   //soft or hard
        $result = parent::delete($options);
        Cache::tags(Config::get('entrust-branch.permission_role_table'))->flush();
        return $result;
    }
    public function restore()
    {   //soft delete undo's
        $result = parent::restore();
        Cache::tags(Config::get('entrust-branch.permission_role_table'))->flush();
        return $result;
    }

    /**
     * Many-to-Many relations with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(Config::get('auth.providers.users.model'), Config::get('entrust-branch.role_user_table'),Config::get('entrust-branch.role_foreign_key'),Config::get('entrust-branch.user_foreign_key'));
        // return $this->belongsToMany(Config::get('auth.model'), Config::get('entrust-branch.role_user_table'));
    }

    /**
     * Many-to-Many relations with the permission model.
     * Named "perms" for backwards compatibility. Also because "perms" is short and sweet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function perms()
    {
        return $this->belongsToMany(Config::get('entrust-branch.permission'), Config::get('entrust-branch.permission_role_table'));
    }

    /**
     * Boot the role model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the role model uses soft deletes.
     *
     * @return void|bool
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function($role) {
            if (!method_exists(Config::get('entrust-branch.role'), 'bootSoftDeletes')) {
                $role->users()->sync([]);
                $role->perms()->sync([]);
            }

            return true;
        });
    }
    
}