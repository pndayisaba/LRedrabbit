@extends('layouts.master')
@section('content')
  <h2>WELCOME REDRABBIT LOGIN [INDEX.BLADE]</h2>
  <!-- Show errors here... -->
  {{ Html::ul($errors->all()) }}

  {{ 
    Form::open(
      array(
        'url'=> 'login',
        'class'=>'login-form',
        'name'=>'login-form',
        'id'=>'login-form'
      )
    ) 
  }}
  <div>
    <div>
      {{ Form::label('email', 'Email') }}
      {{ Form::text('email', Input::old('email')), array('id'=>'email') }}
      @error('email')
        <div class="email-error error"> {{ $message }} </div>
      @enderror
    </div>

    <div>
      {{ Form::label('password', 'Password') }}
      {{ Form::password('password', Input::old('password'), ['id'=>'password']) }}
      @error('password')
        <div class="password-error error">{{ $message }} </div>
      @enderror
    </div>

    <div><button type="submit" id="btn-submit">Submit</submit>
  </div>
  <!--<form id="login-form" action="<?=$_SERVER['REQUEST_URI']?>" method="POST">
    <div><label for="email">Email</label><input type="text" name="email" id="email" /></div>
    <div><label for="password">Password</label><input type="password" name="password" id="password" /></div>
    <div><button type="submit" id="btn-submit">Submit</submit>
  </form>-->

@stop
