<a href="{{ route('beranda') }}" class="btn btn-warning btn-block btn-menu">Beranda</a>
<hr class="menu-divider">

@if(Auth::user()->cuti == 0)
@if(Auth::user()->id_akses == 1)
  <a href="{{ route('parameter') }}" class="btn btn-warning btn-block btn-menu">Parameter</a>
  <a href="{{ route('bank') }}" class="btn btn-warning btn-block btn-menu">Variabel Data</a>
  <!--<a href="{{ route('database') }}" class="btn btn-warning btn-block btn-menu">Database</a>-->
  <hr class="menu-divider">
  <a href="{{ route('karyawan') }}" class="btn btn-warning btn-block btn-menu">Data Karyawan</a>
  <a href="{{ route('karyawan_indeks') }}" class="btn btn-warning btn-block btn-menu">Indeks Karyawan</a>
  <a href="{{ route('karyawan_jasa') }}" class="btn btn-warning btn-block btn-menu">Jasa Karyawan</a>
  <a href="{{ route('karyawan_gapok') }}" class="btn btn-warning btn-block btn-menu">Gapok, TPP, Pajak</a>
  @if($a_param->dasar_remun == 2)
  <a href="{{ route('karyawan_cuti') }}" class="btn btn-warning btn-block btn-menu">Cuti Karyawan</a>
  <a href="{{ route('karyawan_absensi') }}" class="btn btn-warning btn-block btn-menu">Absensi Karyawan</a>
  <a href="{{ route('karyawan_histori_all') }}" class="btn btn-warning btn-block btn-menu">Edit History Karyawan</a>
  @endif
  <!--<a href="{{ route('karyawan_histori_admin') }}" class="btn btn-warning btn-block btn-menu">Histori Karyawan</a>-->
  <hr class="menu-divider">  
  <a href="{{ route('rumusan_indeks') }}" class="btn btn-warning btn-block btn-menu">Indeks Masa Kerja</a>  
  <hr class="menu-divider">
  <a href="{{ route('bpjs_admin') }}" class="btn btn-warning btn-block btn-menu">Claim Asuransi</a>
  <a href="{{ route('bpjs_data') }}" class="btn btn-warning btn-block btn-menu">Data Claim Asuransi</a>
  <hr class="menu-divider">
  <a href="{{ route('remunerasi_admin') }}" class="btn btn-warning btn-block btn-menu">Remunerasi</a>
  <a href="{{ route('remunerasi_data') }}" class="btn btn-warning btn-block btn-menu">Data Remunerasi</a>
  <!--<a href="{{ route('remunerasi_backup') }}" class="btn btn-warning btn-block btn-menu">Backup Remunerasi</a>-->
  <hr class="menu-divider">
  <a href="{{ route('tarif_daftar') }}" class="btn btn-warning btn-block btn-menu">Skema Tarif</a>
  <!--<hr class="menu-divider">
  <a href="{{ route('pengumuman') }}" class="btn btn-warning btn-block btn-menu">Pengumuman</a>-->
  <hr class="menu-divider">
@endif

