<?php namespace Ventamatic\Http\Controllers\Branch;


use Auth;
use DB;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Core\Branch\PaymentType;
use Ventamatic\Core\Branch\Sale;
use Ventamatic\Core\External\Client;
use Ventamatic\Http\Controllers\Controller;
use Ventamatic\Http\Requests\Request;

class SaleController extends Controller
{
    public function post(Request $request, Branch $branch)
    {
        $client = Client::findOrFail($request->get('client_id'));
        $paymentType = PaymentType::findOrFail($request->get('payment_type_id'));
        $cardPaymentId = $request->get('card_payment_id');
        $total = $request->get('total');
        $clientPayment=$request->get('client_payment');
        $products = $request->get('products');

        $sale  = Sale::doSale(
            Auth::user(),
            $client,
            $branch,
            $paymentType,
            $products,
            $total,
            $clientPayment,
            $cardPaymentId);

        return compact('sale');
    }
}