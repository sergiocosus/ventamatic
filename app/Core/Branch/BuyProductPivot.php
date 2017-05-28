<?php namespace Ventamatic\Core\Branch;

/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 27/05/17
 * Time: 02:48 PM
 */



use Illuminate\Database\Eloquent\Relations\Pivot;

class BuyProductPivot extends Pivot
{
    public function inventoryMovementType()
    {
        return $this->belongsTo(InventoryMovementType::class);
    }
}