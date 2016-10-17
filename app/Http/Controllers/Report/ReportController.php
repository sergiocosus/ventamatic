<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 12/10/16
 * Time: 10:23 PM
 */

namespace Ventamatic\Http\Controllers\Report;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Ventamatic\Core\Branch\Sale;
use Ventamatic\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function getSale(Request $request)
    {
        $query = Sale::query()
            ->with('products', 'client', 'user','branch');

        if ($sale_id = $request->get('sale_id')) {
            \Log::info($sale_id);
            $query->whereId($sale_id);
        }

        if ($user_id = $request->get('user_id')) {
            $query->whereUserId($user_id);
        }

        if ($branch_id = $request->get('branch_id')) {
            $query->whereBranchId($branch_id);
        }

        if ($client_id = $request->get('client_id')) {
            $query->whereClientId($client_id);
        }

        if ($begin_at = $request->get('begin_at')) {
            if (strlen($begin_at) <= 10) {
                $begin_at .= ' 00:00:00';
            }
            $begin_at = new Carbon($begin_at,'America/Mexico_City');
            $begin_at->setTimezone('UTC');

            $query->where('created_at', '>=', $begin_at);
        }

        if ($end_at = $request->get('end_at')) {
            if (strlen($end_at) <= 10) {
                $end_at .= ' 23:59:59';
            }

            $end_at = new Carbon($end_at,'America/Mexico_City');
            $end_at->setTimezone('UTC');

            $query->where('created_at', '<=', $end_at);
        }

        $sales = $query->get();

        return $this->success(compact('sales'));
    }
}