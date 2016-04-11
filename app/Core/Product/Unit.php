<?php namespace Ventamatic\Core\Product;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ventamatic\Core\System\RevisionableBaseModel;

class Unit extends RevisionableBaseModel {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    protected $fillable = ['id', 'name', 'abbreviation'];


    public function products() {
        return $this->hasMany(Product::class);
    }

}
