@extends('layouts.master')
@section('content')
  <h2>WELCOME REDRABBIT LOGIN [STORE.BLADE]</h2>
  <form id="login-form" action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
    <div><label for="email">Email</label><input type="text" name="email" id="email" /></div>
    <div><label for="password">Password</label><input type="password" name="password" id="password" /></div>
    <div><button type="submit" id="btn-submit">Submit</submit>
  </form>

@stop
