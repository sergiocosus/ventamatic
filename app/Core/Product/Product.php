<?php namespace Ventamatic\Core\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Core\Branch\Buy;
use Ventamatic\Core\Branch\BuyProductPivot;
use Ventamatic\Core\Branch\Inventory;
use Ventamatic\Core\Branch\InventoryMovement;
use Ventamatic\Core\Branch\ProductSalePivot;
use Ventamatic\Core\Branch\Sale;
use Ventamatic\Core\System\RevisionableBaseModel;

class Product extends RevisionableBaseModel {

    use SoftDeletes;
    use SearchableTrait;


    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    protected $fillable = ['bar_code', 'description', 'global_minimum', 
        'global_price', 'unit_id', 'brand_id'];

    protected $searchable = [
        'columns' => [
            'products.description' => 10,
            'products.bar_code' => 5,
            'brands.name' => 5,
            'categories.name' => 5,
        ],
        'joins' => [
            'brands' => ['products.brand_id','brands.id'],
            'category_product' => ['category_product.product_id','products.id'],
            'categories' => ['categories.id','category_product.category_id']
        ],
    ];

    protected $casts = [
        'id' => 'integer',
        'unit_id' => 'integer',
        'brand_id' => 'integer',
        'global_minimum' => 'double',
        'global_price' => 'double',
    ];

    public static $basicRelationsToLoad = [
        'categories', 'unit', 'brand'
    ];

    public static function boot()
    {
        parent::boot();

        self::created(function(Product $product) {
            $product->initializeInventory();
        });
    }

    public function initializeInventory() {
        $branches = Branch::get();
        foreach ($branches as $branch) {
            $branch->alterInventory($this, 0);
        }
    }


    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function unit() {
        return $this->belongsTo(Unit::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function branches() {
        return $this->belongsToMany(Branch::class);
    }

    public function buys() {
        return $this->belongsToMany(Buy::class);
    }

    public function sales() {
        return $this->belongsToMany(Sale::class);
    }
    
    public function inventories() {
        return $this->hasMany(Inventory::class);
    }

    public function inventory(Branch $branch)
    {
        return $this->inventories()->whereBranchId($branch->id);
    }

    public function inventoryMovements() {
        return $this->hasMany(InventoryMovement::class);
    }


    public function scopeWithRelations($q)
    {
        $q->with(self::$basicRelationsToLoad);
    }

    public function newPivot(Model $parent, array $attributes, $table, $exists, $using = NULL)
    {
        if ($parent instanceof Sale) {
            return new ProductSalePivot($parent, $attributes, $table, $exists);
        }

        if ($parent instanceof Buy) {
            $pivot = new BuyProductPivot($parent, $attributes, $table, $exists);
            $pivot->load('inventoryMovementType');
            return $pivot;
        }

        return parent::newPivot($parent, $attributes, $table, $exists);
    }


    public function getCorrectPrice()
    {
        /* TODO change logic for price*/
        return $this->global_price;
    }

    
    public function scopeHasInBranch($query, Branch $branch){
        return $query->whereHas('inventories',function($query) use ($branch){
            $query->where('branch_id', $branch->id);
        })->with(['inventories' => function($query) use ($branch){
            $query->where('branch_id', $branch->id);
        }]);
    }
}
