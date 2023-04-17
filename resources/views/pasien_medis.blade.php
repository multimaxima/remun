@extends('layouts.content')
@section('title','Data Pasien Keluar')

@section('judul')
  <h4 class="page-title"> <i class="dripicons-user-id"></i> @yield('title')</h4>
@endsection

@section('content')
@if($pasien)
<div class="menu-samping">
  <form method="GET" action="{{ route('pasien_medis_cetak') }}" target="_blank">
  @csrf
    <input hidden type="text" name="jns" value="{{ $jns }}">
    <input hidden type="text" name="id_dpjp" value="{{ $id_dpjp }}">
    <input hidden type="date" name="awal" value="{{ $awal }}">
    <input hidden type="date" name="akhir" value="{{ $akhir }}">
    <input hidden type="text" name="cari" value="{{ $cari }}">

    <button type="submit" class="btn btn-secondary tbl-samping" title="Cetak">
      <i class="fa fa-print"></i>
      <span class="tbl-samping-label">CETAK</span>
    </button>
  </form>
</div>
@endif

<div class="wrapper">
  <div class="col-12" style="padding: 0 50px;">
    <div id="accordion">
      <div class="card">
        <div class="card-header p-3" id="headingOne">
          <h6 class="m-0">
            <a href="#collapseOne" class="text-dark" data-toggle="collapse" aria-expanded="true" aria-controls="collapseOne">
              FILTER DATA
            </a>
          </h6>
        </div>

        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
          <div class="card-body">
            <form class="form-horizontal fprev" method="GET" action="{{ route('pasien_medis') }}">
            @csrf

              <div class="form-group row">
                <label class="control-label col-2">Menampilkan</label>
                <div class="col-1">            
                  <select class="form-control" name="tampil" onchange="this.form.submit();">
                    <option value="10" {{ $tampil == '10'? 'selected' : null }}>10</option>
                    <option value="25" {{ $tampil == '25'? 'selected' : null }}>25</option>
                    <option value="50" {{ $tampil == '50'? 'selected' : null }}>50</option>
                    <option value="100" {{ $tampil == '100'? 'selected' : null }}>100</option>
                  </select>
                </div>
                <label class="control-label col-3" style="text-align: left;">Data</label>

                <label class="control-label col-2">Tanggal Keluar</label>
                <div class="col-2">
                  <input type="date" class="form-control" name="awal" required value="{{ $awal }}">
                </div>
              </div>

              <div class="form-group row">
                <label class="control-label col-2">DPJP</label>
                <div class="col-4">
                  <select class="form-control" name="id_dpjp" required>
                    <option value=""></option>
                    @foreach($dpjp as $dok)
                      <option value="{{ $dok->id }}" {{ $id_dpjp == $dok->id? 'selected' : null }}>{{ strtoupper($dok->nama) }}</option>
                    @endforeach
                  </select>
                </div>

                <label class="control-label col-2">Sampai Dengan</label>
                <div class="col-2">
                  <input type="date" class="form-control" name="akhir" required value="{{ $akhir }}">
                </div>
              </div>

              <div class="form-group row">
                <label class="control-label col-2">Jenis Pasien</label>
                <div class="col-4">
                  <select class="form-control" name="jns">
                    <option value=""></option>
                    @foreach($jenis as $jen)
                      <option value="{{ $jen->id }}" {{ $jns == $jen->id? 'selected' : null }}>{{ strtoupper($jen->jenis_pasien) }}</option>
                    @endforeach
                  </select>
                </div>

                <label class="control-label col-2">Cari Pasien</label>
                <div class="col-4">
                  <input type="text" class="form-control" name="cari" value="{{ $cari }}">
                </div>
              </div>

              <div class="form-group row" style="margin-top: 5px;">
                <div class="col-10 offset-2">
                  <button type="submit" class="btn btn-warning btn-sm bprev">
                    <i class="fa fa-check"></i> TAMPILKAN DATA
                  </button>

                  <button type="submit" form="reset" class="btn btn-danger btn-sm bprev">
                    <i class="fa fa-times"></i> RESET FILTER
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="wrapper" style="margin-top: 10px;">
  <div class="col-12" style="padding: 0 50px;">
    @if($pasien)
    <div class="card m-b-30 konten">
      <div class="card-body">
        <table width="300%" id="tabel" class="table table-striped table-hover" style="font-size: 13px;">
          <thead>
            <tr>
              <th rowspan="2"></th>
              <th rowspan="2" width="200" style="text-align: center; vertical-align: middle;">NAMA PASIEN</th>
              <th rowspan="2" width="130" style="text-align: center; vertical-align: middle;">WAKTU</th>
              <th rowspan="2" style="text-align: center; vertical-align: middle;">JENIS</th>
              <th rowspan="2" style="text-align: center; vertical-align: middle;">RUANG</th>
              <th rowspan="2" width="200" style="text-align: center; vertical-align: middle;">DPJP</th>
              <th rowspan="2" width="200" style="text-align: center; vertical-align: middle;">LAYANAN</th>
              <th rowspan="2" width="60" style="text-align: center; vertical-align: middle;">TARIF</th>
              <th colspan="2" style="text-align: center;">DPJP</th>
              <th colspan="2" style="text-align: center;">PENGGANTI</th>
              <th colspan="2" style="text-align: center;">OPERATOR</th>
              <th colspan="2" style="text-align: center;">ANASTESI</th>
              <th colspan="2" style="text-align: center;">PENDAMPING</th>
              <th colspan="2" style="text-align: center;">KONSULTASI</th>
              <th colspan="2" style="text-align: center;">LABORAT</th>
              <th colspan="2" style="text-align: center;">PENGANGGUNG JWB</th>
              <th colspan="2" style="text-align: center;">RADIOLOGI</th>
              <th colspan="2" style="text-align: center;">RR</th>
              <th rowspan="2" width="100" style="text-align: center; vertical-align: middle;">TOTAL MEDIS</th>
            </tr>
            <tr>
              <th style="text-align: center;" width="200">NAMA</th>
              <th style="text-align: center;" width="50">JASA</th>
              <th style="text-align: center;" width="200">NAMA</th>
              <th style="text-align: center;" width="50">JASA</th>
              <th style="text-align: center;" width="200">NAMA</th>
              <th style="text-align: center;" width="50">JASA</th>
              <th style="text-align: center;" width="200">NAMA</th>
              <th style="text-align: center;" width="50">JASA</th>
              <th style="text-align: center;" width="200">NAMA</th>
              <th style="text-align: center;" width="50">JASA</th>
              <th style="text-align: center;" width="200">NAMA</th>
              <th style="text-align: center;" width="50">JASA</th>
              <th style="text-align: center;" width="200">NAMA</th>
              <th style="text-align: center;" width="50">JASA</th>
              <th style="text-align: center;" width="200">NAMA</th>
              <th style="text-align: center;" width="50">JASA</th>
              <th style="text-align: center;" width="200">NAMA</th>
              <th style="text-align: center;" width="50">JASA</th>
              <th style="text-align: center;" width="200">NAMA</th>
              <th style="text-align: center;" width="50">JASA</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pasien as $pas)  
              <tr>
                <td class="min">
                  <a href="#" class="btn btn-primary btn-xs">
                    <i class="fa fa-list"></i>
                  </a>
                </td>
                <td>{{ strtoupper($pas->nama) }}</td>
                <td>{{ strtoupper($pas->waktu) }}</td>
                <td class="min" style="letter-spacing: 2px;">{{ strtoupper($pas->jenis_pasien) }}</td>
                <td>{{ strtoupper($pas->ruang) }}</td>
                <td>{{ strtoupper($pas->dpjp) }}</td>
                <td>{{ strtoupper($pas->jasa) }}</td>
                <td align="right">{{ number_format($pas->tarif,0) }}</td>
                <td>{{ strtoupper($pas->dpjp_real) }}</td>
                <td align="right">{{ number_format($pas->jasa_dpjp,0) }}</td>
                <td>{{ strtoupper($pas->pengganti) }}</td>
                <td align="right">{{ number_format($pas->jasa_pengganti,0) }}</td>
                <td>{{ strtoupper($pas->operator) }}</td>
                <td align="right">{{ number_format($pas->jasa_operator,0) }}</td>
                <td>{{ strtoupper($pas->anastesi) }}</td>
                <td align="right">{{ number_format($pas->jasa_anastesi,0) }}</td>
                <td>{{ strtoupper($pas->pendamping) }}</td>
                <td align="right">{{ number_format($pas->jasa_pendamping,0) }}</td>
                <td>{{ strtoupper($pas->konsul) }}</td>
                <td align="right">{{ number_format($pas->jasa_konsul,0) }}</td>
                <td>{{ strtoupper($pas->laborat) }}</td>
                <td align="right">{{ number_format($pas->jasa_laborat,0) }}</td>
                <td>{{ strtoupper($pas->tanggung) }}</td>
                <td align="right">{{ number_format($pas->jasa_tanggung,0) }}</td>
                <td>{{ strtoupper($pas->radiologi) }}</td>
                <td align="right">{{ number_format($pas->jasa_radiologi,0) }}</td>
                <td>{{ strtoupper($pas->rr) }}</td>
                <td align="right">{{ number_format($pas->jasa_rr,0) }}</td>
                <td align="right">{{ number_format($pas->medis,0) }}</td>
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
        "order": [[ 1, "asc" ]],
        "scrollX": true,
        "paging": false,
        "searching": false,
        "info": false,
      });
    });
  </script>
@endsection