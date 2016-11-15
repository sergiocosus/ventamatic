<?php namespace Ventamatic\Core\External;

use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Ventamatic\Core\Branch\Buy;
use Ventamatic\Core\Product\Brand;
use Ventamatic\Core\System\RevisionableBaseModel;

class Supplier extends RevisionableBaseModel {

    use SoftDeletes;
    use SearchableTrait;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    protected $fillable = ['id', 'name', 'last_name', 'last_name_2', 'email', 
        'phone', 'cellphone', 'address', 'rfc', 'supplier_category_id'];

    protected $casts = [
        'id' => 'integer',
        'supplier_category_id' => 'integer'
    ];

    protected $searchable = [
        'columns' => [
            'name' => 10,
            'last_name' => 10,
            'last_name_2' => 10,
        ]
    ];

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