@if(Auth::user()->id_akses == 2 || Auth::user()->id_akses == 8)
  @if(Auth::user()->id_ruang <> 5 && Auth::user()->id_ruang <> 30 && Auth::user()->id_ruang <> 31 && Auth::user()->id_ruang <> 33 && Auth::user()->id_ruang <> 29 && Auth::user()->id_ruang <> 46 && Auth::user()->id_ruang <> 47 && Auth::user()->id_ruang <> 62 && Auth::user()->id_ruang <> 72 && Auth::user()->id_ruang <> 52)
    <a href="{{ route('pasien_ruang') }}" class="btn btn-warning btn-block btn-menu">Layanan</a>
    <a href="{{ route('pasien_ruang_data') }}" class="btn btn-warning btn-block btn-menu">Data Layanan</a>
  @endif

  @if(Auth::user()->id_ruang == 5)
    <a href="{{ route('pasien_operasi') }}" class="btn btn-warning btn-block btn-menu">Layanan</a>
    <a href="{{ route('pasien_operasi_transaksi') }}" class="btn btn-warning btn-block btn-menu">Data Layanan</a>
  @endif

  @if(Auth::user()->id_ruang == 30)
    <a href="{{ route('pasien_apotik') }}" class="btn btn-warning btn-block btn-menu"></i>Layanan</a>
    <a href="{{ route('pasien_apotik_transaksi') }}" class="btn btn-warning btn-block btn-menu">Data Layanan</a>
  @endif

  @if(Auth::user()->id_ruang == 31)
    <a href="{{ route('pasien_gizi') }}" class="btn btn-warning btn-block btn-menu">Layanan</a>
    <a href="{{ route('pasien_gizi_transaksi') }}" class="btn btn-warning btn-block btn-menu">Data Layanan</a>
  @endif

  @if(Auth::user()->id_ruang == 33)
    <a href="{{ route('pasien_upp') }}" class="btn btn-warning btn-block btn-menu">Pembayaran</a>
    <a href="{{ route('pasien_upp_data') }}" class="btn btn-warning btn-block btn-menu">Data Pembayaran</a>
  @endif

  @if(Auth::user()->id_ruang == 29 || Auth::user()->id_ruang == 46 || Auth::user()->id_ruang == 47 || Auth::user()->id_ruang == 62 || Auth::user()->id_ruang == 72)
    <a href="{{ route('pasien_laborat') }}" class="btn btn-warning btn-block btn-menu">Layanan</a>
    <a href="{{ route('pasien_laborat_transaksi') }}" class="btn btn-warning btn-block btn-menu">Data Layanan</a>
  @endif  

  @if(Auth::user()->id_ruang == 52)
    <a href="{{ route('pasien_laborat') }}" class="btn btn-warning btn-block btn-menu">Layanan</a>
    <a href="{{ route('pasien_jenasah_transaksi') }}" class="btn btn-warning btn-block btn-menu">Data Layanan</a>
  @endif  

  @if($a_param->dasar_remun == 2 && Auth::user()->id_akses == 8)
  <hr class="menu-divider">
  <a href="{{ route('karyawan_ruang') }}" class="btn btn-warning btn-block btn-menu">Absensi Karyawan</a>
  @endif

  @if($a_param->histori == 1)
  <a href="{{ route('karyawan_histori_update') }}" class="btn btn-warning btn-block btn-menu">Update History Karyawan</a>
  @else
  <a href="{{ route('karyawan_histori_data') }}" class="btn btn-warning btn-block btn-menu">Data History Karyawan</a>
  @endif

  <hr class="menu-divider">
@endif

<!--@if(Auth::user()->id_akses == 8)  
  <a href="{{ route('karyawan_ruang') }}" class="btn btn-warning btn-block btn-menu">Absensi Karyawan</a>
  <hr class="menu-divider">
@endif-->

<!--Keuangan-->
@if(Auth::user()->id_akses == 3)
  <a href="{{ route('tarif_daftar') }}" class="btn btn-warning btn-block btn-menu">Skema Tarif</a>
  <hr class="menu-divider">
  <a href="{{ route('bpjs') }}" class="btn btn-warning btn-block btn-menu">Claim Asuransi</a>
  <a href="{{ route('bpjs_data') }}" class="btn btn-warning btn-block btn-menu">Data Claim Asuransi</a>
  <hr class="menu-divider">
  <a href="{{ route('remunerasi') }}" class="btn btn-warning btn-block btn-menu">Remunerasi</a>
  <a href="{{ route('remunerasi_data') }}" class="btn btn-warning btn-block btn-menu">Data Remunerasi</a>
  <hr class="menu-divider">
@endif

