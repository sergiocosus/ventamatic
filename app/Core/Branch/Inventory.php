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


}
