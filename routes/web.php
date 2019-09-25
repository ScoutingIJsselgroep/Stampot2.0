<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/pay', 'PayController@index')->name('pay');
Route::post('/pay', 'PayController@insert')->name('pay/insert');

Route::post('/users', 'HomeController@insert')->name('users/insert');

Route::get('/products', 'ProductController@index')->name('products');
Route::post('/products', 'ProductController@insert')->name('products/insert');
Route::get('/products/delete/{id}', 'ProductController@delete')->name('products/delete');

Route::get('/transactions', 'TransactionController@index')->name('transactions');
Route::get('/transactions/{date}', 'TransactionController@transactionDetails')->name('transactions/details');
Route::post('/transactions', 'TransactionController@singleTransaction')->name('transactions/insert');

Route::get('/tally', 'TallyController@index')->name('tally');
Route::get('/tally/rows', 'TallyController@rows')->name('tally/rows');
