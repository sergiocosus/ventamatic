<?php namespace Ventamatic\Core\Branch;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Exception;
use Ventamatic\Core\Product\Product;
use Ventamatic\Core\System\RevisionableBaseModel;
use Ventamatic\Core\External\Supplier;
use Ventamatic\Core\User\User;

class Buy extends RevisionableBaseModel {
    
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

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function paymentType() {
        return $this->belongsTo(PaymentType::class);
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class);
    }

    public static function doBuy(User $user,
                                  $supplier,
                                  Branch $branch,
                                  PaymentType $paymentType,
                                  Array $products,
                                  $ieps,$iva,$total,
                                  $card_payment_id = null)
    {
        \Log::alert('Inicio Do Buy');
        $buy = new self();
        $buy->supplier()->associate($supplier);
        $buy->user()->associate($user);
        $buy->branch()->associate($branch);
        $buy->paymentType()->associate($paymentType);

        \Log::alert('Inicio Do Buy2');
        $buy->card_payment_id=$card_payment_id;
        $buy->total=$total;
        $buy->ieps=$ieps;
        $buy->iva=$iva;
        \Log::alert('Inicio Do Buy3');
        \Log::alert('$total:'.$total);
        \Log::alert('ieps:'.$ieps);
        \Log::alert('iva'.$iva);


        try {
            DB::beginTransaction();
            \Log::alert('Inicio Do Buy4');

            $branch->addInventory($products);
            \Log::alert('Inicio Do Buy5');
            $buy->save();
            \Log::alert('Inicio Do Buy6');
            $calculatedTotal = $buy->attachProducts($products);
            \Log::alert('Inicio Do Buy7');
            \Log::alert('Inicio Do Buytotal:'.$total);
            \Log::alert('Inicio Do CalculedTotal:'.$calculatedTotal);

            if($total != $calculatedTotal){
                throw new Exception('El total no concuerda');
            }
            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $buy;

    }

    private function attachProducts(Array $products){
        $productsToAttach = [];
        $total = 0;
        \Log::alert('En AttachProducts');
        foreach ($products as $product_data)
        {
            /** @var Product $product */
            \Log::alert('InicyAntes');
            $product = Product::findOrFail($product_data['product_id']);
            \Log::alert('dESPUeS DE PRODUCT:'.$product);
            $cost = $product->getCorrectPrice();
            \Log::alert('dESPUeS DE PRODUCTcost:'.$cost);
            $quantity = $product_data['quantity'];
            \Log::alert('dESPUeS DE PRODUCTQuantity:'.$quantity);


            $total += $cost * $quantity;
            \Log::alert('dESPUeS DE PRODUCT total:'.$total);
            $productsToAttach[$product->id] = compact('cost', 'quantity');
            \Log::alert('dESPUeS DE PRODUCT id:'.$product->id);
        }
        \Log::alert('dESPUeS DE PRODUCT1');
        $this->products()->attach($productsToAttach);
        \Log::alert('dESPUeS DE PRODUCT2T:'.$total);
        return $total;
    }
    

}
