<?php namespace Ventamatic\Core\User\Security;

use Ventamatic\Core\User\EntrustForBranch\EntrustBranchRole;

class BranchRole extends EntrustBranchRole {

    protected $fillable = ['id', 'name', 'display_name', 'description'];


    public function branchPermissions() {
        return $this->belongsToMany(BranchPermission::class);
    }

}
