@extends('layouts.content')
@section('title','Data Layanan Pasien')

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <form class="form-inline" method="GET" action="{{ route('pasien_layanan_data') }}" style="margin-top: 5px; margin-bottom: 0;">
    @csrf
      <input type="hidden" name="tampil" value="{{ $tampil }}">
      <input type="hidden" name="cari" value="{{ $cari }}">

      <label>Tanggal</label>
      <input type="date" name="awal" value="{{ $awal }}" style="width: 120px;" required>
      <label>-</label>
      <input type="date" name="akhir" value="{{ $akhir }}" style="width: 120px;" required>

      <select name="id_pasien_jenis">
        <option value="" style="font-style: italic;">JENIS PASIEN</option>
        @foreach($jenis as $jns)
          <option value="{{ $jns->id }}" {{ $id_pasien_jenis == $jns->id? 'selected' : null }}>{{ $jns->jenis }}</option>
        @endforeach
      </select>

      <select name="id_pasien_jenis_rawat">
        <option value="" style="font-style: italic;">JENIS RAWAT</option>
        <option value="1" {{ $id_pasien_jenis_rawat == '1'? 'selected' : null }}>Rawat Jalan</option>
        <option value="2" {{ $id_pasien_jenis_rawat == '2'? 'selected' : null }}>Rawat Inap</option>
      </select>

      <select name="id_ruang" required>
        <option value="" style="font-style: italic;">SEMUA RUANG</option>
        @foreach($ruang as $rng)
          <option value="{{ $rng->id }}" {{ $id_ruang == $rng->id? 'selected' : null }}>{{ $rng->ruang }}</option>
        @endforeach
      </select>

      <select name="id_dpjp">
        <option value="" style="font-style: italic;">SEMUA DPJP</option>
        @foreach($dpjp as $dokter)
          <option value="{{ $dokter->id }}" {{ $id_dpjp == $dokter->id? 'selected' : null }}>{{ $dokter->nama }}</option>
        @endforeach
      </select>

      <button type="submit" class="btn btn-primary" style="margin-top: 0;">
        <i class="fa fa-check"></i>
      </button>
    </form>
  </div>
</div>

