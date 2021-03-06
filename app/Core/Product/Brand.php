<?php namespace Ventamatic\Core\Product;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ventamatic\Core\System\RevisionableBaseModel;
use Ventamatic\Core\External\Supplier;

class Brand extends RevisionableBaseModel {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    protected $fillable = ['name'];

    protected $casts = [
        'id' => 'integer',
    ];
    
    public function suppliers() {
        return $this->belongsToMany(Supplier::class);
    }
    
    public function products() {
        return $this->hasMany(Product::class);
    }


}
