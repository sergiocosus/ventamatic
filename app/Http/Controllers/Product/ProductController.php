<?php namespace Ventamatic\Http\Controllers\Product;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Ventamatic\Core\Product\Product;
use Ventamatic\Core\Product\ProductService;
use Ventamatic\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->middleware('auth:api');
        $this->productService = $productService;
    }

    public function get(Request $request)
    {
        $this->can('product-get');

        $query = Product::withRelations();

        if ($request->get('deleted')) {
            $this->can('product-delete');
            $query->withTrashed();
        }

        $products = $query->get();
        
        return $this->success(compact('products'));
    }

    public function getProduct(Product $product)
    {
        $this->can('product-get-detail');

        $product->load('categories', 'unit', 'brand');
        $product->append('last_cost');

        return $this->success(compact('product'));
    }

    public function post(Request $request)
    {
        $this->can('product-create');

        $product = $this->productService->create($request->all());

        return $this->success(compact('product'));
    }

    public function delete(Product $product)
    {
        $this->can('product-delete');

        if($product->delete()){
            return $this->success();
        }else{
            return $this->error();
        }
    }

    public function patchRestore(Product $product)
    {
        $this->can('product-delete');

        if($product->restore()){
            $product->load(Product::$basicRelationsToLoad);
            return $this->success(compact('product'));
        }else{
            return $this->error();
        }
    }

    public function put(Request $request, Product $product)
    {
        $this->can('product-edit');

        $product = $this->productService->update($product, $request->all());

        return $this->success(compact('product'));
    }

    public function getSearch(Request $request)
    {
        $this->can('product-get');

        $products = Product::search($request->get('search'), null, true, true)
            ->groupBy('products.id')
            ->limit(100)
            ->get();

        $products->each(function($product) {
            $product->append('last_cost');
        });

        return $this->success(compact('products'));
    }
    
    public function getBarCode(Request $request){
        $this->can('product-get');

        if($bar_code = $request->get('bar_code')) {
            $product = Product::with('categories', 'unit', 'brand')
                ->whereBarCode($bar_code)->first();
            $product->append('last_cost');

            if($product){
                return $this->success(compact('product'));
            } else {
                return $this->error(400, \Lang::get('products.not_found_bar_code',compact('bar_code')));
            }
        }
    }

}