<?php namespace Ventamatic\Core\Branch;

use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Ventamatic\Core\Product\Product;
use Ventamatic\Core\System\RevisionableBaseModel;
use Ventamatic\Core\Branch\InventoryMovement;
use Ventamatic\Core\Branch\InventoryMovementType;
use Ventamatic\Core\User\Schedule;
use Ventamatic\Core\User\Security\BranchPermission;
use Ventamatic\Core\User\Security\BranchRole;
use Ventamatic\Core\User\User;
use Ventamatic\Exceptions\InventoryException;
use Auth;


class Branch extends RevisionableBaseModel {
    
    use SoftDeletes;
    use SearchableTrait;

    const SUMA_INVENTARIO = 1;
    const RESTA_INVENTARIO= 2;

    protected $dates = ['deleted_at'];
    
    protected $softDelete = true;
    
    protected $fillable = ['name', 'description', 'address', 'title_ticket', 
        'header_ticket', 'footer_ticket', 'image_hash'];

    protected $casts = [
        'id' => 'integer',
    ];

    protected $appends = [
        'image_url',
    ];

    protected $searchable = [
        'columns' => [
            'name' => 10,
        ]
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

    public function roles(){
        return $this->belongsToMany(BranchRole::class);
    }

    public function branchRoles(){
        return $this->belongsToMany(BranchRole::class, 'branch_role_user')
            ->withPivot('user_id');
    }

    public function alterInventory(Product $product, $quantity)
    {
        if (!$product->unit->validateQuantity($quantity)) {
            throw new InventoryException(\Lang::get('unit.invalid_quantity_for_unit',
                [
                    'quantity' => $quantity,
                    'unit_id' => $product->unit->id,
                    'unit_name' => $product->unit->name,
                ]
            ));
        }

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
        $inventory->save();

        return $inventory;
    }

    public function addInventoryMovement(User $user, Product $product, $data, $batch = null)
    {
        /** @var InventoryMovementType $inventoryMovementType */
        $inventoryMovementType = InventoryMovementType::findOrFail(
            $data['inventory_movement_type_id']
        );

        $quantity = $data['quantity'];

        if (!isset($batch)) {
            $batch = InventoryMovement::getLastBatch();
        }

        $movements = [];

        $value = array_get($data, 'value', 0);

        switch($inventoryMovementType->id){
            case InventoryMovementType::COMPRA:
            case InventoryMovementType::PROMOCION:
            case InventoryMovementType::CONSIGNACION:
                $movements[] = InventoryMovement::createMovement($user, $this, $product,
                    $inventoryMovementType, $quantity, $batch, array_get($data, 'model'), $value);
            break;

            case InventoryMovementType::TRASLADO:
                $movements[] = InventoryMovement::createMovement($user, $this, $product,
                    $inventoryMovementType, -$quantity, $batch);
                $movements[] = InventoryMovement::createMovement($user,
                    Branch::find($data['destiny_branch_id']), $product,
                    $inventoryMovementType, $quantity, $batch);
            break;
            case InventoryMovementType::CONVERSION:
                $movements[] = InventoryMovement::createMovement($user, $this, $product,
                    $inventoryMovementType, -$quantity, $batch);
                $movements[] = InventoryMovement::createMovement($user, $this,
                    Product::find($data['product_converted_id']),
                    $inventoryMovementType, $data['quantity_converted'], $batch);
                break;
            case InventoryMovementType::CONCESION:
            case InventoryMovementType::CADUCADO:
            case InventoryMovementType::VENTA:
                $movements[] = InventoryMovement::createMovement($user, $this, $product,
                $inventoryMovementType, -$quantity, $batch, array_get($data, 'model'), $value);
            break;

            case InventoryMovementType::AJUSTE:
            case InventoryMovementType::CARGA_MASIVA:
                $movements[] = InventoryMovement::createMovement($user, $this, $product,
                    $inventoryMovementType, $quantity, $batch,null,  $value);
            break;
            case InventoryMovementType::SALE_CANCELED:
                $movements[] = InventoryMovement::createMovement($user, $this, $product,
                    $inventoryMovementType, $quantity, $batch, array_get($data, 'model'), $value);
                break;
        }

        return $movements;
    }

    public function getImageUrlAttribute()
    {
        if ($this->image_hash) {
            return env('APP_URL').'/storage/images/branch/'.$this->image_hash;
        }

        return null;
    }
}
