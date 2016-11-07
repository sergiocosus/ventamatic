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
            ->with('user', 'branch', 'scheduleStatus');
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
            ->with('products', 'client', 'user','branch');
        $this->validateBranchPermission('report-sale', $query, $request);
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
        $this->validateBranchPermission('report-buy', $query, $request);
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
        $this->validateBranchPermission('report-inventory-movement', $query, $request);
        $this->processSimpleFields($query, $request, [
            'product_id',
            'user_id',
            'inventory_movement_type_id',
            'branch_id',
        ]);

        $this->processDateRange($query, $request);

        return $query;
    }

    public function getInventory(Array $request)
    {
        $query = Inventory::with('branch', 'product', 'product.brand',
            'product.categories');
        $this->validateBranchPermission('report-inventory', $query, $request);
        $this->processSimpleFields($query, $request, [
            'branch_id',
            'product_id',
            'quantity',
            'price',
            'minimum',
        ]);

        return $query;
    }

    public function getHistoricInventory(Array $request)
    {
        $query = Inventory::with('branch', 'product')
            ->historicFrom($request['date']);
        $this->validateBranchPermission('report-historic-inventory', $query, $request);
        $this->processSimpleFields($query, $request, [
            'branch_id',
            'product_id',
            'quantity',
            'price',
            'minimum',
        ]);

        return $query;
    }

    private function validateBranchPermission($permission_name, $query, $request)
    {
        if ($branch_id = array_get($request, 'branch_id')) {
            BranchPermissionException::check($permission_name, Branch::findOrFail($branch_id));
            $query->where('branch_id', $branch_id);
        } else{
            $branches = \Auth::user()->getBranchesWithPermission($permission_name);
            $query->whereIn('branch_id', $branches->pluck('id'));
        }
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