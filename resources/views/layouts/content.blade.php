<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>RSUD Genteng - @yield('title')</title>
  <link rel="shortcut icon" type="image/x-icon" href="/images/fav.png">
  <link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap-responsive.css">  
  <link rel="stylesheet" type="text/css" href="/bootstrap/css/toastr.min.css">
  <link rel="stylesheet" type="text/css" href="/datatables/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="/datatables/fixedHeader.bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="/datatables/fixedColumns.bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="/bootstrap/select2/css/select2.min.css">
  <link rel="stylesheet" type="text/css" href="/font_awesome/css/all.min.css">

  <script src="/bootstrap/js/autoNumeric.js"></script>

  @yield('style')
</head>
<body>
  <div class="header">
    <div class="header-image">
      <img src="/images/header.jpg" class="header-img">
    </div>
    <div class="header-user">
      <a href="{{ route('profil') }}" style="display: inline-flex;">
      <div style="margin-right: 15px;">
        <label style="font-weight: bold; font-size: 2.5vh; margin-top: 10px; text-align: right; color: white; text-shadow: 2px 2px 8px #000000; margin-bottom: 0.15vh;">
          @if(Auth::user()->gelar_depan)
            {{ Auth::user()->gelar_depan }}
          @endif

          @if( Auth::user()->gelar_belakang )
            {{ strtoupper(Auth::user()->nama) }}, {{ Auth::user()->gelar_belakang }}
          @else
            {{ strtoupper(Auth::user()->nama) }}
          @endif
        </label>
        <label style="font-size: 1.65vh; font-weight: bold; text-align: right; color: #ff0000;">
          @if($c_akses->id == 2)
            Ruang : {{ strtoupper($c_ruang->ruang) }}
          @else
            Hak Akses : {{ strtoupper($c_akses->akses) }}
          @endif
        </label>
      </div>
      @if(Auth::user()->foto)
        <img src="/{{ Auth::user()->foto }}" style="border-radius: 40px; height: 9vh; box-shadow: 0 0 15px 2px #ffffff;">
      @else
        <img src="/images/noimage.jpg" style="border-radius: 40px; height: 9vh; box-shadow: 0 0 15px 2px #ffffff;">
      @endif
      </a>
    </div>
  </div>

  <a href="{{ route('beranda') }}">
    <img src="/images/web_logo.png" class="header-logo">
  </a>

  <div class="row-fluid" style="margin-top: 10px;">
    <div class="span2 menu" id="menu-utama">
      @include('layouts.menu')
    </div>

    <div class="span10" style="margin-left: 20px;">
      @yield('content')
    </div>
  </div>

  <footer class="footer">
    ©
    <script language="JavaScript" type="text/javascript">
      now = new Date
      theYear=now.getYear()
      if (theYear < 1900)
        theYear=theYear+1900
      document.write(theYear)
    </script>
    RSUD Genteng Banyuwangi.
  </footer>

  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
  @csrf
  </form>

  <script src="/bootstrap/js/jquery-3.6.0.min.js"></script>
  <script src="/bootstrap/js/bootstrap-transition.js"></script>
  <script src="/bootstrap/js/bootstrap-alert.js"></script>
  <script src="/bootstrap/js/bootstrap-modal.js"></script>
  <script src="/bootstrap/js/bootstrap-dropdown.js"></script>
  <script src="/bootstrap/js/bootstrap-scrollspy.js"></script>
  <script src="/bootstrap/js/bootstrap-tab.js"></script>
  <script src="/bootstrap/js/bootstrap-tooltip.js"></script>
  <script src="/bootstrap/js/bootstrap-popover.js"></script>
  <script src="/bootstrap/js/bootstrap-button.js"></script>
  <script src="/bootstrap/js/bootstrap-collapse.js"></script>
  <script src="/bootstrap/js/bootstrap-carousel.js"></script>
  <script src="/bootstrap/js/bootstrap-typeahead.js"></script>
  <script src="/bootstrap/js/bootstrap-affix.js"></script>

  <script src="/bootstrap/js/jquery.table.marge.js"></script>    
  <script src="/bootstrap/js/toastr.min.js"></script>  
  <script src="/datatables/js/jquery.dataTables.js"></script>  
  <script src="/datatables/dataTables.fixedHeader.min.js"></script>  
  <script src="/datatables/dataTables.fixedColumns.min.js"></script>  
  <script src="/bootstrap/select2/js/select2.min.js"></script>
  
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

  <script>
    var status = localStorage.getItem("status");
    var elHref = localStorage.getItem("elementsHref")
    $("a[href$='"+elHref+"']").addClass(status);

    $(document).ready(function($){
      $(".btn-menu").bind('click', function () {
        localStorage.setItem("status", "active");
        localStorage.setItem("elementsHref", $(this).attr("href"))
        $(".btn-menu").removeClass(localStorage.getItem("status"));
        $(this).addClass(localStorage.getItem("status"));
      });
    });
  </script>  

  <script type="text/javascript">
    function show_my_receipt() {
      var page = "";
      var myWindow = window.open(page, "_blank", "scrollbars=yes,width=400,height=500,top=300");
         
      myWindow.focus();
      setTimeout(function() {
        myWindow.close();
      }, 10000);        
    }
  </script>  

  {!! Toastr::message() !!} 
</body>
</html>
