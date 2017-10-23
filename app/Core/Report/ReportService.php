<?php namespace Ventamatic\Core\Report;
use Carbon\Carbon;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Core\Branch\Buy;
use Ventamatic\Core\Branch\Inventory;
use Ventamatic\Core\Branch\InventoryMovement;
use Ventamatic\Core\Branch\Sale;
use Ventamatic\Core\User\Schedule;
use Ventamatic\Exceptions\BranchPermissionException;

/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 23/10/16
 * Time: 05:30 PM
 */



class ReportService
{
    public function getSchedule($request)
    {
        $query = Schedule::query()
            ->with([
                'user' => function ($q) {$q->withTrashed();},
                'branch' => function ($q) {$q->withTrashed();},
                'scheduleStatus']);
        $this->validateBranchPermission('report-schedule', $query, $request);
        $this->processSimpleFields($query, $request, [
            'id',
            'user_id',
        ]);
        $this->processDateRange($query, $request);

        return $query;
    }

    public function getSale(Array $request){
        $query = Sale::query()
            ->withTrashed()
            ->with([
                'products' => function ($q) {$q->withTrashed();},
                'client' => function ($q) {$q->withTrashed();},
                'user' => function ($q) {$q->withTrashed();},
                'branch' => function ($q) {$q->withTrashed();}
            ]);
        $this->validateBranchPermission('report-sale', $query, $request);
        $this->processSimpleFields($query, $request, [
            'id',
            'user_id',
            'client_id',
        ]);
        $this->processDateRange($query, $request);

        return $query;
    }

    public function getBuy(Array $request){
        $query = Buy::query()
            ->with([
                'products' => function ($q) {$q->withTrashed();},
                'supplier' => function ($q) {$q->withTrashed();},
                'user' => function ($q) {$q->withTrashed();},
                'branch' => function ($q) {$q->withTrashed();}
            ]);
        $this->validateBranchPermission('report-buy', $query, $request);
        $this->processSimpleFields($query, $request, [
            'id',
            'user_id',
            'supplier_id',
            'supplier_bill_id',
        ]);

        $this->processDateRange($query, $request);

        return $query;
    }

    public function getInventoryMovements(Array $request){
        $query = InventoryMovement::with([
            'user' => function ($q) {$q->withTrashed();},
            'product' => function ($q) {$q->withTrashed();},
            'branch' => function ($q) {$q->withTrashed();},
            'inventoryMovementType' => function ($q) {$q->withTrashed();}
        ]);
        $this->validateBranchPermission('report-inventory-movement', $query, $request);
        $this->processSimpleFields($query, $request, [
            'product_id',
            'user_id',
            'inventory_movement_type_id',
        ]);

        $this->processDateRange($query, $request);

        return $query;
    }

    public function getInventory(Array $request)
    {
        $query = Inventory::with([
            'branch' => function ($q) {$q->withTrashed();},
            'product' => function ($q) {$q->withTrashed();},
            'product.brand' => function ($q) {$q->withTrashed();},
            'product.categories' => function ($q) {$q->withTrashed();}
            ]);
        $this->validateBranchPermission('report-inventory', $query, $request);
        $this->processSimpleFields($query, $request, [
            'product_id',
            'quantity',
            'price',
            'minimum',
        ]);
        $this->processOrder($query, $request);

        return $query;
    }

    public function getHistoricInventory(Array $request)
    {
        $query = Inventory::with([
                'branch' => function ($q) {$q->withTrashed();},
                'product' => function ($q) {$q->withTrashed();},
                'product.brand' => function ($q) {$q->withTrashed();},
                'product.categories' => function ($q) {$q->withTrashed();}
            ])
            ->historicFrom($request['date']);
        $this->validateBranchPermission('report-historic-inventory', $query, $request, 'inventories.branch_id');
        $this->processSimpleFields($query, $request, [
            'product_id',
            'price',
            'minimum',
        ], 'inventories');

        if($quantity = array_get($request, 'quantity')) {
            $query->having('quantity', '=', $quantity);
        }

        return $query;
    }

    private function validateBranchPermission($permission_name, $query, $request, $branchField = 'branch_id')
    {
        if ($branch_id = array_get($request, 'branch_id')) {
            BranchPermissionException::check($permission_name, Branch::findOrFail($branch_id));
            $query->where($branchField, $branch_id);
        } else{
            $branches = \Auth::user()->getBranchesWithPermission($permission_name);
            $query->whereIn($branchField, $branches->pluck('id'));
        }
    }

    public function processSimpleFields($query, $request, $fields, $table = null){
        foreach ($fields as $field) {
            if($data = array_get($request, $field)) {
                if ($table) {
                    $query->where($table . '.' . $field, $data);
                } else {
                    $query->where($field, $data);
                }
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

    private function processOrder($query, $request)
    {
        $orderBy = array_get($request, 'order_by');
        $orderType = array_get($request, 'order_type', 'desc');

        if (!($orderType == 'desc' || $orderBy == 'asc')) {
            throw new \Exception('Invalid order type');
        }

        if ($orderBy) {
            $query->orderBy($orderBy, $orderType);
        }
    }
}