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
        $this->middleware('jwt.auth');
        $this->productService = $productService;
    }

    public function get()
    {
        $products = Product::with('categories', 'unit', 'brand')->get();
        
        return $this->success(compact('products'));
    }

    public function getProduct(Product $product)
    {
        $product->load('categories', 'unit', 'brand');

        return $this->success(compact('product'));
    }

    public function post(Request $request)
    {
        $product = $this->productService->create($request->all());

        return $this->success(compact('product'));
    }

    public function delete(Product $product)
    {
        if($product->delete()){
            return $this->success();
        }else{
            return $this->error();
        }
    }

    public function put(Request $request, Product $product)
    {
        $product = $this->productService->update($product, $request->all());

        return $this->success(compact('product'));
    }

    public function getSearch(Request $request)
    {
        $products = Product::search($request->get('search'))->get();

        return $this->success(compact('products'));
    }
    
    public function getBarCode(Request $request){
        if($bar_code = $request->get('bar_code')) {
            $product = Product::with('categories', 'unit', 'brand')
                ->whereBarCode($bar_code)->first();
            if($product){
                return $this->success(compact('product'));
            } else {
                return $this->error(400, \Lang::get('products.not_found_bar_code',compact('bar_code')));
            }
        }
    }

}