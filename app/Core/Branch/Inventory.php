<?php namespace Ventamatic\Core\Branch;

use Ventamatic\Core\Product\Product;
use Ventamatic\Core\System\RevisionableBaseModel;

class Inventory extends RevisionableBaseModel {

    protected $fillable = ['branch_id', 'product_id', 'quantity', 
        'price', 'minimum'];


    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }


}
