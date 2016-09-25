<?php namespace Ventamatic\Core\User\Security;



use Ventamatic\Exceptions\PermissionException;
use Ventamatic\Modules\EntrustBranch\EntrustBranchRole;

class BranchRole extends EntrustBranchRole {

    protected $fillable = ['id', 'name', 'display_name', 'description'];


    public function branchPermissions() {
        return $this->belongsToMany(BranchPermission::class,'branch_permission_role');
    }

    public function isProtected(){
        if($this->protected) {
            throw new PermissionException('Protected role');
        }
    }
}
