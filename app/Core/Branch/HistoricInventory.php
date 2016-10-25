<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 24/10/16
 * Time: 08:51 PM
 */

namespace Ventamatic\Core\Branch;


use Ventamatic\Core\System\BaseModel;

class HistoricInventory extends BaseModel
{

    protected $table = '(
select inventories.*,
   (inventories.quantity - ifnull(inventory_movements_total.total_quantity,0)) as quantity

    from inventories
  left JOIN  (
               select inventory_movements.*,
                 sum(inventory_movements.quantity) as total_quantity from inventory_movements
                where inventory_movements.created_at >= ?
                GROUP BY inventory_movements.product_id, inventory_movements.branch_id
             ) as inventory_movements_total
    ON (inventories.product_id = inventory_movements_total.product_id
        and inventories.branch_id = inventory_movements_total.branch_id)

GROUP BY inventories.product_id, inventories.branch_id;
   ) ';
}