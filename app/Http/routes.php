<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'api'],function(){
Route::group(['prefix' => 'v1'],function(){

    Route::post('auth', 'Auth\AuthController@authenticate');

    Route::group(['prefix' => 'user'],function(){
        Route::get('','UserController@get');
        Route::post('','UserController@post');
        
        Route::group(['prefix' => '{user}'],function(){
            Route::delete('','UserController@delete');
            Route::put('','UserController@put');

            Route::group(['prefix' => 'role/{role}'],function(){
                Route::put('','UserController@putRole');
                Route::delete('','UserController@deleteRole');
            });
            
            Route::group(['prefix' => 'schedule'],function(){
                Route::post('','UserController@postSchedule');
                Route::patch('{schedule}','UserController@patchSchedule');
            });
        });
    });

    Route::group(['prefix' => 'branch'],function(){
        Route::get('','Branch\BranchController@get');

        Route::group(['prefix' => '{branch}'],function(){
            Route::get('','Branch\BranchController@get');
            Route::put('','Branch\BranchController@put');
            
            Route::group(['prefix' => 'sale'],function(){
                Route::post('','Branch\SaleController@post');
            });

            Route::group(['prefix' => 'buy'],function(){
                Route::post('','Branch\BuyController@post');
            });

            Route::group(['prefix' => 'inventory'],function(){
                Route::put('','Branch\InventoryController@put');
            });
            
        });
    });

    Route::group(['prefix' => 'client'],function(){
        Route::get('','ClientController@get');
        Route::post('','ClientController@post');

        Route::group(['prefix' => '{client}'],function() {
            Route::get('','ClientController@get');
            Route::delete('', 'ClientController@delete');
            Route::put('', 'ClientController@put');
        });
    });
    
    Route::group(['prefix' => 'supplier'],function(){ 
        Route::get('','Supplier\CategoryController@get');
        Route::post('','Supplier\CategoryController@post');

        Route::group(['prefix' => '{supplier}'],function() {
            Route::get('','Supplier\CategoryController@get');
            Route::delete('', 'Supplier\CategoryController@delete');
            Route::put('', 'Supplier\CategoryController@put');
        });

        Route::group(['prefix' => 'category'],function(){
            Route::get('','Supplier\CategoryController@get');
            Route::post('','Supplier\SupplierController@post');

            Route::group(['prefix' => '{supplierCategory}'],function() {
                Route::get('','Supplier\SupplierController@get');
                Route::delete('', 'Supplier\SupplierController@delete');
                Route::put('', 'Supplier\SupplierController@put');
            });
        });
    });
    
    Route::group(['prefix' => 'product'],function(){
        Route::get('','Product\ProductController@get');
        Route::post('','Product\ProductController@post');

        Route::group(['prefix' => '{product}'],function() {
            Route::get('','Product\ProductController@get');
            Route::delete('', 'Product\ProductController@delete');
            Route::put('', 'Product\ProductController@put');
        });
        
        Route::group(['prefix' => 'category'],function(){
            Route::get('','Product\CategoryController@get');
            Route::post('','Product\CategoryController@post');

            Route::group(['prefix' => '{category}'],function() {
                Route::get('','Product\CategoryController@get');
                Route::delete('', 'Product\CategoryController@delete');
                Route::put('', 'Product\CategoryController@put');
            });
        });
        
        Route::group(['prefix' => 'brand'],function(){
            Route::get('','Product\BrandController@get');
            Route::post('','Product\BrandController@post');

            Route::group(['prefix' => '{brand}'],function() {
                Route::get('','Product\BrandController@get');
                Route::delete('', 'Product\BrandController@delete');
                Route::put('', 'Product\BrandController@put');
            });
        });
    });
    

    
    Route::group(['prefix' => 'revision'],function(){
        Route::get('','RevisionController@get');
        Route::get('{user}','RevisionController@get');
    });
    

    
    Route::group(['prefix' => 'security'],function(){
        Route::group(['prefix' => 'system'],function(){
            Route::group(['prefix' => 'permission'],function(){
                Route::get('{permission?}','Security\SystemPermissionController@get');
            });
            Route::group(['prefix' => 'role'],function(){
                Route::get('','Security\SystemRoleController@get');
                Route::post('','Security\SystemRoleController@post');

                Route::group(['prefix' => '{role}'],function() {
                    Route::get('','Security\SystemRoleController@get');
                    Route::delete('', 'Security\SystemRoleController@delete');
                    Route::put('', 'Security\SystemRoleController@put');
                    Route::group(['prefix' => 'permission'],function() {
                        Route::put('{permission}','Security\SystemRoleController@putPermission');
                        Route::delete('{permission}', 'Security\SystemRoleController@deletePermission');
                    });
                });
            });
        });
        Route::group(['prefix' => 'branch'],function(){
            Route::group(['prefix' => 'permission'],function(){
                Route::get('{branchPermission?}','Security\BranchRoleController@get');
            });
            Route::group(['prefix' => 'role'],function(){
                Route::get('','Security\BranchRoleController@get');
                Route::delete('', 'Security\BranchRoleController@delete');
                Route::put('', 'Security\BranchRoleController@put');
                Route::group(['prefix' => 'permission'],function() {
                    Route::put('{branchPermission}','Security\BranchRoleController@putPermission');
                    Route::delete('{branchPermission}', 'Security\BranchRoleController@deletePermission');
                });
            });
        });
    });

});
});

