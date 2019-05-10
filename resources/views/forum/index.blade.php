@extends('layouts.master')

@section('title', 'THIS IS THE FORUM TITLE ...')

@section('content')
  
<script type="text/javascript" src="http://localhost:80/public/js/Forum.bundle.js" charset="UTF-8"></script>
    <h2>Welome to REDRABBIT FORUM [index.blade]</h2>
    <p>...</p>
    [Time: {{ time() }}]
    <p>.................</p>
    <div class="forum-create-post"><a href="/forum/create">Create Post</a></div>
    {!! $forumOutput !!}

    
    <script type="text/javascript">
      window.forum.data = <?=(!empty($forumData) ? json_encode($forumData):"new Object()")?>;
    </script>

  @stop
