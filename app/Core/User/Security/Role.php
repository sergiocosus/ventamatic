<?php namespace Ventamatic\Core\User\Security;

use Ventamatic\Core\User\User;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole {

    protected $fillable = ['id', 'name', 'display_name', 'description'];

    protected $casts = [
        'id' => 'integer',
    ];

    public function permissions() {
        return $this->belongsToMany(Permission::class);
    }

    public function users() {
        return $this->belongsToMany(User::class);
    }
    

}
