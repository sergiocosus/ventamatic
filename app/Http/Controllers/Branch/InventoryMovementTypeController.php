<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 29/05/17
 * Time: 03:15 PM
 */

namespace Ventamatic\Http\Controllers\Branch;

use Ventamatic\Core\Branch\InventoryMovementType;
use Ventamatic\Http\Controllers\Controller;

class InventoryMovementTypeController extends Controller
{
    public function getAll()
    {
        $inventory_movement_types = InventoryMovementType::all();

        return $this->success(compact('inventory_movement_types'));
    }
}