<?php namespace Ventamatic\Core\Branch;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ventamatic\Core\Product\Product;
use Ventamatic\Core\System\RevisionableBaseModel;
use Ventamatic\Core\User\User;

class InventoryMovement extends RevisionableBaseModel {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;
    
    protected $fillable = ['id', 'user_id', 'branch_id', 'product_id', 
        'inventory_movement_type_id', 'batch', 'quantity', 'cost'];


    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function inventoryMovementType() {
        return $this->belongsTo(InventoryMovementType::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }


}
