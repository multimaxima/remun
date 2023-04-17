@extends('mobile.layouts.content')

@section('bawah')
  <li>
    <a href="#" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne"><i class="fa fa-filter"></i>Filter</a>
  </li>
  <li><a href="{{ route('informasi_software') }}"><i class="fa fa-info"></i>Informasi</a></li>
@endsection

@section('content')
<div class="row isi">
  <div class="container" style="margin-top: 8vh;">    
  </div>
</div>

<div class="collapse" id="collapseOne" aria-labelledby="headingOne" style="margin-top: 1vh;">
  <div class="row isi">
    <div class="container">    
      <div class="card">
        <div class="card-body user-data-card" style="font-size: 3vw;"> 
          <form class="form-horizontal" method="GET" action="{{ route('pasien_ruang_data') }}"  style="margin-top: 5px; margin-bottom: 0;">
        @csrf
          <input type="hidden" name="cari" value="{{ $cari }}">
          <input type="hidden" name="tampil" value="{{ $tampil }}">

          <div class="mb-3">
            <div class="title mb-2">Tanggal</div>
            <input type="date" name="awal" class="form-control" value="{{ $awal }}">
          </div>

          <div class="mb-3">
            <div class="title mb-2">Sampai</div>
            <input type="date" name="akhir" class="form-control" value="{{ $akhir }}">
          </div>

          <div class="mb-3">
            <div class="title mb-2">Jenis Pasien</div>          
            <select name="id_jenis" class="form-control">
              <option value="" style="font-style: italic;">SEMUA JENIS</option>
              @foreach($jenis as $jns)
                <option value="{{ $jns->id }}" {{ $id_jenis == $jns->id? 'selected' : null }}>{{ strtoupper($jns->jenis) }}</option>
              @endforeach
            </select>
          </div>
          
          <div class="btn-group btn-group-xs" style="margin-top: 0;">
            <button type="submit" class="btn btn-primary btn-xs">TAMPILKAN</button>
            <button type="submit" form="cetak" class="btn btn-primary btn-xs">CETAK</button>
          </div>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>

<form hidden method="GET" id="cetak" action="{{ route('pasien_ruang_data_cetak') }}" target="_blank">
@csrf
  <input type="date" name="awal" value="{{ $awal }}">
  <input type="date" name="akhir" value="{{ $akhir }}">
</form>

