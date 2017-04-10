<!DOCTYPE html>
<html>
  <head>
    <!-- Required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <!-- Color theme for statusbar -->
    <meta name="theme-color" content="#2196f3">
    <!-- Your app title -->
    <title>Agence Interativa</title>
    <!-- Path to Framework7 Library CSS, Material Theme -->
    <link href="{{ asset('/css/framework7.material.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/framework7.material.colors.min.css') }}" rel="stylesheet">
    <!-- Path to your custom app styles-->
    <link href="{{ asset('/css/my-app.css') }}" rel="stylesheet">
    @stack('css')
  </head>
  <body>
    @include('elements.panel-left')
    @include('elements.navbar-links')
    <span id="progress-bar" class="progressbar-infinite-overlay"></span>
    <!-- Views -->
    <div class="views">
      <!-- Your main view, should have "view-main" class -->
      <div class="view view-main">
        <!-- Pages container, because we use fixed navbar and toolbar, it has additional appropriate classes-->
        <div class="pages navbar-fixed">
          <!-- Page, "data-page" contains page name -->
          @yield('content')
        </div>
      </div>
    </div>
    <!-- Path to Framework7 Library JS-->
    <script type="text/javascript" src="{{ asset('/js/framework7.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('/js/my-app.js')}}"></script>
    @stack('js')
  </body>
</html> 