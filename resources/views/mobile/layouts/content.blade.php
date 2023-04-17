<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover, shrink-to-fit=no">
  <meta name="description" content="Suha - Multipurpose Ecommerce Mobile HTML Template">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="theme-color" content="#100DD1">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <title>RSUD Genteng</title>
  <link rel="stylesheet" href="/mobile/fonts/font.css">
  <link rel="stylesheet" href="/mobile/css/bootstrap.min.css">
  <link rel="stylesheet" href="/mobile/css/animate.css">
  <link rel="stylesheet" href="/mobile/css/owl.carousel.min.css">
  <link rel="stylesheet" href="/mobile/css/font-awesome.min.css">
  <link rel="stylesheet" href="/mobile/css/default/lineicons.min.css">
  <link rel="stylesheet" type="text/css" href="/datatables/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="/datatables/fixedHeader.bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="/datatables/fixedColumns.bootstrap.min.css">
  <link rel="stylesheet" href="/mobile/style.css">
  <link rel="stylesheet" href="/bootstrap/css/toastr.min.css">
  @yield('style')
</head>
<body>
  <div class="preloader" id="preloader">
    <div class="spinner-grow text-secondary" role="status">
      <div class="sr-only">Loading...</div>
    </div>
  </div>

  <div class="header-area" id="headerArea">
    <div class="container-fluid h-100 d-flex align-items-center justify-content-between">
      <div class="logo-wrapper">
        <a href="{{ route('index') }}">
          <img src="/images/web_logo.png" width="60%">
        </a>
      </div>
      <div class="suha-navbar-toggler d-flex flex-wrap" id="suhaNavbarToggler"><span></span><span></span><span></span></div>
    </div>
  </div>

  <div class="sidenav-black-overlay"></div>

  @include('mobile.layouts.menu')
  @yield('content')

  <div class="internet-connection-status" id="internetStatus"></div>

  @include('mobile.layouts.footer')

  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
  @csrf
  </form>

  <script src="/mobile/js/bootstrap.bundle.min.js"></script>
  <script src="/mobile/js/jquery.min.js"></script>  
  <script src="/mobile/js/waypoints.min.js"></script>
  <script src="/mobile/js/jquery.easing.min.js"></script>
  <script src="/mobile/js/owl.carousel.min.js"></script>
  <script src="/mobile/js/jquery.counterup.min.js"></script>
  <script src="/mobile/js/jquery.countdown.min.js"></script>
  <script src="/mobile/js/default/jquery.passwordstrength.js"></script>
  <script src="/mobile/js/default/dark-mode-switch.js"></script>
  <script src="/mobile/js/default/no-internet.js"></script>
  <script src="/mobile/js/default/active.js"></script>
  <script src="/mobile/js/pwa.js"></script>
  <script src="/datatables/js/jquery.dataTables.js"></script>  
  <script src="/datatables/dataTables.fixedHeader.min.js"></script>  
  <script src="/datatables/dataTables.fixedColumns.min.js"></script>  
  <script src="/bootstrap/js/autoNumeric.min.js"></script>
  <script src="/bootstrap/js/toastr.min.js"></script>  
  @yield('script')

  <script type="text/javascript">
    (function(){
      $('.fprev').on('submit', function() {
        $('.bprev').attr('disabled','true');
      })
    })();
  </script>  

  <script>
    new AutoNumeric.multiple('.nominal');
  </script>

  <script>
    function goBack() {
      window.history.back();
    }
  </script>  

  {!! Toastr::message() !!} 
</body>
</html>