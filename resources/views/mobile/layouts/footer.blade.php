<div class="footer-nav-area" id="footerNav">
  <div class="container-fluid h-100 px-0">
    <div class="suha-footer-nav h-100">
      <ul class="h-100 d-flex align-items-center justify-content-between ps-0">
        <li><a href="{{ route('index') }}"><i class="fa fa-home"></i>Beranda</a></li>        
        @yield('bawah')
        <li><a href="{{ route('profil') }}"><i class="fa fa-user"></i>Profil</a></li>
        <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="lni lni-exit"></i>Keluar</a></li>
      </ul>
    </div>
  </div>
</div>