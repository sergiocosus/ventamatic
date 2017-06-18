<?php namespace Ventamatic\Core\User\Security;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission {

    protected $fillable = ['id', 'name', 'display_name', 'description'];

    protected $casts = [
        'id' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();

        self::created(function(Permission $permission) {
            $adminRole = Role::whereName('admin')->first();
            if ($adminRole) {
                $adminRole->attachPermission($permission);
            }
        });
    }

    public function roles() {
        return $this->belongsToMany(Role::class);
    }
    

}
