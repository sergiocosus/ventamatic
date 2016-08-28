<?php namespace Ventamatic\Core\Branch;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Exception;
use Ventamatic\Core\Product\Product;
use Ventamatic\Core\System\RevisionableBaseModel;
use Ventamatic\Core\External\Supplier;
use Ventamatic\Core\User\User;

class Buy extends RevisionableBaseModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    protected $fillable = ['id', 'payment_type_id', 'card_payment_id', 'iva',
        'ieps', 'total', 'user_id', 'supplier_id', 'branch_id',
        'supplier_bill_id'];

    protected $casts = [
        'id' => 'integer',
        'payment_type_id' => 'integer',
        'user_id' => 'integer',
        'supplier_id' => 'integer',
        'branch_id' => 'integer',
        'card_payment_id' => 'integer',
        'iva' => 'double',
        'ieps' => 'double',
        'total' => 'double',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('cost','quantity');
    }

    public static function doBuy(User $user,
                                 $supplier,
                                 $supplierBillId,
                                 Branch $branch,
                                 PaymentType $paymentType,
                                 Array $products,
                                 $ieps, $iva, $total,
                                 $card_payment_id = null)
    {
        $buy = new self();
        $buy->supplier()->associate($supplier);
        $buy->user()->associate($user);
        $buy->branch()->associate($branch);
        $buy->paymentType()->associate($paymentType);

        $buy->card_payment_id = $card_payment_id;
        $buy->total = $total;
        $buy->ieps = $ieps;
        $buy->iva = $iva;
        $buy->supplier_bill_id = $supplierBillId;

        try {
            DB::beginTransaction();

            foreach ($products as $productData) {
                /** @var Product $product */
                $product = Product::findOrFail($productData['product_id']);
                $quantity = $productData['quantity'];

                $branch->alterInventory($product, $quantity);
            }

            $buy->save();
            $calculatedTotal = $buy->attachProducts($products);

            if ($total != $calculatedTotal) {
                throw new Exception(\Lang::get('buy.total_not_match', [
                    'given_total' => $total,
                    'calculed_total' => $calculatedTotal,
                ]));
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $buy;
    }

    private function attachProducts(Array $products)
    {
        $productsToAttach = [];
        $total = 0;
        foreach ($products as $product_data) {
            /** @var Product $product */
            $product = Product::findOrFail($product_data['product_id']);
            $cost = $product_data['cost'];
            $quantity = $product_data['quantity'];

            $total += $cost * $quantity;
            $productsToAttach[$product->id] = compact('cost', 'quantity');
        }
        $this->products()->attach($productsToAttach);
        return $total;
    }


}
