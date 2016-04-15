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


Route::get('user/me', 'UserController@getUser');



Route::group(['prefix' => '/api'],function(){
Route::group(['prefix' => 'v1'],function(){

    Route::post('auth', 'Auth\AuthController@authenticate');

    Route::group(['prefix' => 'user'],function(){
        Route::get('','');
        Route::post('','');
        
        Route::group(['prefix' => '{user}'],function(){
            Route::delete('','');
            Route::put('','');

            Route::group(['prefix' => 'role/{role}'],function(){
                Route::post('','');
                Route::delete('','');
            });
            
            Route::group(['prefix' => 'schedule'],function(){
                Route::post('','');
                Route::put('{schedule}','');
            });
        });
    });

    Route::group(['prefix' => 'branch'],function(){
        Route::get('','');

        Route::group(['prefix' => '{branch}'],function(){
            Route::get('','');
            Route::put('','');
            
            Route::group(['prefix' => 'sale'],function(){
                Route::post('','');
            });

            Route::group(['prefix' => 'buy'],function(){
                Route::post('','');
            });

            Route::group(['prefix' => 'inventory'],function(){
                Route::put('','');
            });
            
        });
    });

    Route::group(['prefix' => 'client'],function(){
        Route::get('','');
        Route::post('','');

        Route::group(['prefix' => '{client}'],function() {
            Route::get('','');
            Route::delete('', '');
            Route::put('', '');
        });

    });
    
    Route::group(['prefix' => 'supplier'],function(){ 
        Route::get('','');
        Route::post('','');

        Route::group(['prefix' => '{supplier}'],function() {
            Route::get('','');
            Route::delete('', '');
            Route::put('', '');
        });

        Route::group(['prefix' => 'category'],function(){
            Route::get('','');
            Route::post('','');

            Route::group(['prefix' => '{supplierCategory}'],function() {
                Route::get('','');
                Route::delete('', '');
                Route::put('', '');
            });
        });
    });
    
    Route::group(['prefix' => 'product'],function(){
        Route::get('','');
        Route::post('','');

        Route::group(['prefix' => '{product}'],function() {
            Route::get('','');
            Route::delete('', '');
            Route::put('', '');
        });
        
        Route::group(['prefix' => 'category'],function(){
            Route::get('','');
            Route::post('','');

            Route::group(['prefix' => '{category}'],function() {
                Route::get('','');
                Route::delete('', '');
                Route::put('', '');
            });
        });
        
        Route::group(['prefix' => 'brand'],function(){
            Route::get('','');
            Route::post('','');

            Route::group(['prefix' => '{brand}'],function() {
                Route::get('','');
                Route::delete('', '');
                Route::put('', '');
            });
        });
    });
    

    
    Route::group(['prefix' => 'revision'],function(){
        Route::get('','');
        Route::get('{user}','');
    });
    

    
    Route::group(['prefix' => 'security'],function(){
        Route::group(['prefix' => 'system'],function(){
            Route::group(['prefix' => 'permission'],function(){
                Route::get('','');
                Route::get('{permission}','');
            });
            Route::group(['prefix' => 'role'],function(){
                Route::get('','');
                Route::post('','');

                Route::group(['prefix' => '{role}'],function() {
                    Route::get('','');
                    Route::delete('', '');
                    Route::put('', '');
                    Route::group(['prefix' => 'permission'],function() {
                        Route::post('{permission}','');
                        Route::delete('{permission}', '');

                    });
                });
            });
        });
        Route::group(['prefix' => 'branch'],function(){
            Route::group(['prefix' => 'permission'],function(){
                Route::get('','');
                Route::get('{branchPermission}','');
            });
            Route::group(['prefix' => 'role'],function(){
                Route::get('','');
                Route::delete('', '');
                Route::put('', '');
                Route::group(['prefix' => 'permission'],function() {
                    Route::post('{branchPermission}','');
                    Route::delete('{branchPermission}', '');
                });
            });
        });
    });
    
   
    
    
});
});

