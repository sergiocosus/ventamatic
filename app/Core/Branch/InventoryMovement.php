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

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'brand_id' => 'integer',
        'product_id' => 'integer',
        'inventory_movement_type_id' => 'integer',
        'batch' => 'integer',
        'quantity' => 'double',
        'cost' => 'double',
    ];

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


    public function inventoriableMovement()
    {
        return $this->morphTo();
    }

    public static function createMovement(User $user,
                                   Branch $branch,
                                   Product $product,
                                   InventoryMovementType $inventoryMovementType,
                                   $quantity,
                                   $batch,
                                   $model = null){
        $inventoryMovement = new static();

        $inventoryMovement->user()->associate($user);
        $inventoryMovement->branch()->associate($branch);
        $inventoryMovement->product()->associate($product);
        $inventoryMovement->inventoryMovementType()->associate($inventoryMovementType);
        $inventoryMovement->batch =  $batch;
        $inventoryMovement->quantity = $quantity;

        if ($model) {
           $inventoryMovement->inventoriableMovement()->associate($model);
        }

        $branch->alterInventory($product, $quantity);

        $inventoryMovement->save();

        return $inventoryMovement;
    }
}
