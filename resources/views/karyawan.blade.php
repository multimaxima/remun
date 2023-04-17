@extends('layouts.content')
@section('title','Data Karyawan')

@section('style')
  <style type="text/css">
    td a{
      color: #424242;
    }
  </style>
@endsection

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12" style="display: inline-flex;">
        <form class="form-inline" method="GET" action="{{ route('karyawan') }}" style="margin-top: 5px; margin-bottom: 0;">
        @csrf
          <input type="hidden" name="cari" value="{{ $cari }}">
          <input type="hidden" name="tampil" value="{{ $tampil }}">

          <select name="id_ruang" id="id_ruang" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">=== SEMUA RUANG ===</option>
            @foreach($ruang as $rng)
              <option value="{{ $rng->id }}" {{ $id_ruang == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
            @endforeach
          </select>

          <select name="id_bagian" id="id_bagian" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">=== SEMUA BAGIAN ===</option>
            @foreach($bagian as $bag)
              <option value="{{ $bag->id }}" {{ $id_bagian == $bag->id? 'selected' : null }}>{{ strtoupper($bag->bagian) }}</option>
            @endforeach
          </select>

          <select name="id_akses" id="id_akses" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">=== SEMUA HAK AKSES ===</option>
            @foreach($akses as $aks)
              <option value="{{ $aks->id }}" {{ $id_akses == $aks->id? 'selected' : null }}>{{ strtoupper($aks->akses) }}</option>
            @endforeach
          </select>

          <select name="aktif" id="aktif" onchange="this.form.submit();">
            <option value="0" {{ $aktif == 0? 'selected' : null }}>KARYAWAN AKTIF</option>
            <option value="1" {{ $aktif == 1? 'selected' : null }}>KARYAWAN TIDAK AKTIF</option>
          </select>
        </form>
        
        <div class="btn-group" style="margin-left: 5px;">
          <button type="submit" form="form_baru" class="btn btn-primary" title="Tambah Data">TAMBAH</button>
          <button type="submit" form="cetak" class="btn btn-primary" title="Cetak">CETAK</button>    
          <a href="{{ route('karyawan_export') }}" class="btn btn-primary" title="Export Excel">EXPORT</a>
        </div>

        <form hidden id="cetak" method="GET" action="{{ route('karyawan_cetak') }}" target="_blank">
        @csrf
          <input type="text" name="id_ruang" id="c_id_ruang" value="">
        </form>  
      </div>
    </div>
  </div>
</div>

<div class="content">
  @include('layouts.pesan')
  <form method="GET" action="{{ route('karyawan') }}" class="form-inline" style="margin-bottom: 5px;">
  @csrf
    <input type="hidden" name="id_ruang" value="{{ $id_ruang }}">
    <input type="hidden" name="id_bagian" value="{{ $id_bagian }}">
    <input type="hidden" name="aktif" value="{{ $aktif }}">
    <input type="hidden" name="id_akses" value="{{ $id_akses }}">

    Menampilkan
    <select onchange="this.form.submit();" name="tampil">
      <option value="10" {{ $tampil == '10'? 'selected' : null }}>10</option>
      <option value="25" {{ $tampil == '25'? 'selected' : null }}>25</option>
      <option value="50" {{ $tampil == '50'? 'selected' : null }}>50</option>
      <option value="100" {{ $tampil == '100'? 'selected' : null }}>100</option>
      <option value="999999999999999" {{ $tampil == '999999999999999'? 'selected' : null }}>Semua</option>
    </select> data

    <input type="text" name="cari" class="pull-right" placeholder="Cari..." value="{{ $cari }}">
  </form>
  <table id="tabel" width="100%" class="table table-striped table-hover table-bordered">
    <thead>
      <tr>
        <th rowspan="2"></th>
        <th rowspan="2" style="padding: 0 10px;">No.</th>
        <th rowspan="2" style="padding: 0 10px;">Nama</th>
        <th rowspan="2" style="padding: 0 10px;">Bagian</th>
        <th colspan="3" style="padding: 0 10px;">Ruang Kerja</th>        
        <th rowspan="2" style="padding: 0 10px;">No. Rekening</th>
        <th rowspan="2" style="padding: 0 10px;">Skore</th>
        <th rowspan="2" style="padding: 0 10px;">Akses</th>
      </tr>
      <tr>
        <th style="padding: 0 10px;">Utama</th>
        <th style="padding: 0 10px;">1</th>
        <th style="padding: 0 10px;">2</th>
      </tr>      
    </thead>
    <tbody>
      <?php $no = 0;?>
      @foreach($karyawan as $kary)
      <?php $no++ ;?>
      <tr>
        <td class="min">
          <div class="btn-group">
            <button class="btn btn-info btn-mini edit" data-id="{{ $kary->id }}" title="Edit Data Karyawan">
              <i class="icon-edit"></i>
            </button>            

            @if($a_param->dasar_remun == 2)
            <button class="btn btn-info btn-mini edit_history" title="Histori Data Karyawan" data-id="{{ $kary->id }}">
              <i class="icon-time"></i>
            </button>            
            @endif

            <a href="{{ route('karyawan_reset',Crypt::encrypt($kary->id)) }}" class="btn btn-info btn-mini" title="Reset Password {{ $kary->nama }}" onclick="return confirm('Reset password {{ $kary->nama }} ?')">
              <i class="icon-warning-sign"></i>
            </a>

            @if($kary->hapus == 0)
            <a href="{{ route('karyawan_hapus',Crypt::encrypt($kary->id)) }}" class="btn btn-info btn-mini" title="Hapus Karyawan" onclick="return confirm('Hapus karyawan {{ $kary->nama }} ?')">
              <i class="icon-trash"></i>
            </a>
            @endif
          </div>
        </td>
        <td class="min" style="text-align: right; padding-right: 5px;">{{ $no }}.</td>
        <td style="white-space: nowrap;">{{ $kary->nama }}</td>
        <td class="min">{{ strtoupper($kary->bagian) }}</td>
        <td class="min">{{ strtoupper($kary->ruang) }}</td>
        <td class="min">{{ strtoupper($kary->ruang_1) }}</td>
        <td class="min">{{ strtoupper($kary->ruang_2) }}</td>
        <td class="min" style="text-align: center;">{{ strtoupper($kary->rekening) }}</td>        
        <td class="min" style="text-align: right;">{{ number_format($kary->skore,2) }}</td>
        <td class="min">{{ strtoupper($kary->akses) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div>
    <div class="pull-left" style="font-size: 12px;">
      Menampilkan {{ number_format($karyawan->firstItem(),0) }} - {{ number_format($karyawan->lastItem(),0) }} dari {{ number_format($karyawan->total(),0) }} data
    </div>                               
    <div class="pagination pagination-small pull-right">
      {!! $karyawan->appends(request()->input())->render("pagination::bootstrap-4"); !!}
    </div>
  </div>
</div>    

<form hidden method="GET" action="{{ route('karyawan_edit') }}" id="form_edit">
@csrf
  <input type="text" name="id" id="edit_id">
  <input type="text" name="tampil" value="{{ $tampil }}">
  <input type="text" name="cari" value="{{ $cari }}">
  <input type="text" name="id_ruang" value="{{ $id_ruang }}">
  <input type="text" name="id_bagian" value="{{ $id_bagian }}">
</form>   

<form hidden method="GET" action="{{ route('karyawan_baru') }}" id="form_baru">
@csrf
  <input type="text" name="tampil" value="{{ $tampil }}">
  <input type="text" name="cari" value="{{ $cari }}">
  <input type="text" name="id_ruang" value="{{ $id_ruang }}">
  <input type="text" name="id_bagian" value="{{ $id_bagian }}">
</form>   

<form hidden method="GET" action="{{ route('karyawan_histori') }}" id="form_histori" target="_blank">
@csrf
  <input type="text" name="id" id="id_edit_history">
</form>
@endsection

@section('script')
  <script type="text/javascript">
    window.onload=function() {
      $id_ruang = document.getElementById('id_ruang').value;

      document.getElementById('c_id_ruang').value = $id_ruang;
    }

    $(document).ready(function() {
      $('.edit').on("click",function() {
        var id = $(this).attr('data-id');
        $('#edit_id').val(id);
        $('#form_edit').submit();
      });

      $('.edit_history').on("click",function() {
        var id = $(this).attr('data-id');
        $('#id_edit_history').val(id);
        $('#form_histori').submit();
      });
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.21*box.clientHeight);

      $('#tabel').DataTable( {     
        scrollY:        tinggi,
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        searching:      false,
        sort:           false,
        info:           false,
      });
    });
  </script>
@endsection