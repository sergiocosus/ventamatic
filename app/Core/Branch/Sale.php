<?php namespace Ventamatic\Core\Branch;

use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ventamatic\Core\External\Client;
use Ventamatic\Core\Product\Product;
use Ventamatic\Core\System\RevisionableBaseModel;
use Ventamatic\Core\User\User;

class Sale extends RevisionableBaseModel {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $softDelete = true;

    protected $fillable = ['payment_type_id', 'card_payment_id', 
        'client_payment', 'total', 'user_id', 
        'client_id', 'branch_id'];

    protected $casts = [
        'id' => 'integer',
        'payment_type_id' => 'integer',
        'user_id' => 'integer',
        'client_id' => 'integer',
        'branch_id' => 'integer',
        'card_payment_id' => 'integer',
        'client_payment' => 'double',
        'total' => 'double',
        'current_price' => 'double',
    ];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function paymentType() {
        return $this->belongsTo(PaymentType::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity','price');
    }

    public function newPivot(Model $parent, array $attributes, $table, $exists, $using = NULL)
    {
        if ($parent instanceof Product) {
            return new ProductSalePivot($parent, $attributes, $table, $exists);
        }

        return parent::newPivot($parent, $attributes, $table, $exists);
    }


    public static function doSale(User $user,
                                  Client $client,
                                  Branch $branch,
                                  PaymentType $paymentType,
                                  Array $products,
                                  $given_total, $client_payment, $card_payment_id = null)
    {
        $sale = new self();
        $sale->client()->associate($client);
        $sale->user()->associate($user);
        $sale->branch()->associate($branch);
        $sale->paymentType()->associate($paymentType);

        $sale->card_payment_id=$card_payment_id;
        $sale->total=$given_total;
        $sale->client_payment=$client_payment;

        try {
            DB::beginTransaction();

            $sale->save();
            $calculated_total = $sale->attachProducts($products, $branch, $user);
            self::validateTotal($given_total, $calculated_total);

            DB::commit();
        }
        catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $sale;
    }

    private static function validateTotal($given_total, $calculated_total) {
        if($given_total != floor($calculated_total*100)/100){
            throw new Exception(\Lang::get('buy.total_not_match', [
                'given_total' => $given_total,
                'calculed_total' => $calculated_total,
            ]));
        }
    }
    
    private function attachProducts(Array $products, Branch $branch, User $user){
        $productsToAttach = [];
        $total = 0;
        foreach ($products as $productData) {
            /** @var Product $inventory */
            $inventory = Inventory::whereProductId($productData['product_id'])
                    ->whereBranchId($branch->id)->first();

            $price = $inventory->current_price;
            $quantity = $productData['quantity'];

            $total += $price * $quantity;

            $productsToAttach[$inventory->product_id] = compact('price', 'quantity');

            $branch->addInventoryMovement($user, $inventory->product, [
                'inventory_movement_type_id' => InventoryMovementType::VENTA,
                'quantity' => $productData['quantity'],
                'model' => $this,
                'value' => $price,
            ]);
        }
        $this->products()->attach($productsToAttach);
        return $total;
    }

    public function inventoryMovements()
    {
        return $this->morphMany(InventoryMovement::class, 'inventoriableMovement');
    }
}
