@extends('layouts.master')
@section('content')
  <h2>Welcome Forum Discussions</div>
  <p>Ask Questions, or help other people by answering their questions</p>
  <form name="forum-form" class="forum-form-laravel" id="forum-form" method="POST" action="/forum">
    @csrf
     
    <div id="unkown-error" class="input-error"></div>
    <div>
      <label for="title">Title</label>
      <input type="text" name="title" id="title" />
      @error('title')
        <div id="title-error" class="input-error">{{ $message }}</div>
      @enderror
    </div>
    <div>
      <label for="description">Description</label>
      <textarea name="description" id="description"></textarea>
      @error('description')
        <div id="description-error" class="input-error">{{ $message }}</div>
      @enderror
    </div>
    <div><button type="submit" id="btn-forum-submit">Submit</button></div>
  </form>
  
  <script type="text/javascript" src="http://localhost:80/js/redrabbit-form.js"></script>
  <script type="text/javascript">
    var RRF = new RedRabbitForm({
        errors: <?=(!empty($errors) ? json_encode($errors):'[ ]')?>,
        data: <?=(!empty($inputData) ? json_encode($inputData) : 'new Object()')?>
    });
    RRF.showHideErrors();
    RRF.displayFormValues();  
  </script>

@stop

