<?php namespace Ventamatic\Core\Product;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ventamatic\Core\System\RevisionableBaseModel;

class Unit extends RevisionableBaseModel {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    protected $fillable = ['id', 'name', 'abbreviation'];

    protected $casts = [
        'id' => 'integer',
        'step' => 'float',
    ];
    
    public function products() {
        return $this->hasMany(Product::class);
    }

    public function validateQuantity($quantity)
    {
        $module = ($quantity * 1000) % ($this->step * 1000);

        return $module == 0;
    }

}
