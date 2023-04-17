@extends('layouts.content')
@section('title','Cari Data Pasien')

@section('style')
  <style type="text/css">
    th, td {
      white-space: nowrap;
    }
  </style>
@endsection

@section('judul')
  <h4 class="page-title"> <i class="dripicons-user-id"></i> @yield('title')</h4>
@endsection

@section('content')
<div class="wrapper" id="collapseExample" style="margin-bottom: 60px;">
  <div class="col-12" style="padding: 0 50px;">
    <div class="card card-body" style="padding: 10px 0;">
      <form class="form-inline justify-content-center" method="GET" action="{{ route('pasien_cari') }}">
      @csrf    
        <input type="text" class="form-control" name="cari" value="{{ $cari }}" placeholder="Masukkan Nama/Register Pasien" style="min-width: 300px;">
        <button class="btn btn-primary btn-sm" style="margin-left: 5px;">CARI</button>
      </form>
    </div>
  </div>
</div>

<div class="wrapper">
  <div class="col-12" style="padding: 0 50px;">
    @if($pasien)
    <div class="card m-b-30 konten">
      <div class="card-body">
        <table width="300%" id="tabel" class="table table-hover table-striped" style="font-size: 13px; margin-bottom: 10px;">
          <thead>
            <tr>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">NAMA PASIEN</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">MR</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">WAKTU TINDAKAN</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">WAKTU KELUAR</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">JENIS</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">RUANG PERAWATAN</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">RUANG TINDAKAN</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">JASA</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">TARIF</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">JS</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">JP</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">PROFIT</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">PENGHASIL</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">NON PENGHASIL</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">DPJP</th>
              <th style="text-align: center;" colspan="2">DPJP TINDAKAN</th>
              <th style="text-align: center;" colspan="2">DOKTER PENGGANTI</th>
              <th style="text-align: center;" colspan="2">OPERATOR</th>
              <th style="text-align: center;" colspan="2">ANASTESI</th>
              <th style="text-align: center;" colspan="2">DOKTER PENDAMPING</th>
              <th style="text-align: center;" colspan="2">DOKTER KONSUL</th>
              <th style="text-align: center;" colspan="2">LABORAT</th>
              <th style="text-align: center;" colspan="2">PENANGGUNG JAWAB</th>
              <th style="text-align: center;" colspan="2">RADIOLOGI</th>
              <th style="text-align: center;" colspan="2">RR</th>
              <th style="text-align: center; vertical-align: middle; background-color: #dfdfdf;" rowspan="2">TOTAL MEDIS</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">PERAWAT</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">PENATA ANASTESI</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">PERAWAT ASISTEN 1</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">PERAWAT ASISTEN 2</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">INSTRUMEN</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">SIRKULER</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">PERAWAT PENDAMPING 1</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">PERAWAT PENDAMPING 2</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">APOTEKER</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">ASISTEN APOTEKER</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">ADMIN FARMASI</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">ADMINISTRASI</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">POS REMUN</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">DIREKSI</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">STAF DIREKSI</th>
              <th style="text-align: center; vertical-align: middle;" rowspan="2">INSENTIF PERAWAT</th>
            </tr>
            <tr>
              <th style="text-align: center; vertical-align: middle;">NAMA</th>
              <th style="text-align: center; vertical-align: middle;">JASA</th>
              <th style="text-align: center; vertical-align: middle;">NAMA</th>
              <th style="text-align: center; vertical-align: middle;">JASA</th>
              <th style="text-align: center; vertical-align: middle;">NAMA</th>
              <th style="text-align: center; vertical-align: middle;">JASA</th>
              <th style="text-align: center; vertical-align: middle;">NAMA</th>
              <th style="text-align: center; vertical-align: middle;">JASA</th>
              <th style="text-align: center; vertical-align: middle;">NAMA</th>
              <th style="text-align: center; vertical-align: middle;">JASA</th>
              <th style="text-align: center; vertical-align: middle;">NAMA</th>
              <th style="text-align: center; vertical-align: middle;">JASA</th>
              <th style="text-align: center; vertical-align: middle;">NAMA</th>
              <th style="text-align: center; vertical-align: middle;">JASA</th>
              <th style="text-align: center; vertical-align: middle;">NAMA</th>
              <th style="text-align: center; vertical-align: middle;">JASA</th>
              <th style="text-align: center; vertical-align: middle;">NAMA</th>
              <th style="text-align: center; vertical-align: middle;">JASA</th>
              <th style="text-align: center; vertical-align: middle;">NAMA</th>
              <th style="text-align: center; vertical-align: middle;">JASA</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pasien as $pas)
              <tr>
                <td>{{ $pas->pasien }}</td>
                <td>{{ $pas->no_mr }}</td>
                <td>{{ $pas->waktu }}</td>
                <td>{{ $pas->keluar }}</td>
                <td>{{ $pas->jenis_pasien }}</td>
                <td>{{ $pas->ruang_perawatan }}</td>
                <td>{{ $pas->ruang_tindakan }}</td>
                <td>{{ $pas->jasa }}</td>
                <td align="right">{{ number_format($pas->tarif,0) }}</td>
                <td align="right">{{ number_format($pas->js,0) }}</td>
                <td align="right">{{ number_format($pas->jp,0) }}</td>
                <td align="right">{{ number_format($pas->profit,0) }}</td>
                <td align="right">{{ number_format($pas->penghasil,0) }}</td>
                <td align="right">{{ number_format($pas->non_penghasil,0) }}</td>
                <td>{{ $pas->dpjp }}</td>
                <td>{{ $pas->dpjp_real }}</td>
                <td align="right">{{ number_format($pas->jasa_dpjp,0) }}</td>
                <td>{{ $pas->pengganti }}</td>
                <td align="right">{{ number_format($pas->jasa_pengganti,0) }}</td>
                <td>{{ $pas->operator }}</td>
                <td align="right">{{ number_format($pas->jasa_operator,0) }}</td>
                <td>{{ $pas->anastesi }}</td>
                <td align="right">{{ number_format($pas->jasa_anastesi,0) }}</td>
                <td>{{ $pas->pendamping }}</td>
                <td align="right">{{ number_format($pas->jasa_pendamping,0) }}</td>
                <td>{{ $pas->konsul }}</td>
                <td align="right">{{ number_format($pas->jasa_konsul,0) }}</td>
                <td>{{ $pas->laborat }}</td>
                <td align="right">{{ number_format($pas->jasa_laborat,0) }}</td>
                <td>{{ $pas->penanggung_jawab }}</td>
                <td align="right">{{ number_format($pas->jasa_tanggung,0) }}</td>
                <td>{{ $pas->radiologi }}</td>
                <td align="right">{{ number_format($pas->jasa_radiologi,0) }}</td>
                <td>{{ $pas->rr }}</td>
                <td align="right">{{ number_format($pas->jasa_rr,0) }}</td>
                <td align="right" style="background-color: #dfdfdf;">{{ number_format($pas->medis,0) }}</td>
                <td align="right">{{ number_format($pas->jp_perawat,0) }}</td>
                <td align="right">{{ number_format($pas->pen_anastesi,0) }}</td>
                <td align="right">{{ number_format($pas->per_asisten_1,0) }}</td>
                <td align="right">{{ number_format($pas->per_asisten_2,0) }}</td>
                <td align="right">{{ number_format($pas->instrumen,0) }}</td>
                <td align="right">{{ number_format($pas->sirkuler,0) }}</td>
                <td align="right">{{ number_format($pas->per_pendamping_1,0) }}</td>
                <td align="right">{{ number_format($pas->per_pendamping_2,0) }}</td>
                <td align="right">{{ number_format($pas->apoteker,0) }}</td>
                <td align="right">{{ number_format($pas->ass_apoteker,0) }}</td>
                <td align="right">{{ number_format($pas->admin_farmasi,0) }}</td>
                <td align="right">{{ number_format($pas->administrasi,0) }}</td>
                <td align="right">{{ number_format($pas->pos_remun,0) }}</td>
                <td align="right">{{ number_format($pas->direksi,0) }}</td>
                <td align="right">{{ number_format($pas->staf_direksi,0) }}</td>
                <td align="right">{{ number_format($pas->insentif_perawat,0) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>        
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
        scrollY:        "400px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        searching:      false,        
        stateSave: true,        
      });
    });
  </script>
@endsection