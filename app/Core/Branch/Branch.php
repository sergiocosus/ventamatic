<?php namespace Ventamatic\Core\Branch;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ventamatic\Core\Product\Product;
use Ventamatic\Core\System\RevisionableBaseModel;
use Ventamatic\Core\Branch\InventoryMovement;
use Ventamatic\Core\Branch\InventoryMovementType;
use Ventamatic\Core\User\Schedule;
use Ventamatic\Core\User\User;
use Ventamatic\Exceptions\InventoryException;
use Auth;


class Branch extends RevisionableBaseModel {
    
    use SoftDeletes;

    const SUMA_INVENTARIO = 1;
    const RESTA_INVENTARIO= 2;

    protected $dates = ['deleted_at'];
    
    protected $softDelete = true;
    
    protected $fillable = ['name', 'description', 'address', 'title_ticket', 
        'header_ticket', 'footer_ticket', 'image_hash'];

    protected $casts = [
        'id' => 'integer',
    ];

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

    
    public function reductInventory(Array $products)
    {
        foreach ($products as $productData)
        {
            /** @var Product $product */
            $product_id = $productData['product_id'];
            $quantity = $productData['quantity'];
            
            /** @var Inventory $inventory */
            $inventory = $this->inventories()->whereProductId($product_id)->first();
            if($inventory->quantity >= $quantity)
            {
                $inventory->quantity -= $quantity;
                $inventory->save();
            }
            else
            {
                throw new InventoryException("Cantidad insuficiente en el inventario");
            }
        }
    }

    public function alterInventory(Product $product, $quantity)
    {
        /** @var Inventory $inventory */
        $inventory = $this->inventories()->whereProductId($product->id)->first();
        if (!$inventory) {
            $inventory = new Inventory();
            $inventory->branch()->associate($this);
            $inventory->product()->associate($product);
            $inventory->quantity = 0;
        }

        if ($inventory->quantity + $quantity < 0) {
            throw new InventoryException(\Lang::get('inventory.insufficient_inventory',
                [
                    'product' => $product->description,
                    'requested' => $quantity,
                    'existent' => $inventory->quantity
                ]
            ));
        }

        $inventory->quantity += $quantity;



        return $inventory->save();
    }

    public function addInventoryMovement(User $user, Product $product, $data)
    {
        /** @var InventoryMovementType $inventoryMovementType */
        $inventoryMovementType = InventoryMovementType::findOrFail(
            $data['inventory_movement_type_id']
        );

        $quantity = $data['quantity'];

        $batch = 1;
        $lastInventoryMovementBatch = InventoryMovement::orderBy('id','desc')->first(['batch']);
        if($lastInventoryMovementBatch) {
            $batch += $lastInventoryMovementBatch->batch;
        }

        switch($inventoryMovementType->id){
            case InventoryMovementType::COMPRA:
            case InventoryMovementType::PROMOCION:
                InventoryMovement::createMovement($user, $this, $product,
                    $inventoryMovementType, $quantity, $batch);
            break;

            case InventoryMovementType::TRASLADO:
                InventoryMovement::createMovement($user, $this, $product,
                    $inventoryMovementType, -$quantity, $batch);
                InventoryMovement::createMovement($user,
                    Branch::find($data['destiny_branch_id']), $product,
                    $inventoryMovementType, $quantity, $batch);
            break;

            //case InventoryMovementType::CONVERSION:
            case InventoryMovementType::CONCESION:
            case InventoryMovementType::CADUCADO:
            case InventoryMovementType::VENTA:
                InventoryMovement::createMovement($user, $this, $product,
                    $inventoryMovementType, -$quantity, $batch);
            break;

            case InventoryMovementType::AJUSTE:
                InventoryMovement::createMovement($user, $this, $product,
                    $inventoryMovementType, $quantity, $batch);
            break;
        }
    }

}
