<?php



Route::get('/', function () {
    return view('welcome');
});
Route::post('auth', 'Auth\AuthController@authenticate');

Route::group(['prefix' => 'user'],function(){
    Route::get('me','User\UserController@getMe');
    Route::get('','User\UserController@get');
    Route::get('search','User\UserController@getSearch');
    Route::post('','User\UserController@post');

    Route::group(['prefix' => '{user}'],function(){
        Route::get('','User\UserController@getUser');
        Route::delete('','User\UserController@delete');
        Route::put('','User\UserController@put');

        Route::group(['prefix' => 'roles'],function(){
            Route::put('','User\UserController@putRoles');
        });

        Route::group(['prefix' => 'branch-roles'],function(){
            Route::put('','User\UserController@putBranchRoles');
        });

        Route::group(['prefix' => 'schedule'],function(){
            Route::get('current','User\ScheduleController@getCurrent');
            Route::post('{branch}','User\ScheduleController@post');
            Route::put('','User\ScheduleController@patch');
            Route::put('note/{schedule}','User\ScheduleController@putNote');
        });
    });
});

Route::group(['prefix' => 'branch'],function(){
    Route::get('','Branch\BranchController@get');
    Route::get('search','Branch\BranchController@getSearch');

    Route::group(['prefix' => '{branch}'],function(){
        Route::get('','Branch\BranchController@getBranch');
        Route::put('','Branch\BranchController@put');

        Route::group(['prefix' => 'sale'],function(){
            Route::post('','Branch\SaleController@post');
        });

        Route::group(['prefix' => 'buy'],function(){
            Route::post('','Branch\BuyController@post');
        });

        Route::group(['prefix' => 'inventory'],function(){
            Route::get('','Branch\InventoryController@getAll');
            Route::get('search','Branch\InventoryController@getSearch');
            Route::get('bar-code','Branch\InventoryController@getBarCode');
            Route::put('{product}','Branch\InventoryController@put');
            Route::post('{product}','Branch\InventoryController@post');
            Route::get('{product}','Branch\InventoryController@get');
            Route::patch('{product}','Branch\InventoryController@addInventoryMovement');
        });

    });
});

Route::group(['prefix' => 'client'],function(){
    Route::get('','ClientController@get');
    Route::get('search','ClientController@getSearch');
    Route::post('','ClientController@post');

    Route::group(['prefix' => '{client}'],function() {
        Route::get('','ClientController@getClient');
        Route::delete('', 'ClientController@delete');
        Route::put('', 'ClientController@put');
    });
});

Route::group(['prefix' => 'supplier'],function(){
    Route::get('','Supplier\SupplierController@get');
    Route::get('search','Supplier\SupplierController@getSearch');
    Route::post('','Supplier\SupplierController@post');

    Route::group(['prefix' => 'category'],function(){
        Route::get('','Supplier\CategoryController@get');
        Route::post('','Supplier\CategoryController@post');

        Route::group(['prefix' => '{supplierCategory}'],function() {
            Route::get('','Supplier\CategoryController@getCategory');
            Route::delete('', 'Supplier\CategoryController@delete');
            Route::put('', 'Supplier\CategoryController@put');
        });
    });

    Route::group(['prefix' => '{supplier}'],function() {
        Route::get('','Supplier\SupplierController@getSupplier');
        Route::delete('', 'Supplier\SupplierController@delete');
        Route::put('', 'Supplier\SupplierController@put');
    });
});

Route::group(['prefix' => 'product'],function(){
    Route::get('','Product\ProductController@get');
    Route::post('','Product\ProductController@post');
    Route::get('search','Product\ProductController@getSearch');
    Route::get('bar-code','Product\ProductController@getBarCode');

    Route::group(['prefix' => 'category'],function(){
        Route::get('','Product\CategoryController@get');
        Route::post('','Product\CategoryController@post');

        Route::group(['prefix' => '{category}'],function() {
            Route::get('','Product\CategoryController@getCategory');
            Route::delete('', 'Product\CategoryController@delete');
            Route::put('', 'Product\CategoryController@put');
        });
    });

    Route::group(['prefix' => 'brand'],function(){
        Route::get('','Product\BrandController@get');
        Route::post('','Product\BrandController@post');

        Route::group(['prefix' => '{brand}'],function() {
            Route::get('','Product\BrandController@getBrand');
            Route::delete('', 'Product\BrandController@delete');
            Route::put('', 'Product\BrandController@put');
        });
    });

    Route::group(['prefix' => '{product}'],function() {
        Route::get('','Product\ProductController@getProduct');
        Route::delete('', 'Product\ProductController@delete');
        Route::put('', 'Product\ProductController@put');
    });
});

Route::group(['prefix' => 'report'],function(){
    Route::get('sale','Report\ReportController@getSale');
    Route::get('buy','Report\ReportController@getBuy');
    Route::get('inventory-movements','Report\ReportController@getInventoryMovements');
    Route::get('inventory','Report\ReportController@getInventory');
    Route::get('historic-inventory','Report\ReportController@getHistoricInventory');
    Route::get('schedule','Report\ReportController@getSchedule');
});


Route::group(['prefix' => 'revision'],function(){
    Route::get('','RevisionController@get');
    Route::get('user/{user}','RevisionController@getUser');
});



Route::group(['prefix' => 'security'],function(){
    Route::group(['prefix' => 'system'],function(){
        Route::group(['prefix' => 'permission'],function(){
            Route::get('','Security\SystemPermissionController@get');
            Route::get('{permission}','Security\SystemPermissionController@getPermission');
        });
        Route::group(['prefix' => 'role'],function(){
            Route::get('','Security\SystemRoleController@get');
            Route::post('','Security\SystemRoleController@post');

            Route::group(['prefix' => '{role}'],function() {
                Route::get('','Security\SystemRoleController@getRole');
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
            Route::get('','Security\BranchPermissionController@getAll');
            Route::get('{permission}','Security\BranchPermissionController@get');
        });
        Route::group(['prefix' => 'role'],function(){
            Route::get('','Security\BranchRoleController@get');
            Route::post('','Security\BranchRoleController@post');

            Route::group(['prefix' => '{branch_role}'],function() {
                Route::get('','Security\BranchRoleController@getBranchRole');
                Route::delete('', 'Security\BranchRoleController@delete');
                Route::put('', 'Security\BranchRoleController@put');
                Route::group(['prefix' => 'permission'],function() {
                    Route::put('{branchPermission}', 'Security\BranchRoleController@putPermission');
                    Route::delete('{branchPermission}', 'Security\BranchRoleController@deletePermission');
                });
            });
        });
    });
});

