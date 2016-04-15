<?php namespace Ventamatic\Core\External;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ventamatic\Core\Branch\Buy;
use Ventamatic\Core\Product\Brand;
use Ventamatic\Core\System\RevisionableBaseModel;

class Supplier extends RevisionableBaseModel {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    protected $fillable = ['id', 'name', 'last_name', 'last_name_2', 'email', 
        'phone', 'cellphone', 'address', 'rfc', 'supplier_category_id'];


    public function supplierCategory() {
        return $this->belongsTo(SupplierCategory::class);
    }

    public function brands() {
        return $this->belongsToMany(Brand::class);
    }

    public function buys() {
        return $this->hasMany(Buy::class);
    }


}
