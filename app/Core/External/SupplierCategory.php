<?php namespace Ventamatic\Core\External;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ventamatic\Core\System\RevisionableBaseModel;

class SupplierCategory extends RevisionableBaseModel {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    protected $fillable = ['id', 'name'];

    protected $casts = [
        'id' => 'integer',
    ];

    public function suppliers() {
        return $this->hasMany(Supplier::class);
    }


}
