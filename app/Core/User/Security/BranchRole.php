<?php namespace Ventamatic\Core\User\Security;

use Illuminate\Database\Eloquent\Model;

class BranchRole extends Model {

    /* TODO Make Entrust for Branch and apply to this class */
    protected $fillable = ['id', 'name', 'display_name', 'description'];


    public function branchPermissions() {
        return $this->belongsToMany(BranchPermission::class);
    }

}
