<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 6/12/17
 * Time: 12:08 PM
 */

namespace Ventamatic\Core\System;


use Ventamatic\Core\Branch\Inventory;
use Ventamatic\Core\Branch\InventoryMovement;
use Ventamatic\Core\Branch\InventoryMovementType;

class MigrationUtils
{
    public function updateLastCostInInventory()
    {
        Inventory::chunk(100, function($inventories){
            foreach ($inventories as $inventory) {
                $inventoryMovement = InventoryMovement::query()
                    ->whereProductId($inventory->product_id)
                    ->whereBranchId($inventory->branch_id)
                    ->whereIn('inventory_movement_type_id', [
                        InventoryMovementType::COMPRA,
                        InventoryMovementType::CARGA_MASIVA,
                        InventoryMovementType::CONSIGNACION,
                    ])
                    ->orderBy('created_at','desc')
                    ->first();

                if ($inventoryMovement && $inventoryMovement->value) {
                    $inventory->last_cost = $inventoryMovement->value;
                    $inventory->save();
                }
            }
        });

    }
}