<div class="row isi" style="padding-bottom: 10vh;">
  <div class="container">
    <div class="card" style="margin-top: 1vh;">
      <div class="card-body" style="font-size: 3vw; overflow-x: auto;">
        <table width="100%" id="tabel" class="table table-hover table-striped table-bordered">
    <thead>
      <tr>
        <th class="min" rowspan="3" valign="middle" style="text-align: center;">WAKTU</th>              
        <th class="min" rowspan="3" valign="middle" style="text-align: center;">NAMA PASIEN</th>
        <th class="min" rowspan="3" valign="middle" style="text-align: center;">REGISTER</th>
        <th class="min" rowspan="3" valign="middle" style="text-align: center;">NO. MR</th>
        <th class="min" rowspan="3" valign="middle" style="text-align: center;">JENIS</th>        
        <th class="min" rowspan="3" valign="middle" style="text-align: center;">PETUGAS</th>
        <th class="min" rowspan="3" valign="middle" style="text-align: center;">JASA</th>              
        <th class="min" rowspan="3" valign="middle" style="text-align: center;">TARIF</th>
        <th colspan="20" valign="middle" style="text-align: center;">Medis</th>        
        <th colspan="13" valign="middle" style="text-align: center;">Perawat Setara</th>        
      </tr>
      <tr>
        <th colspan="2" valign="middle" style="padding: 0 20px; text-align: center;">DPJP</th>
        <th colspan="2" valign="middle" style="padding: 0 20px; text-align: center;">Pengganti</th>
        <th colspan="2" valign="middle" style="padding: 0 20px; text-align: center;">Operator</th>
        <th colspan="2" valign="middle" style="padding: 0 20px; text-align: center;">Anastesi</th>
        <th colspan="2" valign="middle" style="padding: 0 20px; text-align: center;">Pendamping</th>
        <th colspan="2" valign="middle" style="padding: 0 20px; text-align: center;">Konsul</th>
        <th colspan="2" valign="middle" style="padding: 0 20px; text-align: center;">Laborat</th>
        <th colspan="2" valign="middle" style="padding: 0 20px; text-align: center;">Pen. Jawab</th>
        <th colspan="2" valign="middle" style="padding: 0 20px; text-align: center;">Radiologi</th>
        <th colspan="2" valign="middle" style="padding: 0 20px; text-align: center;">RR</th>
        <th rowspan="2" valign="middle" class="min" style="padding: 0 20px; text-align: center;">Perawat</th>
        <th rowspan="2" valign="middle" class="min" style="padding: 0 20px; text-align: center;">Penata Anastesi</th>
        <th rowspan="2" valign="middle" class="min" style="padding: 0 20px; text-align: center;">Per. Ass. 1</th>
        <th rowspan="2" valign="middle" class="min" style="padding: 0 20px; text-align: center;">Per. Ass. 2</th>
        <th rowspan="2" valign="middle" class="min" style="padding: 0 20px; text-align: center;">Instrumen</th>
        <th rowspan="2" valign="middle" class="min" style="padding: 0 20px; text-align: center;">Sirkuler</th>
        <th rowspan="2" valign="middle" class="min" style="padding: 0 20px; text-align: center;">Per. Pend. 1</th>
        <th rowspan="2" valign="middle" class="min" style="padding: 0 20px; text-align: center;">Per. Pend. 2</th>
        <th rowspan="2" valign="middle" class="min" style="padding: 0 20px; text-align: center;">Apoteker</th>
        <th rowspan="2" valign="middle" class="min" style="padding: 0 20px; text-align: center;">Ass. Apoteker</th>
        <th rowspan="2" valign="middle" class="min" style="padding: 0 20px; text-align: center;">Admin Farmasi</th>
        <th rowspan="2" valign="middle" class="min" style="padding: 0 20px; text-align: center;">Pemulasaran</th>
        <th rowspan="2" valign="middle" class="min" style="padding: 0 20px; text-align: center;">Fisio</th>
      </tr>
      <tr>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Nama</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Jasa</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Nama</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Jasa</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Nama</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Jasa</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Nama</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Jasa</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Nama</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Jasa</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Nama</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Jasa</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Nama</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Jasa</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Nama</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Jasa</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Nama</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Jasa</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Nama</th>
        <th style="padding: 0 20px; text-align: center;" valign="middle">Jasa</th>
      </tr>
    </thead>
    <tbody>
      @foreach($pasien as $pas)
      <tr>
        <td class="min">{{ strtoupper($pas->waktu) }}</td>
        <td class="min">{{ strtoupper($pas->nama) }}</td>
        <td class="min">{{ $pas->register }}</td>
        <td class="min">{{ $pas->no_mr }}</td>
        <td class="min">{{ strtoupper($pas->jenis_pasien) }}</td>        
        <td class="min">{{ $pas->petugas }}</td>
        <td class="min">{{ strtoupper($pas->jasa) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->tarif,0) }}</td>
        <td class="min">{{ $pas->dpjp }}</td>        
        <td class="min" style="text-align: right;">{{ number_format($pas->jasa_dpjp,0) }}</td>        
        <td class="min">{{ $pas->pengganti }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->jasa_pengganti,0) }}</td>
        <td class="min">{{ $pas->operator }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->jasa_operator,0) }}</td>
        <td class="min">{{ $pas->anastesi }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->jasa_anastesi,0) }}</td>
        <td class="min">{{ $pas->pendamping }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->jasa_pendamping,0) }}</td>
        <td class="min">{{ $pas->konsul }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->jasa_konsul,0) }}</td>
        <td class="min">{{ $pas->laborat }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->jasa_laborat,0) }}</td>
        <td class="min">{{ $pas->tanggung }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->jasa_tanggung,0) }}</td>
        <td class="min">{{ $pas->radiologi }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->jasa_radiologi,0) }}</td>
        <td class="min">{{ $pas->rr }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->jasa_rr,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->jp_perawat,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->pen_anastesi,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->per_asisten_1,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->per_asisten_2,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->instrumen,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->sirkuler,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->per_pendamping_1,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->per_pendamping_2,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->apoteker,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->ass_apoteker,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->admin_farmasi,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->pemulasaran,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($pas->fisio,0) }}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <th colspan="7" style="text-align: center;"></th>
      <th class="min" style="text-align: right; padding: 5px;">{{ number_format($total->tarif,0) }}</th>
      <th class="min" style="text-align: center;"></th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_dpjp,0) }}</th>        
      <th class="min" style="text-align: center;"></th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_pengganti,0) }}</th>
      <th class="min" style="text-align: center;"></th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_operator,0) }}</th>
      <th class="min" style="text-align: center;"></th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_anastesi,0) }}</th>
      <th class="min" style="text-align: center;"></th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_pendamping,0) }}</th>
      <th class="min" style="text-align: center;"></th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_konsul,0) }}</th>
      <th class="min" style="text-align: center;"></th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_laborat,0) }}</th>
      <th class="min" style="text-align: center;"></th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_tanggung,0) }}</th>
      <th class="min" style="text-align: center;"></th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_radiologi,0) }}</th>
      <th class="min" style="text-align: center;"></th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_rr,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jp_perawat,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->pen_anastesi,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->per_asisten_1,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->per_asisten_2,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->instrumen,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->sirkuler,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->per_pendamping_1,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->per_pendamping_2,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->apoteker,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->ass_apoteker,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->admin_farmasi,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->pemulasaran,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->fisio,0) }}</th>
    </tfoot>
  </table>
      </div>
    </div>
    <div style="margin-top: 1vh;">
      <div class="pull-left" style="font-size: 12px;">
        {{ number_format($pasien->firstItem(),0) }} - {{ number_format($pasien->lastItem(),0) }} dari {{ number_format($pasien->total(),0) }} data
      </div>                               
      <div class="pagination pagination-sm pull-right">
        {!! $pasien->appends(request()->input())->render("pagination::simple-bootstrap-4"); !!}
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $(document).ready(function() {
      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.34*box.clientHeight);

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
    });
  </script>
@endsection