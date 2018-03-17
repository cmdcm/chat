<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">

    <title>Chat</title>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('themes/animate.css') }}"/>
    @yield('themes')
  </head>
  
  <body>
     
     @yield('content')

     <script type="text/javascript" src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
     
     @yield('scripts')
  
  </body>
</html>