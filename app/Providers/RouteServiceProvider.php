<?php

namespace Ventamatic\Providers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Route;
use Ventamatic\Core\External\Client;
use Ventamatic\Core\External\Supplier;
use Ventamatic\Core\External\SupplierCategory;
use Ventamatic\Core\Product\Brand;
use Ventamatic\Core\Product\Category;
use Ventamatic\Core\Product\Product;
use Ventamatic\Core\User\User;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Ventamatic\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        \Route::bind('user', function ($value) {
            if($value == 'me'){
                return $user = Auth::user();
            } else {
                return User::findOrFail($value);
            }
        });

        \Route::bind('productTrashed', function ($id) {
            $product = Product::onlyTrashed()->whereKey($id)->first();

            if ($product) {
                return $product;
            } else {
                throw (new ModelNotFoundException)->setModel(
                    Product::class, $id
                );
            }
        });

        \Route::bind('supplierTrashed', function ($id) {
            $supplier = Supplier::onlyTrashed()->whereKey($id)->first();

            if ($supplier) {
                return $supplier;
            } else {
                throw (new ModelNotFoundException)->setModel(
                    Supplier::class, $id
                );
            }
        });

        \Route::bind('clientTrashed', function ($id) {
            $client = Client::onlyTrashed()->whereKey($id)->first();

            if ($client) {
                return $client;
            } else {
                throw (new ModelNotFoundException)->setModel(
                    Client::class, $id
                );
            }
        });

        \Route::bind('userTrashed', function ($id) {
            $user = User::onlyTrashed()->whereKey($id)->first();

            if ($user) {
                return $user;
            } else {
                throw (new ModelNotFoundException)->setModel(
                    User::class, $id
                );
            }
        });

        \Route::bind('categoryTrashed', function ($id) {
            $category = Category::onlyTrashed()->whereKey($id)->first();

            if ($category) {
                return $category;
            } else {
                throw (new ModelNotFoundException)->setModel(
                    Category::class, $id
                );
            }
        });

        \Route::bind('supplier_categoryTrashed', function ($id) {
            $supplierCategory = SupplierCategory::onlyTrashed()->whereKey($id)->first();

            if ($supplierCategory) {
                return $supplierCategory;
            } else {
                throw (new ModelNotFoundException)->setModel(
                    SupplierCategory::class, $id
                );
            }
        });

        \Route::bind('brandTrashed', function ($id) {
            $brand = Brand::onlyTrashed()->whereKey($id)->first();

            if ($brand) {
                return $brand;
            } else {
                throw (new ModelNotFoundException)->setModel(
                    Brand::class, $id
                );
            }
        });

    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes($router);

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapWebRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace, 'middleware' => 'web',
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => 'api',
            'prefix' => 'v1',
        ], function () {
            \Laravel\Passport\Passport::routes();
        });

        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace,
            'prefix' => 'v1',
        ], function () {
            require base_path('routes/api.php');
        });
    }
}
