@extends('layouts.content')
@section('title','Data Layanan')

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12">
        <form class="form-inline justify-content-center" method="GET" action="{{ route('pasien_ruang_data') }}"  style="margin-top: 5px; margin-bottom: 0;">
        @csrf
          <input type="hidden" name="cari" value="{{ $cari }}">
          <input type="hidden" name="tampil" value="{{ $tampil }}">

          <label>Tanggal</label>
          <input type="date" name="awal" value="{{ $awal }}" style="width: 130px;">
          <label>s/d</label>
          <input type="date" name="akhir" value="{{ $akhir }}" style="width: 130px;">
          
          <select name="id_jenis">
            <option value="" style="font-style: italic;">SEMUA JENIS</option>
            @foreach($jenis as $jns)
              <option value="{{ $jns->id }}" {{ $id_jenis == $jns->id? 'selected' : null }}>{{ strtoupper($jns->jenis) }}</option>
            @endforeach
          </select>
          
          <div class="btn-group" style="margin-top: 0;">
            <button type="submit" class="btn btn-primary">TAMPILKAN</button>
            <button type="submit" form="cetak" class="btn btn-primary">CETAK</button>
          </div>
        </form>

        <form hidden method="GET" id="cetak" action="{{ route('pasien_ruang_data_cetak') }}" target="_blank">
        @csrf
          <input type="date" name="awal" value="{{ $awal }}">
          <input type="date" name="akhir" value="{{ $akhir }}">
        </form>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <form method="GET" action="{{ route('pasien_ruang_data') }}" class="form-inline" style="margin-bottom: 5px;">
  @csrf
    <input type="hidden" name="awal" value="{{ $awal }}">
    <input type="hidden" name="akhir" value="{{ $akhir }}">
    <input type="hidden" name="id_jenis" value="{{ $id_jenis }}">
    <input type="hidden" name="akhir" value="{{ $akhir }}">

    Menampilkan
    <select onchange="this.form.submit();" name="tampil">
      <option value="10" {{ $tampil == '10'? 'selected' : null }}>10</option>
      <option value="25" {{ $tampil == '25'? 'selected' : null }}>25</option>
      <option value="50" {{ $tampil == '50'? 'selected' : null }}>50</option>
      <option value="100" {{ $tampil == '100'? 'selected' : null }}>100</option>
      <option value="9999999999999" {{ $tampil == '9999999999999'? 'selected' : null }}>Semua</option>
    </select> data

    <input type="text" name="cari" class="pull-right" placeholder="Cari..." value="{{ $cari }}">
  </form>

  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered">
    <thead>
      <tr>
        <th class="min" rowspan="3" style="padding: 0 20px;">WAKTU</th>              
        <th class="min" rowspan="3" style="padding: 0 20px;">NAMA PASIEN</th>
        <th class="min" rowspan="3" style="padding: 0 20px;">REGISTER</th>
        <th class="min" rowspan="3" style="padding: 0 20px;">NO. MR</th>
        <th class="min" rowspan="3" style="padding: 0 20px;">JENIS</th>        
        <th class="min" rowspan="3" style="padding: 0 20px;">PETUGAS</th>
        <th class="min" rowspan="3" style="padding: 0 20px;">JASA</th>              
        <th class="min" rowspan="3" style="padding: 0 20px;">TARIF</th>
        <th colspan="20" style="padding: 0 20px;">Medis</th>        
        <th colspan="13" style="padding: 0 20px;">Perawat Setara</th>        
      </tr>
      <tr>
        <th colspan="2" style="padding: 0 20px;">DPJP</th>
        <th colspan="2" style="padding: 0 20px;">Pengganti</th>
        <th colspan="2" style="padding: 0 20px;">Operator</th>
        <th colspan="2" style="padding: 0 20px;">Anastesi</th>
        <th colspan="2" style="padding: 0 20px;">Pendamping</th>
        <th colspan="2" style="padding: 0 20px;">Konsul</th>
        <th colspan="2" style="padding: 0 20px;">Laborat</th>
        <th colspan="2" style="padding: 0 20px;">Penanggung Jawab</th>
        <th colspan="2" style="padding: 0 20px;">Radiologi</th>
        <th colspan="2" style="padding: 0 20px;">RR</th>
        <th rowspan="2" class="min" style="padding: 0 20px;">Perawat</th>
        <th rowspan="2" class="min" style="padding: 0 20px;">Penata Anastesi</th>
        <th rowspan="2" class="min" style="padding: 0 20px;">Per. Ass. 1</th>
        <th rowspan="2" class="min" style="padding: 0 20px;">Per. Ass. 2</th>
        <th rowspan="2" class="min" style="padding: 0 20px;">Instrumen</th>
        <th rowspan="2" class="min" style="padding: 0 20px;">Sirkuler</th>
        <th rowspan="2" class="min" style="padding: 0 20px;">Per. Pend. 1</th>
        <th rowspan="2" class="min" style="padding: 0 20px;">Per. Pend. 2</th>
        <th rowspan="2" class="min" style="padding: 0 20px;">Apoteker</th>
        <th rowspan="2" class="min" style="padding: 0 20px;">Ass. Apoteker</th>
        <th rowspan="2" class="min" style="padding: 0 20px;">Admin Farmasi</th>
        <th rowspan="2" class="min" style="padding: 0 20px;">Pemulasaran</th>
        <th rowspan="2" class="min" style="padding: 0 20px;">Fisio</th>
      </tr>
      <tr>
        <th style="padding: 0 20px;">Nama</th>
        <th style="padding: 0 20px;">Jasa</th>
        <th style="padding: 0 20px;">Nama</th>
        <th style="padding: 0 20px;">Jasa</th>
        <th style="padding: 0 20px;">Nama</th>
        <th style="padding: 0 20px;">Jasa</th>
        <th style="padding: 0 20px;">Nama</th>
        <th style="padding: 0 20px;">Jasa</th>
        <th style="padding: 0 20px;">Nama</th>
        <th style="padding: 0 20px;">Jasa</th>
        <th style="padding: 0 20px;">Nama</th>
        <th style="padding: 0 20px;">Jasa</th>
        <th style="padding: 0 20px;">Nama</th>
        <th style="padding: 0 20px;">Jasa</th>
        <th style="padding: 0 20px;">Nama</th>
        <th style="padding: 0 20px;">Jasa</th>
        <th style="padding: 0 20px;">Nama</th>
        <th style="padding: 0 20px;">Jasa</th>
        <th style="padding: 0 20px;">Nama</th>
        <th style="padding: 0 20px;">Jasa</th>
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

  <div>
    <div class="pull-left" style="font-size: 12px;">
      Menampilkan {{ number_format($pasien->firstItem(),0) }} - {{ number_format($pasien->lastItem(),0) }} dari {{ number_format($pasien->total(),0) }} data
    </div>                               
    <div class="pagination pagination-small pull-right">
      {!! $pasien->appends(request()->input())->render("pagination::bootstrap-4"); !!}
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