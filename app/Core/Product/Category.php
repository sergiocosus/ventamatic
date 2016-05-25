<?php namespace Ventamatic\Core\Product;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ventamatic\Core\System\RevisionableBaseModel;

class Category extends RevisionableBaseModel {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    protected $fillable = ['id', 'name'];

    protected $casts = [
        'id' => 'integer',
    ];

    public function products() {
        return $this->belongsToMany(Product::class);
    }
    

}
