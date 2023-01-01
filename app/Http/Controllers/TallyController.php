<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TallyController extends Controller
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

      $transactions = DB::select('SELECT * FROM transactions', []);
      $products = DB::select('SELECT * FROM products WHERE deleted = 0', []);
      $query = $request->input('query');
      if ($query !== ''){
        $users = DB::table('users')
                ->where('name', 'like', '%'.$query.'%')
                ->paginate(20, ['*'], 'users');
      }
      else {
        $users = DB::table('users')->paginate(20, ['*'], 'users');
      }

      return view('tally', ['transactions' => $transactions, 'products' => $products, 'users' => $users, 'query' => $query]);
  }

  public function rows(Request $request)
  {
      $query = $request->input('query');

      // Subquery Joins
      $latestTransactions = DB::table('transactions')
                            ->select('user_id', 'product_id', DB::raw('products.name AS product_name'), DB::raw('products.filename AS product_icon'), DB::raw('SUM(amount) AS sum_amount'))
                            ->groupBy('user_id', 'product_id', 'products.name', 'products.filename')
                            ->join('products', 'products.id', '=', 'transactions.product_id')
                            ->where('transaction_created_at', '>=', DB::raw('NOW() - INTERVAL 16 HOUR'))
                            ->where('products.deleted', '=', 0);

      $nonLatestTransactions = DB::table('transactions')
                            ->select('user_id', DB::raw('SUM(amount) AS sum_amount'), DB::raw('MAX(transaction_created_at) AS transaction_created_at'))
                            ->groupBy('user_id');

      $consuming_users = DB::table('users')
                ->joinSub($latestTransactions, 'latest_transactions', function ($join) {
                      $join->on('users.id', '=', 'latest_transactions.user_id');
                  })
                ->orderBy('name', 'asc')
                ->paginate(20, ['*'], 'consuming_users');

      if ($query !== '') {
        $idle_users = DB::table('users')
                  ->joinSub($nonLatestTransactions, 'latest_transactions', function ($join) {
                        $join->on('users.id', '=', 'latest_transactions.user_id');
                    })
                  ->where('name', 'like', '%'.$query.'%')
                  ->latest('transaction_created_at')
                  ->paginate(10, ['*'], 'idle_users');
      } else {
        $idle_users = DB::table('users')
                  ->joinSub($nonLatestTransactions, 'latest_transactions', function ($join) {
                        $join->on('users.id', '=', 'latest_transactions.user_id');
                    })
                  ->latest('transaction_created_at')
                  ->paginate(10, ['*'], 'idle_users');
      }

      $products = DB::select('SELECT * FROM products WHERE deleted = 0', []);

      return view('tally_rows', ['consuming_users' => $consuming_users, 'idle_users'=>$idle_users, 'products' => $products, 'query'=>$query]);
  }
}
