<?php namespace Ventamatic\Core\User;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Ventamatic\Core\Branch\Buy;
use Ventamatic\Core\Branch\InventoryMovement;
use Ventamatic\Core\Branch\Sale;
use Ventamatic\Core\User\Security\Role;
use Venturecraft\Revisionable\RevisionableTrait;

class User extends Authenticatable
{
    use RevisionableTrait;
    
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'last_name', 'last_name_2', 'phone',
        'cellphone', 'address', 'rfc'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function roles() {
        return $this->belongsToMany(Role::class);
    }

    public function buys() {
        return $this->hasMany(Buy::class);
    }

    public function inventoryMovements() {
        return $this->hasMany(InventoryMovement::class);
    }
    
    public function sales() {
        return $this->hasMany(Sale::class);
    }

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }

    public function sessions() {
        return $this->hasMany(Session::class);
    }
}
