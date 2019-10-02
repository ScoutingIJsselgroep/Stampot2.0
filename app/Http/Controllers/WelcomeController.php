<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class WelcomeController extends Controller
{
  public function index()
  {
      return view('welcome', []);
  }
}
