<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Collective\Html\FormFacade;
use Collective\Html\HtmlFacade;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SignupValidate;


class SignupController extends Controller
{
  public function index(): String
  {
    return view('signup/index');
  }

  public function store(SignupValidate $request)
  {
    // SignupValidate returned no errors;
    
    //$validated = $request->validated();
    return view('signup/thank-you');
  }
}
