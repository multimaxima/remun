@extends('layouts.content')
@section('title','Data Pasien')

@section('judul')
  <div class="float-right">
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" title="Filter Data">
      <i class="fa fa-filter"></i>
    </button>
  </div>

  <h4 class="page-title"> <i class="dripicons-user-id"></i> @yield('title')</h4>
@endsection

@section('content')
@if($rng || $jns)
<div class="wrapper collapse show" id="collapseExample" style="margin-bottom: 60px;">
@else
<div class="wrapper collapse" id="collapseExample" style="margin-bottom: 60px;">
@endif
  <div class="col-12" style="padding: 0 50px;">
    <div class="card card-body" style="padding: 10px 0;">
      <form class="form-inline justify-content-center" method="GET" action="{{ route('pasien') }}">
      @csrf    

        <label>Ruang</label>
        <select class="form-control form-control-sm" name="rng" style="margin: 0 10px;" onchange="this.form.submit();">
          <option value="">=== SEMUA RUANGAN ===</option>
          @foreach($ruang as $ruang)
            <option value="{{ $ruang->id }}" {{ $rng == $ruang->id? 'selected' : null }}>{{ strtoupper($ruang->ruang) }}</option>
          @endforeach
        </select>                  

        <label>Jenis</label>
        <select class="form-control form-control-sm" name="jns" style="margin: 0 10px;" onchange="this.form.submit();">
          <option value="">=== SEMUA JENIS ===</option>
          @foreach($jenis as $jenis)
            <option value="{{ $jenis->id }}" {{ $jns == $jenis->id? 'selected' : null }}>{{ strtoupper($jenis->jenis_pasien) }}</option>
          @endforeach
        </select>

        <button type="submit" form="export" class="btn btn-primary btn-sm" style="margin-right: 3px;">
          <i class="fa fa-file-excel-o"></i> EXPORT
        </button>

        <a href="#" class="btn btn-primary btn-sm">
          <i class="fa fa-print"></i> CETAK
        </a>
      </form>

      <form hidden method="GET" action="{{ route('pasien_export') }}" id="export">
      @csrf
        <input type="text" name="id_jenis" value="{{ $jns }}">
        <input type="text" name="id_ruang" value="{{ $rng }}">
      </form>
    </div>
  </div>
</div>

<div class="wrapper">
  <div class="col-12" style="padding: 0 50px;">
    @if($pasien)
    <div class="card m-b-30 konten">
      <div class="card-body">
        <form class="form-inline row m-b-5" method="GET" action="{{ route('pasien') }}">
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

        <table width="100%" id="tabel" class="table table-hover table-striped" style="font-size: 13px; margin-bottom: 10px;">
          <thead>
            <th></th>
            <th>NAMA PASIEN</th>
            <th width="60">MR</th>
            <th>REGISTER</th>
            <th>JENIS</th>
            <th>ALAMAT</th>
            <th>MASUK</th>
            <th>RUANG</th>
          </thead>
          <tbody>
            @foreach($pasien as $pas)  
              <tr>              
                <td class="min">  
                  <form method="GET" action="{{ route('pasien_detil') }}" target="_blank">
                  @csrf
                    <input type="hidden" name="rng" value="{{ $rng }}">
                    <input type="hidden" name="jns" value="{{ $jns }}">
                    <input type="hidden" name="id_pasien" value="{{ Crypt::encrypt($pas->id) }}">

                    <button type="submit" title="Detil" class="btn btn-primary btn-xs">
                      RINCIAN
                    </button>                
                  </form>
                </td>
                <td>{{ strtoupper($pas->nama) }}</td>
                <td class="min" align="center">{{ $pas->no_mr }}</td>
                <td class="min" align="center">{{ $pas->register }}</td>
                <td>{{ strtoupper($pas->jenis_pasien) }}</td>
                <td>{{ $pas->alamat }}</td>
                <td class="min">{{ $pas->masuk }}</td>                
                <td>{{ strtoupper($pas->ruang) }}</td>                
              </tr>              
            @endforeach
          </tbody>
        </table>
        <div style="float: left;">
          Menampilkan {{ number_format($pasien->firstItem(),0) }} - {{ number_format($pasien->lastItem(),0) }} dari {{ number_format($pasien->total(),0) }} data
        </div>                               
        <div style="float: right;">
          {!! $pasien->appends(request()->input())->render("pagination::bootstrap-4"); !!}
        </div>
      </div>      
    </div>
    @endif
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('#tabel').DataTable( {
        "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": 0
        }],
        "stateSave": true,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        "order": [[ 0, "asc" ]],
        info:       false,
        searching:  false,
        paging:     false,
      });
    });
  </script>
@endsection