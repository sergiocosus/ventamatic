<?php namespace Ventamatic\Core\Branch;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ventamatic\Core\Product\Product;
use Ventamatic\Core\System\RevisionableBaseModel;
use Ventamatic\Core\User\Schedule;
use Ventamatic\Exceptions\InventoryException;

class Branch extends RevisionableBaseModel {
    
    use SoftDeletes;
    
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
    
    public function alterInventory(Product $product, $data)
    {
        /** @var Inventory $inventory */
        $inventory = $this->inventories()->whereProductId($product->id)->first();
        if(!$inventory)
        {
            $inventory = new Inventory();
            $inventory->branch()->associate($this);
            $inventory->product()->associate($product);
            $inventory->quantity = 0;
        }
        $inventory->quantity += $data->json('quantity');
        return $inventory->save();
    }
    
    public function addInventoryMovement()
    {
        
    }

}
