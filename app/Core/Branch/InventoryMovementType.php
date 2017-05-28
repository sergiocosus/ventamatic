<?php namespace Ventamatic\Core\Branch;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ventamatic\Core\System\RevisionableBaseModel;

class InventoryMovementType extends RevisionableBaseModel {

    const PROMOCION = 1;
    const TRASLADO = 2;
    const CONVERSION = 3;
    const CONCESION = 4;
    const CADUCADO = 5;
    const AJUSTE = 6;
    const COMPRA = 7;
    const VENTA = 8;
    const CONSIGNACION = 9;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    protected $fillable = ['id', 'name'];

    protected $casts = [
        'id' => 'integer',
    ];
    
    public function inventoryMovements() {
        return $this->hasMany(InventoryMovement::class);
    }


}
