<?php namespace Ventamatic\Core\Report;
use Carbon\Carbon;
use Ventamatic\Core\Branch\Buy;
use Ventamatic\Core\Branch\InventoryMovement;
use Ventamatic\Core\Branch\Sale;

/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 23/10/16
 * Time: 05:30 PM
 */



class ReportService
{

    public function getSale(Array $request){
        $query = Sale::query()
            ->with('products', 'client', 'user','branch');

        $this->processSimpleFields($query, $request, [
            'id',
            'user_id',
            'branch_id',
            'client_id',
        ]);

        $this->processDateRange($query, $request);

        return $query;
    }

    public function getBuy(Array $request){
        $query = Buy::query()
            ->with('products', 'supplier', 'user', 'branch');

        $this->processSimpleFields($query, $request, [
            'id',
            'user_id',
            'branch_id',
            'provider_id',
        ]);

        $this->processDateRange($query, $request);

        return $query;
    }

    public function getInventoryMovements(Array $request){
        $query = InventoryMovement::with('user', 'product', 'branch', 'inventoryMovementType');

        $this->processSimpleFields($query, $request, [
            'product_id',
            'user_id',
            'inventory_movement_type_id',
            'branch_id',
        ]);

        $this->processDateRange($query, $request);

        return $query;
    }

    public function processSimpleFields($query, $request, $fields){
        foreach ($fields as $field) {
            if($data = array_get($request, $field)) {
                $query->where($field, $data);
            }
        }
    }


    private function processDateRange($query, $request, $field = 'created_at')
    {
        if ($begin_at = array_get($request, 'begin_at')) {
            $query->where($field, '>=', $this->validateBeginAt($begin_at));
        }

        if ($end_at = array_get($request, 'end_at')) {
            $query->where($field, '<=', $this->validateEndAt($end_at));
        }
    }

    private function validateBeginAt($begin_at) {
        if (strlen($begin_at) <= 10) {
            $begin_at .= ' 00:00:00';
        }
        $begin_at = new Carbon($begin_at,'America/Mexico_City');
        $begin_at->setTimezone('UTC');

        return $begin_at;
    }

    private function validateEndAt($end_at) {
        if (strlen($end_at) <= 10) {
            $end_at .= ' 23:59:59';
        }

        $end_at = new Carbon($end_at,'America/Mexico_City');
        $end_at->setTimezone('UTC');

        return $end_at;
    }
}