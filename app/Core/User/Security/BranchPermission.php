<?php namespace Ventamatic\Core\User\Security;

use Ventamatic\Modules\EntrustBranch\EntrustBranchPermission;

class BranchPermission extends EntrustBranchPermission {

    protected $fillable = ['id', 'name', 'display_name', 'description'];


    public function branchRoles() {
        return $this->belongsToMany(BranchRole::class);
    }
    
}
