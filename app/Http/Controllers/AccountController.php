<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Redrabbit;

class AccountController extends Controller
{
  public function index(Request $request)
  {
    if(empty($request->cookie('email')))
      return $this->loginRedirect($request);
    else
      return view('account/index');
  }

  public function loginRedirect(Request $request)
  {
    return Redirect::to('/login');
  }
}
