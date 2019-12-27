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
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/', 'WelcomeController@index')->name('welcome');
Route::get('/stats/horserace', 'StatsController@horserace')->name('stats/horserace');
Route::get('/stats/totals', 'StatsController@totals')->name('stats/totals');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/administrator/pay', 'PayController@index')->name('pay');
Route::post('/administrator/pay', 'PayController@insert')->name('pay/insert');

Route::post('/users', 'HomeController@insert')->name('users/insert');
Route::get('/users/profile', 'HomeController@profile')->name('users/profile');
Route::post('/users/edit', 'HomeController@edit')->name('users/edit');


Route::get('/administrator/products', 'ProductController@index')->name('products');
Route::post('/administrator/products', 'ProductController@insert')->name('products/insert');
Route::get('/administrator/products/delete/{id}', 'ProductController@delete')->name('products/delete');

Route::get('/administrator/transactions', 'TransactionController@index')->name('transactions');
Route::get('/administrator/transactions/{date}', 'TransactionController@transactionDetails')->name('transactions/details');
Route::post('/administrator/transactions', 'TransactionController@singleTransaction')->name('transactions/insert');

Route::get('/administrator/tally', 'TallyController@index')->name('tally');
Route::get('/tally/rows', 'TallyController@rows')->name('tally/rows');

Route::get('/administrator/users', 'UserController@index')->name('users');
Route::post('/administrator/users/invoice', 'UserController@invoice')->name('users/invoice');