<div class="content content1">
  <form method="GET" action="{{ route('pasien_layanan_data') }}" class="form-inline" style="margin-bottom: 5px;">
  @csrf
    <input type="hidden" name="awal" value="{{ $awal }}">
    <input type="hidden" name="akhir" value="{{ $akhir }}">
    <input type="hidden" name="id_pasien_jenis" value="{{ $id_pasien_jenis }}">
    <input type="hidden" name="id_pasien_jenis_rawat" value="{{ $id_pasien_jenis_rawat }}">
    <input type="hidden" name="id_ruang" value="{{ $id_ruang }}">
    <input type="hidden" name="id_dpjp" value="{{ $id_dpjp }}">

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

  <table id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
    <thead style="text-transform: uppercase;">
      <tr>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Nama Pasien</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Jenis</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Waktu</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Jasa</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Ruang Perawatan</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Ruang Layanan</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Tarif</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">JS</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">JP</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Profit</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Non Penghasil</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Penghasil</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">DPJP</th>
        <th colspan="2" style="text-align: center; padding: 0 15px;">DPJP Layanan</th>
        <th colspan="2" style="text-align: center; padding: 0 15px;">Dokter Pengganti</th>
        <th colspan="2" style="text-align: center; padding: 0 15px;">Operator</th>
        <th colspan="2" style="text-align: center; padding: 0 15px;">Anastesi</th>
        <th colspan="2" style="text-align: center; padding: 0 15px;">Pendamping</th>
        <th colspan="2" style="text-align: center; padding: 0 15px;">Konsultasi</th>
        <th colspan="2" style="text-align: center; padding: 0 15px;">Laboratorium</th>
        <th colspan="2" style="text-align: center; padding: 0 15px;">Penanggung Jawab</th>
        <th colspan="2" style="text-align: center; padding: 0 15px;">Radiologi</th>
        <th colspan="2" style="text-align: center; padding: 0 15px;">RR</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Perawat</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Penata Anastesi</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Perawat Anastesi 1</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Perawat Anastesi 2</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Instrumen</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Sirkuler</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Per. Pendamping 1</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Per. Pendamping 2</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Fisioterapi</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Apoteker</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Ass. Apoteker</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Adm. Farmasi</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Administrasi</th>        
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Pemulasaran</th>
      </tr>
      <tr>
        <th style="text-align: center; padding: 0 15px;">Nama</th>
        <th style="text-align: center; padding: 0 15px;">Jasa</th>
        <th style="text-align: center; padding: 0 15px;">Nama</th>
        <th style="text-align: center; padding: 0 15px;">Jasa</th>
        <th style="text-align: center; padding: 0 15px;">Nama</th>
        <th style="text-align: center; padding: 0 15px;">Jasa</th>
        <th style="text-align: center; padding: 0 15px;">Nama</th>
        <th style="text-align: center; padding: 0 15px;">Jasa</th>
        <th style="text-align: center; padding: 0 15px;">Nama</th>
        <th style="text-align: center; padding: 0 15px;">Jasa</th>
        <th style="text-align: center; padding: 0 15px;">Nama</th>
        <th style="text-align: center; padding: 0 15px;">Jasa</th>
        <th style="text-align: center; padding: 0 15px;">Nama</th>
        <th style="text-align: center; padding: 0 15px;">Jasa</th>
        <th style="text-align: center; padding: 0 15px;">Nama</th>
        <th style="text-align: center; padding: 0 15px;">Jasa</th>
        <th style="text-align: center; padding: 0 15px;">Nama</th>
        <th style="text-align: center; padding: 0 15px;">Jasa</th>
        <th style="text-align: center; padding: 0 15px;">Nama</th>
        <th style="text-align: center; padding: 0 15px;">Jasa</th>
      </tr>
    </thead>
    <tbody>
      @foreach($layanan as $lay)
      <tr>
        <td class="min" style="vertical-align: top;">{{ strtoupper($lay->nama) }}</td>
        <td class="min" style="vertical-align: top;">{{ strtoupper($lay->jenis) }} {{ strtoupper($lay->jenis_rawat) }}</td>
        <td class="min" style="vertical-align: top;">{{ $lay->waktu }}</td>
        <td class="min" style="vertical-align: top;">{{ $lay->jasa }}</td>
        <td class="min" style="vertical-align: top;">{{ $lay->ruang_rawat }}</td>
        <td class="min" style="vertical-align: top;">{{ $lay->ruang_tindakan }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->tarif,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->js,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->jp,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->profit,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->non_penghasil,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->penghasil,0) }}</td>
        <td class="min" style="vertical-align: top;">{{ $lay->dpjp }}</td>
        <td class="min" style="vertical-align: top;">{{ $lay->dpjp_real }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->jasa_dpjp,0) }}</td>
        <td class="min" style="vertical-align: top;">{{ $lay->pengganti }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->jasa_pengganti,0) }}</td>
        <td class="min" style="vertical-align: top;">{{ $lay->operator }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->jasa_operator,0) }}</td>
        <td class="min" style="vertical-align: top;">{{ $lay->anastesi }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->jasa_anastesi,0) }}</td>
        <td class="min" style="vertical-align: top;">{{ $lay->pendamping }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->jasa_pendamping,0) }}</td>
        <td class="min" style="vertical-align: top;">{{ $lay->konsul }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->jasa_konsul,0) }}</td>
        <td class="min" style="vertical-align: top;">{{ $lay->laborat }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->jasa_laborat,0) }}</td>
        <td class="min" style="vertical-align: top;">{{ $lay->tanggung }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->jasa_tanggung,0) }}</td>
        <td class="min" style="vertical-align: top;">{{ $lay->radiologi }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->jasa_radiologi,0) }}</td>
        <td class="min" style="vertical-align: top;">{{ $lay->rr }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->jasa_rr,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->jp_perawat,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->pen_anastesi,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->per_asisten_1,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->per_asisten_2,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->instrumen,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->sirkuler,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->per_pendamping_1,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->per_pendamping_2,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->fisio,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->apoteker,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->ass_apoteker,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->admin_farmasi,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->administrasi,0) }}</td>
        <td class="min" style="text-align: right; vertical-align: top;">{{ number_format($lay->pemulasaran,0) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div>
    <div class="pull-left" style="font-size: 12px;">
      Menampilkan {{ number_format($layanan->firstItem(),0) }} - {{ number_format($layanan->lastItem(),0) }} dari {{ number_format($layanan->total(),0) }} data
    </div>                               
    <div class="pagination pagination-small pull-right">
      {!! $layanan->appends(request()->input())->render("pagination::bootstrap-4"); !!}
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      var box = document.querySelector('.content1');
      var tinggi = box.clientHeight-(0.23*box.clientHeight);

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
