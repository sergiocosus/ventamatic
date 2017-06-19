<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 12/10/16
 * Time: 10:23 PM
 */

namespace Ventamatic\Http\Controllers\Report;


use Illuminate\Http\Request;
use Ventamatic\Core\Report\ReportService;
use Ventamatic\Http\Controllers\Controller;

class ReportController extends Controller
{
    /**
     * @var ReportService
     */
    private $reportService;


    /**
     * ReportController constructor.
     */
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
        $this->middleware('auth:api');
    }

    public function getSchedule(Request $request)
    {
        $schedules = $this->reportService->getSchedule($request->all())->get();

        return $this->success(compact('schedules'));
    }

    public function getSale(Request $request)
    {
        $sales = $this->reportService->getSale($request->all())->get();

        return $this->success(compact('sales'));
    }

    public function getBuy(Request $request)
    {
        $buys = $this->reportService->getBuy($request->all())->get();

        return $this->success(compact('buys'));
    }

    public function getInventoryMovements(Request $request)
    {
        $inventory_movements = $this->reportService
            ->getInventoryMovements($request->all())->get();

        return $this->success(compact('inventory_movements'));
    }

    public function getInventory(Request $request)
    {
        $inventories = $this->reportService
            ->getInventory($request->all())
            ->get();

        return $this->success(compact('inventories'));
    }

    public function getHistoricInventory(Request $request)
    {
        $inventories = $this->reportService
            ->getHistoricInventory($request->all())->get();

        return $this->success(compact('inventories'));
    }
}