<?php

use App\Jobs\SendEmailOrderReceived;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth', 'namespace' => 'admin'], function () {
    Route::resource('products/categories', 'CategoryController')->except(['edit', 'create']);
    Route::resource('/products', 'ProductController');

    Route::get('products/{id}/images', 'ProductController@images')->name('products.images');
    Route::post('products/{id}/images', 'ProductController@imagesUpload')->name('products.images.upload');
    Route::delete('products/{id}/images', 'ProductController@imageRemove')->name('products.images.remove');

    Route::get('orders', 'OrderController@index')->name('orders.index');
    Route::get('orders/trashed', 'OrderController@trashed')->name('orders.trashed');
    Route::get('orders/restore/{id}', 'OrderController@restore')->name('orders.restore');
    Route::get('orders/{id}', 'OrderController@show')->name('orders.show');
    Route::get('orders/{id}/cancel', 'OrderController@cancel')->name('orders.cancel');
    Route::post('orders/complete/{id}', 'OrderController@doComplete')->name('orders.do_complete');
    Route::put('orders/{id}/do-cancel', 'OrderController@doCancel')->name('orders.do_cancel');
    Route::delete('orders/{id}', 'OrderController@destroy')->name('orders.destroy');

    Route::get("shipments", 'ShipmentController@index')->name('shipments');
    Route::get("shipments{id}", 'ShipmentController@show')->name('shipments.show');
    Route::get("shipments/{id}/edit", 'ShipmentController@edit')->name('shipments.edit');
    Route::put("shipments/{id}/update", 'ShipmentController@update')->name('shipments.update');

    Route::get('reports/revenue', 'ReportController@revenue')->name('revenue');
});

Auth::routes();

Route::get('/', 'Front\HomeController@index');
Route::get('/products', 'front\ProductController@index');
Route::get('/products/{slug}', 'Front\ProductController@show')->name('products.show');

// cart
Route::get('/carts', 'Front\CartController@index')->name('carts');
Route::get('/carts/clear', 'Front\CartController@clear')->name('carts.clear');
Route::post('/carts', 'Front\CartController@add')->name('carts.add');
Route::patch('/carts', 'Front\CartController@update')->name('carts.update');
Route::delete('/carts/{id?}', 'Front\CartController@destroy')->name('carts.destroy');

// orders
Route::get('/orders', 'Front\OrderController@index')->name('orders');
Route::get('/orders/checkout', 'Front\OrderController@checkout')->name('orders.checkout');
Route::get('/orders/get-cities', 'Front\OrderController@getCityList');
Route::get('/orders/received/{orderId}', 'Front\OrderController@received');
Route::post('/orders/do-checkout', 'Front\OrderController@doCheckout');
Route::post('/orders/shipping-cost', 'Front\OrderController@shippingCost');
Route::post('/orders/set-shipping', 'Front\OrderController@setShipping');
Route::get('/orders/{id}', 'Front\OrderController@show')->name('orders.show');

//Payments
Route::post('payments/notification', 'PaymentController@notification');
Route::get('payments/completed', 'PaymentController@completed');
Route::get('payments/failed', 'PaymentController@failed');
Route::get('payments/unfinish', 'PaymentController@unfinish');

Route::get('profiles', 'Auth\ProfileController@index');
Route::post('profiles', 'Auth\ProfileController@update');

Route::get('coba', function () {
    dispatch(new SendEmailOrderReceived([], Auth::user()));
});
