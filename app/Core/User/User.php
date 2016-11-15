<?php namespace Ventamatic\Core\User;

use Nicolaslopezj\Searchable\SearchableTrait;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Core\Branch\Buy;
use Ventamatic\Core\Branch\InventoryMovement;
use Ventamatic\Core\Branch\Sale;
use Ventamatic\Core\System\BaseUser;

use Ventamatic\Core\User\EntrustForBranch\EntrustBranchRoleTrait;
use Ventamatic\Core\User\Security\BranchPermission;
use Ventamatic\Core\User\Security\Permission;
use Ventamatic\Exceptions\PermissionException;
use Ventamatic\Modules\EntrustBranch\EntrustBranchUserTrait;
use Zizaco\Entrust\Traits\EntrustUserTrait;




class User extends BaseUser
{
    use EntrustUserTrait;
    use EntrustBranchUserTrait;
    use SearchableTrait;

    protected $searchable = [
        'columns' => [
            'name' => 10,
            'last_name' => 10,
            'last_name_2' => 10,
        ]
    ];

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

    public function dieIfProtecte() {
        if($this->protected) {
            throw new PermissionException('Protected admin user');
        }
    }

    public function getPermissionsAttribute(){
        return Permission::whereHas('roles',function($q){
           $q->whereHas('users', function($q){
               $q->whereId($this->id);
           });
        })->get();
    }


    public function getBranchesAttribute(){
        return Branch::with(['branchRoles' => function($q) {
            $q->where('branch_role_user.user_id', $this->id)
                ->with('branchPermissions');
        }])->whereHas('branchRoles', function($query){
            $query->where('branch_role_user.user_id', $this->id);
        })->get();
    }

    public function getBranchesWithPermission($name){
        return Branch::whereHas('branchRoles', function($query) use ($name) {
            $query->where('branch_role_user.user_id', $this->id)
                ->whereHas('branchPermissions', function ($query) use ($name) {
                    $query->whereName($name);
                });
        })->get();
    }

}
