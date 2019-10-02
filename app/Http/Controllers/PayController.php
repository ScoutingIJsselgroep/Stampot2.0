<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayController extends Controller
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

    public function index()
    {
        $users = DB::table('users')->get();


        $transactions = DB::table('transactions')
              ->select('users.id AS user_id', 'users.name AS user_name', 'description', 'mutation')
              ->orderBy('transactions.transaction_created_at', 'desc')
              ->join('users', 'transactions.user_id', '=', 'users.id')
              ->where('product_id', '=', 0)
              ->paginate(10, ['*'], 'transaction_page');

        return view('pay', ['users'=>$users, 'transactions'=>$transactions]);
    }

    public function insert(Request $request)
    {
      $user_ids = $request->input('user_id');
      $amount = $request->input('amount');
      $description = $request->input('description');

      $users_names = [];

      foreach ($user_ids as $user_id) {
        DB::beginTransaction();
        // $products = DB::select('SELECT * FROM products WHERE id = ?', [$product_id]);
        $users = DB::select('SELECT * FROM users WHERE id = ?', [$user_id]);
        array_push($users_names, $users[0]->name);
        $mutation = number_format($amount, 2);
        $saldo_before = $users[0]->balance;
        $saldo_after = number_format($saldo_before + $mutation, 2);

        $transaction_data = array('user_id'=>$user_id, "description"=>$description, "product_id"=>0, "saldo_before"=>$saldo_before, "mutation"=>$mutation, "saldo_after"=>$saldo_after);

        DB::update('UPDATE users SET balance = ? WHERE id = ?', [$saldo_after, $user_id]);
        DB::table('transactions')->insert($transaction_data);
        DB::commit();
      }

      if ($request->amount < 0){
        return redirect()->back()->with('message', implode(", ", $users_names). ' afgeschreven met € '.number_format($request->amount, 2).'.');
      } else {
        return redirect()->back()->with('message', implode(", ", $users_names). ' opgewaardeerd met € '.number_format($request->amount, 2).'.');
      }
    }
}
