@extends('layouts.master')

@section('content')
  <h2>WELCOME REDRABBIT SIGNUP</h2>
  {{ Form::Open([
      'url'=> '/signup',
      'method'=> 'POST',
      'name'=> 'signup-form',
      'id'=> 'signup-form'
    ])
  }}
  <div>
    {{ Form::label('first_name', 'First name') }}
    {{ Form::text('first_name', Input::old('first_name')), array('id'=>'first_name') }}
    @error('first_name')
      <div id="first-name-error" class="input-error">{{ $message }}</div>
    @enderror
  </div>
  <div>
    {{ Form::label('last_name', 'Last name') }}
    {{ Form::text('last_name', Input::old('last_name')), ['id'=> 'last_name']}}
    @error('last_name')
      <div id="last-name-error" class="input-error">{{$message}}</div>
    @enderror
  </div> 
  <div>
    {{ Form::label('email', 'Email') }}
    {{ Form::email('email', Input::old('email')), ['id'=>'email'] }}
    @error('email')
      <div id="email-error" class="input-error">{{$message}}</div>
    @enderror
  <div>
  <div>
    {{ Form::label('password', 'Password') }}
    {{ Form::password('password'), ['id'=>'password'] }}
    @error('password')
      <div id="password-error" class="input-error">{{ $message }}</div>
    @enderror
  </div>
  <div>
    {{ Form::label('password2', 'Re-enter password') }}
    {{ Form::password('password2'), ['id'=>'password2'] }}
    @error('password2')
      <div id="password-error" class="input-error">{{ $message }}</div>
    @enderror
  </div>
  <div>
    <button type="submit" id="btn-submit">Submit</button>
  </div> 

  <script type="text/javascript" src="http://localhost:80/js/redrabbit-form.js"></script>
  <script type="text/javascript">
  var RRF = new RedRabbitForm({
    errors: <?=(!empty($signupResponseData) ? json_encode($signupResponseData) :'{ }')?>,
    data:<?=(!empty($_POST) ? json_encode($_POST) : '[ ]')?>
  });
  RRF.showHideErrors();
  RRF.displayFormValues();
  </script>
@stop