<?php namespace Ventamatic\Core\Branch;

use Nicolaslopezj\Searchable\SearchableTrait;
use Ventamatic\Core\Product\Product;
use Ventamatic\Core\System\RevisionableBaseModel;

class Inventory extends RevisionableBaseModel {

    use SearchableTrait;

    protected $fillable = ['branch_id', 'product_id', 'quantity', 
        'price', 'minimum'];

    protected $casts = [
        'id' => 'integer',
        'branch_id' => 'integer',
        'product_id' => 'integer',
        'quantity' => 'double',
        'price' => 'double',
        'minimum' => 'double',
    ];
    
    protected $searchable = [
        'columns' => [
            'products.description' => 10,
            'products.bar_code' => 5,
            'brands.name' => 5,
            'categories.name' => 5,
        ],
        'joins' => [
            'products' => ['products.id','inventories.product_id'],
            'brands' => ['products.brand_id','brands.id'],
            'category_product' => ['category_product.product_id','products.id'],
            'categories' => ['categories.id','category_product.category_id']
        ],
    ];

    protected $appends = [
        'current_price',
        'current_minimum',
        'current_total_cost',
        'last_cost'
    ];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function scopeNotDeletedProduct($query)
    {
        return $query->whereHas('product', function($query){
            $query->whereNull('deleted_at');
        });
    }

    public function scopeWhereProductBarCode($query, $barCode)
    {
        return $query->whereHas('product', function($query) use ($barCode){
            $query->whereBarCode($barCode);
        });
    }

    public function scopeHistoricFrom($query, $date) {
        $query->select(
            'inventories.*',
            \DB::raw('(inventories.quantity - ifnull(inventory_movements_total.total_quantity,0)) as quantity')
        )
            ->leftJoin(\DB::raw('
               (select inventory_movements.*,
                 sum(inventory_movements.quantity) as total_quantity from inventory_movements
                where inventory_movements.created_at >= ?
                GROUP BY inventory_movements.product_id, inventory_movements.branch_id
             )  as inventory_movements_total
        
        '), function($join){
            $join->on('inventories.product_id', '=', 'inventory_movements_total.product_id');
            $join->on('inventories.branch_id', '=', 'inventory_movements_total.branch_id');
        })
        ->addBinding($date)
        ->groupBy('inventories.product_id', 'inventories.branch_id');
    }


    public function getCurrentPriceAttribute()
    {
        if ($this->price) {
            return $this->price;
        } else {
            return $this->product->global_price;
        }
    }

    public function getCurrentMinimumAttribute()
    {
        if ($this->minimum) {
            return $this->minimum;
        } else {
            return $this->product->global_minimum;
        }
    }

    public function getCurrentTotalCostAttribute() {
        $inventoryMovements = (InventoryMovement::query()
            ->whereProductId($this->product_id)
            ->whereBranchId($this->branch_id)
            ->whereIn('inventory_movement_type_id', [
                InventoryMovementType::CONSIGNACION,
                InventoryMovementType::COMPRA,
                InventoryMovementType::PROMOCION,
                InventoryMovementType::CARGA_MASIVA,
                ])
            ->orderBy('created_at','desc')
            ->get());

        $cost = 0;
        $quantity = $this->quantity;
        foreach ($inventoryMovements as $inventoryMovement) {
            if ($quantity >= $inventoryMovement->quantity) {
                $cost += $inventoryMovement->quantity * $inventoryMovement->value;
                $quantity -= $inventoryMovement->quantity;
            } else {
                $cost += $quantity * $inventoryMovement->value;
                $quantity = 0;
            }

            if ($quantity <= 0) {
                break;
            }
        }

        return $cost;
    }

    public function getLastCostAttribute() {
        $inventoryMovement = (InventoryMovement::query()
            ->whereProductId($this->product_id)
            ->whereBranchId($this->branch_id)
            ->whereIn('inventory_movement_type_id', [
                InventoryMovementType::COMPRA,
                InventoryMovementType::CARGA_MASIVA,
                ])
            ->orderBy('created_at','desc')
            ->first());

        return $inventoryMovement ? $inventoryMovement->value : null;
    }

}
