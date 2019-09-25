<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $current_user = DB::table('users')->where('id', \Auth::user()->id)->first();
        $users = DB::table('users')->paginate(15, ['*'], 'users');

        $products = DB::table('products')->where('deleted', '=', 0)->paginate(3, ['*'], 'products');

        $num_transactions = DB::table('transactions')->where('user_id', \Auth::user()->id)->where('product_id', '!=', 0)->sum('amount');
        $num_this_year = DB::table('transactions')->where('user_id', \Auth::user()->id)->where('product_id', '!=', 0)->whereYear('transaction_created_at', date("Y"))->sum('amount');
        $amount_money = number_format(-1*DB::table('transactions')->where([['product_id', '!=', 0], ['user_id', \Auth::user()->id], ['mutation', '<', 0.0]])->sum('mutation'), 2);

        $transactions = DB::table('transactions')->where('user_id', \Auth::user()->id)->orderBy('transaction_created_at', 'desc')->join('users', 'users.id', '=', 'transactions.user_id')->paginate(5, ['*'], 'transactions');

        return view('home', ['users' => $users, 'products'=>$products, 'num_this_year'=>$num_this_year, 'amount_money'=>$amount_money, 'transactions'=>$transactions, 'current_user' => $current_user, 'num_transactions'=>$num_transactions]);
    }

    /**
     * Add a new user.
     */
    public function insert(Request $request)
    {
        // Save icon
        $user_icon = $request->file('user_icon');
        $extension = $user_icon->getClientOriginalExtension();
        Storage::disk('public')->put($user_icon->getFilename().'.'.$extension,  File::get($user_icon));

        $filename = $user_icon->getFilename().'.'.$extension;
        $name = $request->input('name');
        $email = $request->input('email');
        $data = array('name'=>$name, "email"=>$email, "password"=>"", "user_icon"=>$filename);
        DB::table('users')->insert($data);

        return redirect()->route('home');
    }
}
