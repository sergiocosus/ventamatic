<?php namespace Ventamatic\Http\Controllers\Branch;


use Illuminate\Http\Request;
use Ventamatic\Core\Branch\Branch;
use Ventamatic\Core\Branch\Buy;
use Ventamatic\Core\Branch\PaymentType;
use Ventamatic\Core\External\Supplier;
use Ventamatic\Http\Controllers\Controller;
use Auth;
use DB;




class BuyController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function post(Request $request, Branch $branch)
    {

            \Log::alert("HolaBuy");
            $supplier = Supplier::findOrFail($request->get('supplier_id'));
            \Log::alert($supplier->id);
            /** @var PaymentType $paymentType */
            $paymentType = PaymentType::findOrFail($request->get('payment_type_id'));
            \Log::alert($paymentType);
            $cardPaymentId = $request->get('card_payment_id');
            \Log::alert($cardPaymentId);
            $total = $request->get('total');
            \Log::alert($total);
            $ieps = $request->get('ieps');
            \Log::alert($ieps);
            $iva = $request->get('iva');
            \Log::alert($iva);
            $products = $request->get('products');
            \Log::alert($products);
            \Log::alert('Hola');
            $buy = Buy::doBuy(
                Auth::user(),
                $supplier,
                $branch,
                $paymentType,
                $products,
                $ieps,
                $iva,
                $total,
                $cardPaymentId);

            return $this->success(compact('buy'));
        }
}