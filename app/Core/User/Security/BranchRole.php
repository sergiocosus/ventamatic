<?php namespace Ventamatic\Core\User\Security;



use Ventamatic\Core\Modules\EntrustBranch\EntrustBranchRole;

class BranchRole extends EntrustBranchRole {

    protected $fillable = ['id', 'name', 'display_name', 'description'];


    public function branchPermissions() {
        return $this->belongsToMany(BranchPermission::class);
    }

}
