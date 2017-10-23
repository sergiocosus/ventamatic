<?php namespace Ventamatic\Http\Controllers\Branch;


use Auth;
use DB;
use Illuminate\Http\Request;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Core\Branch\InventoryMovement;
use Ventamatic\Core\Branch\InventoryMovementType;
use Ventamatic\Core\Branch\PaymentType;
use Ventamatic\Core\Branch\Sale;
use Ventamatic\Core\External\Client;
use Ventamatic\Http\Controllers\Controller;


class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function post(Request $request, Branch $branch)
    {
        $this->canOnBranch('sale', $branch);

        if (!Auth::user()->getScheduleInInitialStatus()) {
            return $this->error(400,\Lang::get('schedule.no_schedule'));
        }

        $client = Client::findOrFail($request->get('client_id'));
        $paymentType = PaymentType::findOrFail($request->get('payment_type_id'));
        $cardPaymentId = $request->get('card_payment_id');
        $total = $request->get('total');
        $clientPayment = $request->get('client_payment');
        $products = $request->get('products');

        $sale = Sale::doSale(
            Auth::user(),
            $client,
            $branch,
            $paymentType,
            $products,
            $total,
            $clientPayment,
            $cardPaymentId);

        $sale->load([
            'products',
            'client',
            'paymentType',
            'user'
        ]);

        return $this->success(compact('sale'));
    }

    public function delete(Sale $sale)
    {
        $branch = $sale->branch;
        $this->canOnBranch('sale-delete', $branch);

        $batch = InventoryMovement::getLastBatch();

        foreach($sale->products as $product) {
            $branch->addInventoryMovement(Auth::user(),
                $product,
                [
                    'inventory_movement_type_id' => InventoryMovementType::SALE_CANCELED,
                    'quantity' => $product->pivot->quantity,
                    'value' => $product->pivot->price
                ],
                $batch
            );
        }

        $sale->delete();

        return $this->success();
    }
}