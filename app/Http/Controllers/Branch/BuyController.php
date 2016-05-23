<?php namespace Ventamatic\Http\Controllers\Branch;


use Illuminate\Http\Request;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Http\Controllers\Controller;

class BuyController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function post(Request $request, Branch $branch)
    {
        \Log::alert("HolaSale");
        $client = Client::findOrFail($request->get('client_id'));
        \Log::alert($client->id);
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