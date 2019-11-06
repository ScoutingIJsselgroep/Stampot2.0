<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Mail\Betalingsherinnering;

use Mail;

class UserController extends Controller
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
     * Shows a list with current users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $query = $request->input('query');

        if ($query !== '') {
          $users = DB::table('users')
            ->orderBy('name', 'asc')
            ->where('name', 'like', '%'.$query.'%')
            ->paginate(15, ['*'], 'users');
        } else {
          $users = DB::table('users')
            ->orderBy('name', 'asc')
            ->paginate(15, ['*'], 'users');
        }

        return view('users', ['users' => $users, 'query' => $query]);
    }

    public function invoice(Request $request)
    {

      $users = DB::table('users')->where('balance', '<', 0)->get();
      foreach ($users as $user) {
        if (isset($user->email)) {
          $data = array('name'=>$user->name, 'balance'=>$user->balance);

          Mail::to($user->email)->send(new Betalingsherinnering($data));
        }
      }
      
      return redirect()->back()->with('message', 'Betalingsherinnering gestuurd.');
    }

}