<!--Kepegawaian-->
@if(Auth::user()->id_akses == 4)
  <a href="{{ route('karyawan') }}" class="btn btn-warning btn-block btn-menu">Data Karyawan</a>
  <a href="{{ route('karyawan_indeks') }}" class="btn btn-warning btn-block btn-menu">Indeks Karyawan</a>
  <a href="{{ route('karyawan_jasa') }}" class="btn btn-warning btn-block btn-menu">Jasa Karyawan</a>
  <a href="{{ route('karyawan_gapok') }}" class="btn btn-warning btn-block btn-menu">Gapok, TPP, Pajak</a>
  @if($a_param->dasar_remun == 2)
  <a href="{{ route('karyawan_cuti') }}" class="btn btn-warning btn-block btn-menu">Cuti Karyawan</a>
  <a href="{{ route('karyawan_absensi') }}" class="btn btn-warning btn-block btn-menu">Absensi Karyawan</a>
  @endif
  <!--<a href="{{ route('karyawan_histori_admin') }}" class="btn btn-warning btn-block btn-menu">Histori Karyawan</a>-->
  <hr class="menu-divider">
  <a href="{{ route('rumusan_indeks') }}" class="btn btn-warning btn-block btn-menu">Indeks Masa Kerja</a>  
  <hr class="menu-divider">
  <a href="{{ route('bpjs_data') }}" class="btn btn-warning btn-block btn-menu">Data Claim Asuransi</a>
  <a href="{{ route('remunerasi_data') }}" class="btn btn-warning btn-block btn-menu">Data Remunerasi</a>
  <hr class="menu-divider">
@endif

<!--Pelayanan-->
@if(Auth::user()->id_akses == 5)
  <a href="{{ route('bpjs_data') }}" class="btn btn-warning btn-block btn-menu">Data Claim Asuransi</a>
  <a href="{{ route('remunerasi_data') }}" class="btn btn-warning btn-block btn-menu">Data Remunerasi</a>
  <hr class="menu-divider">
@endif

<!--Olah Data-->
@if(Auth::user()->id_akses == 6)
  <a href="{{ route('karyawan') }}" class="btn btn-warning btn-block btn-menu">Data Karyawan</a>
  <a href="{{ route('karyawan_indeks') }}" class="btn btn-warning btn-block btn-menu">Indeks Karyawan</a>
  <a href="{{ route('karyawan_jasa') }}" class="btn btn-warning btn-block btn-menu">Jasa Karyawan</a>
  <a href="{{ route('karyawan_gapok') }}" class="btn btn-warning btn-block btn-menu">Gapok, TPP, Pajak</a>
  @if($a_param->dasar_remun == 2)
  <a href="{{ route('karyawan_cuti') }}" class="btn btn-warning btn-block btn-menu">Cuti Karyawan</a>
  <a href="{{ route('karyawan_absensi') }}" class="btn btn-warning btn-block btn-menu">Absensi Karyawan</a>
  @endif
  <!--<a href="{{ route('karyawan_histori_admin') }}" class="btn btn-warning btn-block btn-menu">Histori Karyawan</a>-->
  <hr class="menu-divider">  
  <a href="{{ route('rumusan_indeks') }}" class="btn btn-warning btn-block btn-menu">Indeks Masa Kerja</a>  
  <hr class="menu-divider">
  <a href="{{ route('bpjs_data') }}" class="btn btn-warning btn-block btn-menu">Data Claim Asuransi</a>
  <hr class="menu-divider">
  <a href="{{ route('remunerasi_olah_data') }}" class="btn btn-warning btn-block btn-menu">Remunerasi</a>
  <a href="{{ route('remunerasi_spj_data') }}" class="btn btn-warning btn-block btn-menu">Cetak SPJ & Kwitansi</a>
  <a href="{{ route('remunerasi_data') }}" class="btn btn-warning btn-block btn-menu">Data Remunerasi</a>
  <!--<a href="{{ route('remunerasi_original') }}" class="btn btn-warning btn-block btn-menu">Data Remun Original</a>-->
  <hr class="menu-divider">
  <a href="{{ route('tarif_daftar') }}" class="btn btn-warning btn-block btn-menu">Skema Tarif</a>
  <!--<hr class="menu-divider">
  <a href="{{ route('pengumuman') }}" class="btn btn-warning btn-block btn-menu">Pengumuman</a>-->
  <hr class="menu-divider">
