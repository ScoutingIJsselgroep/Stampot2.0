<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class StatsController extends Controller
{
  public function index()
  {
      return view('welcome', []);
  }

  public function horserace()
  {
    $transaction_details = DB::table('transactions')
          ->select('user_id', 'users.name AS user_name', DB::raw('SUM(amount) AS amount'))
          ->join('users', 'transactions.user_id', '=', 'users.id')
          ->join('products', 'transactions.product_id', '=', 'products.id')
          ->orderBy('amount', 'desc')
          ->whereYear('transaction_created_at', '=', date('Y'))
          ->whereMonth('transaction_created_at', '=', date('m'))
          ->groupBy('user_id', 'users.name', 'amount', DB::raw('MONTH(transaction_created_at)'))
          ->paginate(5, ['*'], 'transaction_page');
    return view('horserace', ['transaction_details'=>$transaction_details]);
  }

  public function totals()
  {
    $transaction_details = DB::table('transactions')
          ->select('user_id', 'users.name AS user_name', DB::raw('SUM(amount) AS amount'))
          ->join('users', 'transactions.user_id', '=', 'users.id')
          ->join('products', 'transactions.product_id', '=', 'products.id')
          ->orderBy('amount', 'desc')
          ->groupBy('user_id', 'users.name', 'amount', DB::raw('MONTH(transaction_created_at)'))
    return view('horserace', ['transaction_details'=>$transaction_details]);
  }
}
