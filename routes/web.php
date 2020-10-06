<?php

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

Route::get('/', 'Front\HomeController@index');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth'], function () {

    Route::resource('products/categories', 'admin\CategoryController')->except(['edit', 'create']);
    Route::resource('/products', 'admin\ProductController');
    Route::get('products/{id}/images', 'admin\ProductController@images')->name('products.images');
    Route::post('products/{id}/images', 'admin\ProductController@imagesUpload')->name('products.images.upload');
    Route::delete('products/{id}/images', 'admin\ProductController@imageRemove')->name('products.images.remove');
});
