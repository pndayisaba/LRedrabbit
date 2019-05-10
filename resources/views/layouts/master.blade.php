<?php
//header('Content-Type: application/json');
$externalHost = 'http://localhost:80';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Red Rabbit - [@yield('title')]</title>
    <link href="<?=$externalHost?>/css/style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ url('/css/style.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
  </head>
  <body>
    <div id="wrapper">

      <nav id="nav-wrap">
        <ul id="navigation" class="container">
          <li><a href="/">Home</a></li>
          <li><a href="/about">About</a></li>
          <li><a href="/forum">Forum</a></li>
          <li><a href="/signup">Signup</a></li>
          <li><a href="/login">Login</a></li>
        </ul>
      </nav>
    
      <div class="content">
        @yield('content')
        <p>[This is the body ... ]</p>
      </div>

      <footer id="footer">
        <ul id="navigation" class="container">
          <li><a href="/">Home</a></li>
          <li><a href="/about">About</a></li>
          <li><a href="/forum">Forum</a></li>
        </ul>
      </footer>  
      
    </div>
  </body>
</html>
<!--
  {
    "servername": "localhost",
    "dbname": "redrabbit",
    "username": "webuser",
    "password": "!@webuser100"
  }
-->
