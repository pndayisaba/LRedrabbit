<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cookie;
use App\Redrabbit;
/**
 * User Login
 */
class LoginController extends Controller
{
  private $loginData = [ ];
  public function index(Request $request) 
  {
    if($this->accountRedirect($request))
      return $this->accountRedirect($request);
    else 
      return view('login/create');
  }

  public function create() 
  {
    if($this->accountRedirect($request))
      return $this->accountRedirect($request);
    else
      return view('login/create ');
  }
  public function store(Request $request) 
  {
    if($this->accountRedirect($request))
      return $this->accountRedirect($request);

    $rules = [
      'email'=> 'required',
      'password'=> 'required'
    ];
    $validator = $request->validate($rules);

    $userInfo = Redrabbit::userLogin(Input::get('email'), Input::get('password'));
    if(!empty($userInfo) && !empty($userInfo[0]->email))
    {
      Cookie::queue('email', $userInfo[0]->email, time() + ((60 * 60) * 5));
      return $this->accountRedirect($request, true);
    }
    else
      return view('login/create');
  }

  public function accountRedirect(Request $request, bool $forceRedirect = false)
  {
    return $forceRedirect || $request->cookie('email')
      ? Redirect::to('account')
      : null;
  }
}
