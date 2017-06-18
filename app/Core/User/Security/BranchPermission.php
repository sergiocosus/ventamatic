<?php namespace Ventamatic\Core\User\Security;

use Ventamatic\Modules\EntrustBranch\EntrustBranchPermission;

class BranchPermission extends EntrustBranchPermission {
    protected $fillable = ['id', 'name', 'display_name', 'description'];

    public static function boot()
    {
        parent::boot();

        self::created(function(BranchPermission $branchPermission) {
            $adminBranchRole = BranchRole::whereName('admin')->first();
            if ($adminBranchRole) {
                $adminBranchRole->attachPermission($branchPermission);
            }
        });
    }

    public function branchRoles() {
        return $this->belongsToMany(BranchRole::class, 'branch_permission_role');
    }
    
}
