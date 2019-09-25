<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
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
        $products = DB::table('products')->where('deleted', '=', 0)->paginate(15, ['*'], 'products');
        return view('products', ['products' => $products]);
    }

    /**
     * Create a new product
     */
    public function insert(Request $request)
    {
      request()->validate([
        'name' => 'required',
        'unit' => 'required',
        'price' => 'required',
        'image' => 'required',
      ]);

      // Save icon
      $image = $request->file('image');
      $extension = $image->getClientOriginalExtension();
      Storage::disk('public')->put($image->getFilename().'.'.$extension,  File::get($image));

      $filename = $image->getFilename().'.'.$extension;
      $name = $request->input('name');
      $unit = $request->input('unit');
      $price = $request->input('price');
      $data=array('name'=>$name,'unit'=>$unit,"price"=>$price,"deleted"=>0, "filename"=>$filename);

      DB::table('products')->insert($data);

      return redirect()->back()->with('message', 'Product <code>'.$name.'</code> toegevoegd.');
    }

    /**
     * Delete a product
     */
    public function delete(Request $request)
    {
      $affected = DB::update('UPDATE products SET deleted = 1 WHERE id = ?', [$request->id]);
      return redirect()->route('products');
    }
}