@endif

@if(Auth::user()->id_akses == 7)
  <a href="{{ route('karyawan') }}" class="btn btn-warning btn-block btn-menu">Data Karyawan</a>
  <a href="{{ route('karyawan_indeks') }}" class="btn btn-warning btn-block btn-menu">Indeks Karyawan</a>
  <a href="{{ route('karyawan_jasa') }}" class="btn btn-warning btn-block btn-menu">Jasa Karyawan</a>
  <a href="{{ route('karyawan_gapok') }}" class="btn btn-warning btn-block btn-menu">Gapok, TPP, Pajak</a>
  @if($a_param->dasar_remun == 2)
  <a href="{{ route('karyawan_cuti') }}" class="btn btn-warning btn-block btn-menu">Cuti Karyawan</a>
  <a href="{{ route('karyawan_absensi') }}" class="btn btn-warning btn-block btn-menu">Absensi Karyawan</a>
  @endif
  <!--<a href="{{ route('karyawan_histori_admin') }}" class="btn btn-warning btn-block btn-menu">Histori Karyawan</a>-->
  <hr class="menu-divider">  
  <a href="{{ route('rumusan_indeks') }}" class="btn btn-warning btn-block btn-menu">Indeks Masa Kerja</a>  
  <hr class="menu-divider">
  <a href="{{ route('bpjs_data') }}" class="btn btn-warning btn-block btn-menu">Data Claim Asuransi</a>
  <hr class="menu-divider">
  <a href="{{ route('remunerasi_olah_data') }}" class="btn btn-warning btn-block btn-menu">Verifikasi Remunerasi</a>
  <a href="{{ route('remunerasi_data') }}" class="btn btn-warning btn-block btn-menu">Data Remunerasi</a>
  <!--<a href="{{ route('remunerasi_original') }}" class="btn btn-warning btn-block btn-menu">Data Remun Original</a>-->
  <hr class="menu-divider">
  <a href="{{ route('tarif_daftar') }}" class="btn btn-warning btn-block btn-menu">Skema Tarif</a>
  <!--<hr class="menu-divider">
  <a href="{{ route('pengumuman') }}" class="btn btn-warning btn-block btn-menu">Pengumuman</a>-->  
  <hr class="menu-divider">
@endif

<a href="{{ route('pasien_perawatan_data') }}" class="btn btn-warning btn-block btn-menu">Data Pasien</a>
<a href="{{ route('pasien_keluar') }}" class="btn btn-warning btn-block btn-menu">Pasien Keluar</a>     
<a href="{{ route('pasien_layanan_data') }}" class="btn btn-warning btn-block btn-menu">Layanan Pasien</a>
<a href="{{ route('pasien_statistik') }}" class="btn btn-warning btn-block btn-menu">Statistik Pasien</a>

@if(Auth::user()->id_akses <> 3 && Auth::user()->id_akses <> 1 && Auth::user()->id_akses <> 6 && Auth::user()->id_akses <> 7)
<a href="{{ route('tarif_user') }}" class="btn btn-warning btn-block btn-menu">Skema Tarif</a>
@endif
@endif

<!--@if(Auth::user()->id_akses <> 1)
<hr class="menu-divider">
<a href="{{ route('pengumuman_user') }}" class="btn btn-warning btn-block btn-menu">Pengumuman</a>
@endif-->
<hr class="menu-divider">
<a href="{{ route('jasa_remun') }}" class="btn btn-warning btn-block btn-menu">Jasa Remunerasi</a>
<hr class="menu-divider">
<a href="{{ route('download') }}" class="btn btn-warning btn-block btn-menu">Download</a>
<a href="{{ route('informasi_software') }}" class="btn btn-warning btn-block btn-menu">Informasi</a>
<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-warning btn-block btn-menu">Keluar</a>