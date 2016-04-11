<?php namespace Ventamatic\Core\User\Security;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission {

    protected $fillable = ['id', 'name', 'display_name', 'description'];


    public function roles() {
        return $this->belongsToMany(Role::class);
    }
    

}
