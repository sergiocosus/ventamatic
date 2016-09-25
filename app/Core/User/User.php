<?php namespace Ventamatic\Core\User;

use Ventamatic\Core\Branch\Buy;
use Ventamatic\Core\Branch\InventoryMovement;
use Ventamatic\Core\Branch\Sale;
use Ventamatic\Core\System\BaseUser;

use Ventamatic\Core\User\EntrustForBranch\EntrustBranchRoleTrait;
use Ventamatic\Exceptions\PermissionException;
use Ventamatic\Modules\EntrustBranch\EntrustBranchUserTrait;
use Zizaco\Entrust\Traits\EntrustUserTrait;




class User extends BaseUser 
{
    use EntrustUserTrait;
    use EntrustBranchUserTrait;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','username', 'email', 'password', 'last_name', 'last_name_2', 'phone',
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

    protected $casts = [
        'id' => 'integer',
    ];

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

    public function getScheduleInInitialStatus()
    {
        return $this->schedules()
            ->whereScheduleStatusId(ScheduleStatus::INITIAL)
            ->first();
    }

    public function dieIfAdmin() {
        if($this->hasRole('admin')) {
            throw new PermissionException('Protected admin user');
        }
    }

}
