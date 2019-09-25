<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('auth');
  }

  /**
   * Handle the incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $transactionDates = DB::table('transactions')
          ->select(DB::raw('CAST((transaction_created_at - INTERVAL 9 HOUR) AS DATE) AS date, SUM(amount) AS num_transactions'))
          ->orderBy('date', 'desc')
          ->groupBy(DB::raw('CAST((transaction_created_at - INTERVAL 9 HOUR) AS DATE)'))
          ->where('product_id', '!=', 0)
          ->paginate(10, ['*'], 'date_page');
    return view('transactions', ['transaction_dates'=>$transactionDates]);
  }

  public function transactionDetails(Request $request)
  {
    $transaction_details = DB::table('transactions')
          ->select('user_id', 'users.name AS user_name', 'products.filename AS product_icon', 'products.name AS product_name', DB::raw('CAST((transaction_created_at - INTERVAL 9 HOUR) AS DATE) AS transaction_created_at'), DB::raw('SUM(amount) AS amount'))
          ->join('users', 'transactions.user_id', '=', 'users.id')
          ->join('products', 'transactions.product_id', '=', 'products.id')
          ->orderBy('user_name', 'asc')
          ->whereDate(DB::raw('CAST((transaction_created_at - INTERVAL 9 HOUR) AS DATE)'), $request->date)
          ->groupBy('user_id', 'product_id', DB::raw('CAST((transaction_created_at - INTERVAL 9 HOUR) AS DATE)'))
          ->paginate(100, ['*'], 'transaction_page');
    return view('transaction_details', ['transaction_details'=>$transaction_details, 'date'=>$request->date]);
  }

  /**
   * Create a new transaction
   */
  public function singleTransaction(Request $request)
  {
    $user_id = $request->input('user_id');
    $product_id = $request->input('product_id');
    $amount = $request->input('amount');

    DB::beginTransaction();
    $products = DB::select('SELECT * FROM products WHERE id = ?', [$product_id]);
    $users = DB::select('SELECT * FROM users WHERE id = ?', [$user_id]);

    $description = $products[0]->unit;
    $mutation = number_format(-1 * $amount * $products[0]->price, 2);
    $saldo_before = $users[0]->balance;
    $saldo_after = number_format($saldo_before + $mutation, 2);

    $transaction_data = array('user_id'=>$user_id, "description"=>$description, "product_id"=>$product_id, "saldo_before"=>$saldo_before, "mutation"=>$mutation, "amount"=>$amount, "saldo_after"=>$saldo_after);

    DB::update('UPDATE users SET balance = ? WHERE id = ?', [$saldo_after, $user_id]);
    DB::table('transactions')->insert($transaction_data);
    DB::commit();

    return redirect()->back()->with('message', 'Bestelling geslaagd.');
  }
}
