@extends('layouts.content')
@section('title','Data Karyawan')

@section('style')
  <style type="text/css">
    a{
      color: #424242;
    }
  </style>
@endsection

@section('judul')  
  <h4 class="page-title"> <i class="dripicons-user"></i> @yield('title')</h4>

  <form hidden id="cetak" method="GET" action="{{ route('karyawan_cetak') }}" target="_blank">
  @csrf
    <input type="text" name="id_ruang" id="c_id_ruang" value="">
  </form>  
@endsection

@section('content')
<div class="wrapper" style="margin-bottom: 60px;">
  <div class="col-12" style="padding: 0 50px;">
    <div class="card card-body" style="padding: 10px 0;">
      <form class="form-inline justify-content-center" method="GET" action="{{ route('karyawan_backup') }}">
      @csrf
        <label>TANGGAL</label>
        <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control form-control-sm" style="margin: 0 10px;" onchange="this.form.submit();">

        <select class="form-control form-control-sm" name="id_ruang" id="id_ruang" style="margin: 0 1px; width: 400px;" onchange="this.form.submit();">
          <option value="">=== TAMPILKAN SEMUA RUANG ===</option>
          @foreach($ruang as $rng)
            <option value="{{ $rng->id }}" {{ $id_ruang == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
          @endforeach
        </select>
      </form>
    </div>
  </div>
</div>

<div class="wrapper">
  <div class="col-12" style="padding: 0 50px;">
    <div class="card m-b-30 konten">
      <div class="card-body">   
        <form class="form-inline row m-b-5" method="GET" action="{{ route('karyawan_backup') }}">
        @csrf
          <div class="col-12">
            <div class="float-left">
              Menampilkan
              <select class="form-control form-control-sm" style="margin: 0 5px;" name="tampil" onchange="this.form.submit();">
                <option value="10" {{ $tampil == '10'? 'selected' : null }}>10</option>
                <option value="25" {{ $tampil == '25'? 'selected' : null }}>25</option>
                <option value="50" {{ $tampil == '50'? 'selected' : null }}>50</option>
                <option value="100" {{ $tampil == '100'? 'selected' : null }}>100</option>
                <option value="9999999999" {{ $tampil == '9999999999'? 'selected' : null }}>Semua</option>
              </select>
              Data
            </div>
            <div class="float-right">
              <input type="text" name="cari" class="form-control form-control-sm" value="{{ $cari }}" onchange="this.form.submit();" placeholder="Cari data...">
            </div>
          </div>
        </form>

        <table id="tabel" width="150%" class="table table-striped table-hover" style="font-size: 13px;">
          <thead>
            <th style="vertical-align: middle;">Nama</th>
            <th style="vertical-align: middle;">Bagian</th>
            <th style="vertical-align: middle;">Ruang Utama</th>
            <th style="vertical-align: middle;">Ruang Tambahan</th>
            <th style="vertical-align: middle;">Jabatan</th>
            <th style="vertical-align: middle;">Status</th>
            <th style="vertical-align: middle;">Golongan</th>
            <th style="vertical-align: middle;">Masa Kerja</th>
            <th style="vertical-align: middle;">Indeks Masa Kerja</th>
            <th style="vertical-align: middle;" width="50">Gapok</th>
            <th style="vertical-align: middle;" width="50">Indeks Dasar</th>
            <th style="vertical-align: middle;" width="50">TPP</th>            
            <th style="vertical-align: middle;" width="30">Score</th>
            <th style="vertical-align: middle;">Hak Akses</th>
            <th style="vertical-align: middle;">Interensif</th>
          </thead>
          <tbody>
            @foreach($karyawan as $kary)
            <tr>
              <td>{{ $kary->nama }}</td>
              <td>{{ strtoupper($kary->bagian) }}</td>
              <td>{{ strtoupper($kary->ruang) }}</td>
              <td>{{ strtoupper($kary->ruang_1) }}</td>
              <td>{{ strtoupper($kary->jabatan) }}</td>
              <td class="min">{{ strtoupper($kary->status) }}</td>
              <td align="center" class="min">{{ strtoupper($kary->golongan) }}</td>
              <td align="center">{{ $kary->masa_kerja }}</td>
              <td align="center" class="min">{{ strtoupper($kary->indeks_kerja) }}</td>
              <td align="right" class="min">{{ number_format($kary->gapok,0) }}</td>
              <td align="right" class="min">{{ number_format($kary->indeks_dasar,2) }}</td>
              <td align="right" class="min">{{ number_format($kary->tpp,0) }}</td>
              <td align="right" class="min">{{ number_format($kary->skore,2) }}</td>
              <td>{{ strtoupper($kary->akses) }}</td>
              <td class="min">
                @if($kary->id_tenaga_bagian == 24)
                  YA
                @else
                  TIDAK
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <div style="margin-top: 10px;">
          <div style="float: left;">
            Menampilkan {{ number_format($karyawan->firstItem(),0) }} - {{ number_format($karyawan->lastItem(),0) }} dari {{ number_format($karyawan->total(),0) }} data
          </div>                               
          <div style="float: right;">
            {!! $karyawan->appends(request()->input())->render("pagination::bootstrap-4"); !!}
          </div>
        </div>
      </div>
    </div>
  </div>                
</div>       
@endsection

@section('script')
  <script type="text/javascript">
    window.onload=function() {
      $id_ruang = document.getElementById('id_ruang').value;

      document.getElementById('c_id_ruang').value = $id_ruang;
    }
  </script>


  <script type="text/javascript">
    $(document).ready(function() {
      $('#tabel').DataTable( {
        "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": 0
        }],
        "searching": false,
        "info": false,
        "paging": false,
        "sort": false,
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   {
            leftColumns: 2
        },
      });
    });
  </script>
@endsection