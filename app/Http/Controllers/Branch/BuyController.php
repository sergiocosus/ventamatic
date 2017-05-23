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
        $this->middleware('auth:api');
    }

    public function post(Request $request, Branch $branch)
    {
        $this->canOnBranch('buy', $branch);

        $data = $request->all();

        $supplier = Supplier::findOrFail($data['supplier_id']);
        /** @var PaymentType $paymentType */
        $paymentType = PaymentType::findOrFail($data['payment_type_id']);
        $cardPaymentId = $data['card_payment_id'];
        $total = $data['total'];
        $ieps = $data['ieps'];
        $iva = $data['iva'];
        $products = $data['products'];
        $supplierBillId = $data['supplier_bill_id'];

        $buy = Buy::doBuy(
            Auth::user(),
            $supplier,
            $supplierBillId,
            $branch,
            $paymentType,
            $products,
            $ieps,
            $iva,
            $total,
            $cardPaymentId);

        $buy->load('products');

        return $this->success(compact('buy'));
    }
}