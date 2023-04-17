<div class="suha-sidenav-wrapper" id="sidenavWrapper">
  <div class="sidenav-profile">
    <div class="user-profile">
      @if(Auth::user()->foto)
        <img src="/{{ Auth::user()->foto }}">
      @else
        <img src="/images/noimage.jpg">
      @endif
    </div>
    <div class="user-info">
      <label style="font-size: 3.5vw;">
        @if(Auth::user()->gelar_depan)
          {{ Auth::user()->gelar_depan }}
        @endif

        @if(Auth::user()->gelar_belakang)
          {{ strtoupper(Auth::user()->nama) }}, {{ Auth::user()->gelar_belakang }}
        @else
          {{ strtoupper(Auth::user()->nama) }}
        @endif            
      </label>
      <p style="font-size: 3vw;">
        @if($c_akses->id == 2)
            RUANG {{ strtoupper($c_ruang->ruang) }}
          @else
            {{ strtoupper($c_akses->akses) }}
          @endif
      </p>
    </div>
  </div>

  <ul class="sidenav-nav ps-0">
    <li><a href="{{ route('index') }}">Beranda</a></li>

    @if(Auth::user()->id_akses == 1)
    <li class="suha-dropdown-menu"><a href="#">Parameter</a>
      <ul>
        <li><a href="{{ route('parameter') }}">- Rumah Sakit</a></li>
        <li><a href="{{ route('parameter_software') }}">- Remunerasi</a></li>
      </ul>
    </li>
    <li class="suha-dropdown-menu"><a href="#">Variabel Data</a>
      <ul>
        <li><a href="{{ route('bank') }}">- Bank</a></li>
        <li><a href="{{ route('ruang') }}">- Ruang</a></li>
        <li><a href="{{ route('rekening_layanan') }}">- Rekening Tarif</a></li>
        <li><a href="{{ route('bagian_tenaga') }}">- Jenis Tenaga</a></li>
        <li><a href="{{ route('bagian') }}">- Bagian</a></li>
        <li><a href="{{ route('jasa_layanan') }}">- Jasa</a></li>
        <li><a href="{{ route('kategori_layanan') }}">- Kategori Layanan</a></li>
        <li><a href="{{ route('jenis_pasien') }}">- Jenis Pasien</a></li>
        <li><a href="{{ route('absensi') }}">- Absensi</a></li>
      </ul>
    </li>
    <li class="suha-dropdown-menu"><a href="#">Data Karyawan</a>
      <ul>
        <li><a href="{{ route('karyawan') }}">- Karyawan</a></li>
        <li><a href="{{ route('karyawan_indeks') }}">- Indeks Karyawan</a></li>
        <li><a href="{{ route('karyawan_jasa') }}">- Jasa Karyawan</a></li>
        <li><a href="{{ route('karyawan_gapok') }}">- Gapok, TPP & Pajak</a></li>
      </ul>
    </li>
    <li><a href="{{ route('jasa_remun') }}">Indeks Masa Kerja</a></li>
    <li class="suha-dropdown-menu"><a href="#">Claim Asuransi</a>
      <ul>
        <li><a href="{{ route('bank') }}">- Hitung Claim</a></li>
        <li><a href="{{ route('bank') }}">- Data Claim</a></li>
      </ul>
    </li>
    <li class="suha-dropdown-menu"><a href="#">Remunerasi</a>
      <ul>
        <li><a href="{{ route('bank') }}">- Hitung Remunerasi</a></li>
        <li><a href="{{ route('bank') }}">- Data Remunerasi</a></li>
      </ul>
    </li>
    <li><a href="{{ route('jasa_remun') }}">Skema Tarif</a></li>
    @endif

    @if(Auth::user()->id_akses == 2 || Auth::user()->id_akses == 8)
      @if(Auth::user()->id_ruang <> 5 && Auth::user()->id_ruang <> 30 && Auth::user()->id_ruang <> 31 && Auth::user()->id_ruang <> 33 && Auth::user()->id_ruang <> 29 && Auth::user()->id_ruang <> 46 && Auth::user()->id_ruang <> 47 && Auth::user()->id_ruang <> 62 && Auth::user()->id_ruang <> 72 && Auth::user()->id_ruang <> 52)
        <li><a href="{{ route('pasien_ruang') }}">Layanan</a></li>
        <li><a href="{{ route('pasien_ruang_data') }}">Data Layanan</a></li>
      @endif

      @if(Auth::user()->id_ruang == 5)
        <li><a href="{{ route('pasien_operasi') }}">Layanan</a></li>
        <li><a href="{{ route('pasien_operasi_transaksi') }}">Data Layanan</a></li>
      @endif

      @if(Auth::user()->id_ruang == 30)
        <li><a href="{{ route('pasien_apotik') }}"></i>Layanan</a></li>
        <li><a href="{{ route('pasien_apotik_transaksi') }}">Data Layanan</a></li>
      @endif

      @if(Auth::user()->id_ruang == 31)
        <li><a href="{{ route('pasien_gizi') }}">Layanan</a></li>
        <li><a href="{{ route('pasien_gizi_transaksi') }}">Data Layanan</a></li>
      @endif

      @if(Auth::user()->id_ruang == 33)
        <li><a href="{{ route('pasien_upp') }}">Pembayaran</a></li>
        <li><a href="{{ route('pasien_upp_data') }}">Data Pembayaran</a></li>
      @endif

      @if(Auth::user()->id_ruang == 29 || Auth::user()->id_ruang == 46 || Auth::user()->id_ruang == 47 || Auth::user()->id_ruang == 62 || Auth::user()->id_ruang == 72)
        <li><a href="{{ route('pasien_laborat') }}">Layanan</a></li>
        <li><a href="{{ route('pasien_laborat_transaksi') }}">Data Layanan</a></li>
      @endif  

      @if(Auth::user()->id_ruang == 52)
        <li><a href="{{ route('pasien_laborat') }}">Layanan</a></li>
        <li><a href="{{ route('pasien_jenasah_transaksi') }}">Data Layanan</a></li>
      @endif  

      @if($a_param->dasar_remun == 2)
      <li><a href="{{ route('karyawan_ruang') }}">Absensi Karyawan</a></li>

      @if($a_param->histori == 1)
      <li><a href="{{ route('karyawan_histori_update') }}">Update History Karyawan</a></li>
      @endif
      @endif
    @endif

    <li class="suha-dropdown-menu"><a href="#">Data Pasien</a>
      <ul>
        <li><a href="#">- Data Pasien</a></li>
        <li><a href="#">- Pasien Keluar</a></li>
        <li><a href="#">- Layanan Pasien</a></li>
        <li><a href="#">- Statistik Pasien</a></li>
      </ul>
    </li>
    <li><a href="{{ route('jasa_remun') }}">Jasa Remunerasi</a></li>
    <li><a href="{{ route('profil') }}">Profil</a></li>
    <li><a href="{{ route('informasi_software') }}">Informasi</a></li>
    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Keluar</a></li>
  </ul>
  <div class="go-home-btn" id="goHomeBtn"><i class="lni lni-arrow-left"></i></div>
</div>