<?php namespace Ventamatic\Core\Branch;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ventamatic\Core\Product\Product;
use Ventamatic\Core\System\RevisionableBaseModel;
use Ventamatic\Core\Branch\InventoryMovement;
use Ventamatic\Core\Branch\InventoryMovementType;
use Ventamatic\Core\User\Schedule;
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

    public function lessInventory(Product $product, $data)
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

        $inventory_movement_type=InventoryMovementType::CADUCADO;

        $this->addInventoryMovement(Auth::user(),$product,$data,$inventory_movement_type);

        $inventory->quantity -= $data->json('quantity');
        return $inventory->save();
    }

    public function addInventory(Array $products)
    {

        foreach ($products as $productData)
        {

            /** @var Product $product */
            $product_id = $productData['product_id'];
            $quantity = $productData['quantity'];
            /** @var Inventory $inventory */
            $inventory = $this->inventories()->whereProductId($product_id)->first();
            $inventory->quantity += $quantity;
            $inventory->save();


        }
    }

    public function buyInventory(Product $product, $data)
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

    public function alterInventory(Product $product, $data, $inventory_movement)
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
            //    $this->addInventoryMovement(Auth::user(),$product,$data,$inventory_movement_type);

        if($operator == SUMA_INVENTARIO){
        $inventory->quantity += $data->json('quantity');
        }
        if($operator == RESTA_INVENTARIO){
        $inventory->quantity -= $data->json('quantity');
        }
        return $inventory->save();
    }
    
    public function addInventoryMovement($user,$product,$data,$inventory_movement_type)
    {
        $inventory_movement= new InventoryMovement();

        $inventory_last= InventoryMovement::orderBy('id','desc')->first(['batch']);
        $inventory_movement->user()->associate($user);
        $inventory_movement->branch()->associate($this);
        $inventory_movement->product()->associate($product);

        switch($inventory_movement_type){

            case InventoryMovementType::PROMOCION:
                $inventory_type=InventoryMovementType::findOrFail(InventoryMovementType::PROMOCION);
                $this->alterInventory($product,$data, SUMA_INVENTARIO);
                $this->alterInventory($product,$data, RESTA_INVENTARIO);
            break;

            case InventoryMovementType::TRASLADO:
                $inventory_type=InventoryMovementType::findOrFail(InventoryMovementType::TRASLADO);
                $this->alterInventory($product,$data, RESTA_INVENTARIO);
                $this->alterInventory($product,$data, SUMA_INVENTARIO);
            break;

            case InventoryMovementType::CONVERSION:
                $inventory_type=InventoryMovementType::findOrFail(InventoryMovementType::CONVERSION);
            break;

            case InventoryMovementType::CONCESION:
                $inventory_type=InventoryMovementType::findOrFail(InventoryMovementType::CONCESION);
            break;

            case InventoryMovementType::CADUCADO:
                $inventory_type=InventoryMovementType::findOrFail(InventoryMovementType::CADUCADO);
                $this->alterInventory($product, $data, RESTA_INVENTARIO);
            break;

            case InventoryMovementType::AJUSTE:
                $inventory_type = InventoryMovementType::findorFail(InventoryMovementType::AJUSTE);
                $this->alterInventory($product, $data, RESTA_INVENTARIO);
            break;
        }

        $inventory_movement->inventoryMovementType()->associate($inventory_type);
        $inventory_movement->batch =  $inventory_last + 1;
        $inventory_movement->quantity = $data->json('quantity');
        $inventory_movement->cost = $product->cost;
        $inventory_movement->save();
    }

}
