<?php namespace Ventamatic\Core\Branch;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ventamatic\Core\Product\Product;
use Ventamatic\Core\System\RevisionableBaseModel;
use Ventamatic\Core\User\Schedule;

class Branch extends RevisionableBaseModel {
    
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    
    protected $softDelete = true;
    
    protected $fillable = ['name', 'description', 'address', 'title_ticket', 
        'header_ticket', 'footer_ticket', 'image_hash'];


    public function products() {
        return $this->belongsToMany(Product::class);
    }

    public function buys() {
        return $this->hasMany(Buy::class);
    }

    public function inventories() {
        return $this->hasMany(Inventory::class);
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


}