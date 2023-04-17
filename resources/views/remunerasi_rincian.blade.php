@extends('layouts.content')
@section('title','Rincian Jasa Remunerasi')

@section('style')
  <style type="text/css">
    .white {
      color: transparent;
    }
  </style>
@endsection

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12">
        <form class="form-inline" method="GET" action="{{ route('remunerasi_rincian') }}" style="margin-top: 5px; margin-bottom: 0;">
        @csrf
          <input type="hidden" name="id" value="{{ $remun }}">
          <input type="hidden" name="tampil" value="{{ $tampil }}">
          <input type="hidden" name="cari" value="{{ $cari }}">

          <select name="id_jenis" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">SEMUA JENIS PASIEN</option>
            @foreach($jenis as $jns)
              <option value="{{ $jns->id }}" {{ $id_jenis == $jns->id? 'selected' : null }}>{{ strtoupper($jns->jenis) }}</option>
            @endforeach
          </select>

          <select name="id_rawat" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">SEMUA PERAWATAN</option>
            <option value="1" {{ $id_rawat == 1? 'selected' : null }}>RAWAT JALAN</option>
            <option value="2" {{ $id_rawat == 2? 'selected' : null }}>RAWAT INAP</option>
          </select>

          <select name="id_ruang" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">SEMUA RUANG PERAWATAN</option>
            @foreach($ruang as $rng)
              <option value="{{ $rng->id }}" {{ $id_ruang == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
            @endforeach
          </select>

          <select name="id_ruang_sub" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">SEMUA RUANG LAYANAN</option>
            @foreach($ruang as $rng)
              <option value="{{ $rng->id }}" {{ $id_ruang_sub == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
            @endforeach
          </select>

          <select name="id_dpjp" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">SEMUA DPJP</option>
            @foreach($dpjp as $dokter)
              <option value="{{ $dokter->id }}" {{ $id_dpjp == $dokter->id? 'selected' : null }}>{{ $dokter->nama }}</option>
            @endforeach
          </select>
        </form>  
      </div>
    </div>
  </div>
</div>

<div class="content" style="max-height: 70vh;">
  <form method="GET" action="{{ route('remunerasi_rincian') }}" class="form-inline" style="margin-bottom: 5px;">
  @csrf
    <input type="hidden" name="id" value="{{ $remun }}">
    <input type="hidden" name="id_jenis" value="{{ $id_jenis }}">
    <input type="hidden" name="id_rawat" value="{{ $id_rawat }}">
    <input type="hidden" name="id_ruang" value="{{ $id_ruang }}">
    <input type="hidden" name="id_ruang_sub" value="{{ $id_ruang_sub }}">
    <input type="hidden" name="id_dpjp" value="{{ $id_dpjp }}">

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
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
    <thead>
      <tr>
        <th rowspan="2" style="text-align: center; padding: 0 10px;">WAKTU ENTRI</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px;">PETUGAS ENTRI</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px;">NAMA PASIEN</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px;">MR</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px;">REGISTER</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px;">JENIS</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px;">PERAWATAN</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px;">MASUK</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px;">KELUAR</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px;">RUANG PERAWATAN</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px;">RUANG LAYANAN</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px;">JASA</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">TARIF</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">JS</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">JP</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">PROFIT</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">PENGHASIL</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">NON PENGHASIL</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px;">DPJP</th>
        <th colspan="4" style="text-align: center; padding: 0 10px;">DPJP RUANG</th>
        <th colspan="4" style="text-align: center; padding: 0 10px;">DOKTER PENGGANTI</th>
        <th colspan="6" style="text-align: center; padding: 0 10px;">OPERATOR</th>
        <th colspan="4" style="text-align: center; padding: 0 10px;">ANASTESI</th>
        <th colspan="4" style="text-align: center; padding: 0 10px;">PENDAMPING</th>
        <th colspan="4" style="text-align: center; padding: 0 10px;">KONSUL</th>
        <th colspan="4" style="text-align: center; padding: 0 10px;">LABORAT</th>
        <th colspan="4" style="text-align: center; padding: 0 10px;">PENANGGUNG JAWAB</th>
        <th colspan="4" style="text-align: center; padding: 0 10px;">RADIOLOGI</th>
        <th colspan="4" style="text-align: center; padding: 0 10px;">RR</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">PERAWAT</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">PENATA ANASTESI</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">PER.ASISTEN 1</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">PER.ASISTEN 2</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">INSTRUMEN</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">SIRKULER</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">PER.PENDAMPING 1</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">PER.PENDAMPING 2</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">APOTEKER</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">ASS. APOTEKER</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">ADMIN FARMASI</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">PEMULASARAN</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">FISIOTERAPIS</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">ADMINISTRASI</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">POS REMUN</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">DIREKSI</th>
        <th colspan="2" style="text-align: center; padding: 0 10px;">STAF DIREKSI</th>        
      </tr>
      <tr>
        <th class="min" style="text-align: center; padding: 0 10px;">REAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">CLAIM</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NAMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">REAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">CLAIM</th>
        <th class="min" style="text-align: center; padding: 0 10px;">DITERIMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NAMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">REAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">CLAIM</th>
        <th class="min" style="text-align: center; padding: 0 10px;">DITERIMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NAMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">REAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">CLAIM</th>
        <th class="min" style="text-align: center; padding: 0 10px;">REAL DITERIMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">TAMBAHAN</th>
        <th class="min" style="text-align: center; padding: 0 10px;">DITERIMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NAMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">REAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">CLAIM</th>
        <th class="min" style="text-align: center; padding: 0 10px;">DITERIMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NAMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">REAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">CLAIM</th>
        <th class="min" style="text-align: center; padding: 0 10px;">DITERIMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NAMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">REAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">CLAIM</th>
        <th class="min" style="text-align: center; padding: 0 10px;">DITERIMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NAMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">REAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">CLAIM</th>
        <th class="min" style="text-align: center; padding: 0 10px;">DITERIMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NAMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">REAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">CLAIM</th>
        <th class="min" style="text-align: center; padding: 0 10px;">DITERIMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NAMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">REAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">CLAIM</th>
        <th class="min" style="text-align: center; padding: 0 10px;">DITERIMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NAMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">REAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">CLAIM</th>
        <th class="min" style="text-align: center; padding: 0 10px;">DITERIMA</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
        <th class="min" style="text-align: center; padding: 0 10px;">%</th>
        <th class="min" style="text-align: center; padding: 0 10px;">NOMINAL</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rincian as $rinc)
      <tr>
        <td class="min">{{ $rinc->waktu }}</td>
        <td class="min">{{ $rinc->petugas }}</td>
        <td class="min">{{ $rinc->pasien }}</td>
        <td class="min">{{ $rinc->no_mr }}</td>
        <td class="min">{{ $rinc->register }}</td>
        <td class="min">{{ strtoupper($rinc->jenis) }}</td>
        <td class="min">{{ $rinc->rawat }}</td>
        <td class="min">{{ $rinc->masuk }}</td>
        <td class="min">{{ $rinc->keluar }}</td>
        <td class="min">{{ $rinc->ruang_perawatan }}</td>
        <td class="min">{{ $rinc->ruang_tindakan }}</td>
        <td class="min">{{ $rinc->jasa }}</td>
        <td style="text-align: right;" class="{{ $rinc->real_tarif == 0 ? 'white' : '' }}">{{ number_format($rinc->real_tarif,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->tarif == 0 ? 'white' : '' }}">{{ number_format($rinc->tarif,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_js == 0 ? 'white' : '' }}">{{ number_format($rinc->n_js,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->js == 0 ? 'white' : '' }}">{{ number_format($rinc->js,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_jp == 0 ? 'white' : '' }}">{{ number_format($rinc->n_jp,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jp == 0 ? 'white' : '' }}">{{ number_format($rinc->jp,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_profit == 0 ? 'white' : '' }}">{{ number_format($rinc->n_profit,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->profit == 0 ? 'white' : '' }}">{{ number_format($rinc->profit,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_penghasil == 0 ? 'white' : '' }}">{{ number_format($rinc->n_penghasil,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->penghasil == 0 ? 'white' : '' }}">{{ number_format($rinc->penghasil,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_non_penghasil == 0 ? 'white' : '' }}">{{ number_format($rinc->n_non_penghasil,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->non_penghasil == 0 ? 'white' : '' }}">{{ number_format($rinc->non_penghasil,2) }}</td>
        <td class="min">{{ $rinc->dpjp }}</td>
        <td class="min">{{ $rinc->dpjp_real }}</td>
        <td style="text-align: right;" class="{{ $rinc->real_jasa_dpjp == 0 ? 'white' : '' }}">{{ number_format($rinc->real_jasa_dpjp,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_dpjp == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_dpjp,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_dpjp_diterima == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_dpjp_diterima,2) }}</td>
        <td class="min">{{ $rinc->pengganti }}</td>
        <td style="text-align: right;" class="{{ $rinc->real_jasa_pengganti == 0 ? 'white' : '' }}">{{ number_format($rinc->real_jasa_pengganti,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_pengganti == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_pengganti,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_pengganti_diterima == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_pengganti_diterima,2) }}</td>
        <td class="min">{{ $rinc->operator }}</td>
        <td style="text-align: right;" class="{{ $rinc->real_jasa_operator == 0 ? 'white' : '' }}">{{ number_format($rinc->real_jasa_operator,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_operator == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_operator,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_operator_diterima == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_operator_diterima,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_operator_min == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_operator_min,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_operator_diterima + $rinc->jasa_operator_min == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_operator_diterima + $rinc->jasa_operator_min,2) }}</td>
        <td class="min">{{ $rinc->anastesi }}</td>
        <td style="text-align: right;" class="{{ $rinc->real_jasa_anastesi == 0 ? 'white' : '' }}">{{ number_format($rinc->real_jasa_anastesi,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_anastesi == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_anastesi,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_anastesi_diterima == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_anastesi_diterima,2) }}</td>
        <td class="min">{{ $rinc->pendamping }}</td>
        <td style="text-align: right;" class="{{ $rinc->real_jasa_pendamping == 0 ? 'white' : '' }}">{{ number_format($rinc->real_jasa_pendamping,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_pendamping == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_pendamping,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_pendamping_diterima == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_pendamping_diterima,2) }}</td>
        <td class="min">{{ $rinc->konsul }}</td>
        <td style="text-align: right;" class="{{ $rinc->real_jasa_konsul == 0 ? 'white' : '' }}">{{ number_format($rinc->real_jasa_konsul,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_konsul == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_konsul,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_konsul_diterima == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_konsul_diterima,2) }}</td>
        <td class="min">{{ $rinc->laborat }}</td>
        <td style="text-align: right;" class="{{ $rinc->real_jasa_laborat == 0 ? 'white' : '' }}">{{ number_format($rinc->real_jasa_laborat,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_laborat == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_laborat,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_laborat_diterima == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_laborat_diterima,2) }}</td>
        <td class="min">{{ $rinc->tanggung }}</td>
        <td style="text-align: right;" class="{{ $rinc->real_jasa_tanggung == 0 ? 'white' : '' }}">{{ number_format($rinc->real_jasa_tanggung,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_tanggung == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_tanggung,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_tanggung_diterima == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_tanggung_diterima,2) }}</td>
        <td class="min">{{ $rinc->radiologi }}</td>
        <td style="text-align: right;" class="{{ $rinc->real_jasa_radiologi == 0 ? 'white' : '' }}">{{ number_format($rinc->real_jasa_radiologi,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_radiologi == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_radiologi,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_radiologi_diterima == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_radiologi_diterima,2) }}</td>
        <td class="min">{{ $rinc->rr }}</td>
        <td style="text-align: right;" class="{{ $rinc->real_jasa_rr == 0 ? 'white' : '' }}">{{ number_format($rinc->real_jasa_rr,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_rr == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_rr,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jasa_rr_diterima == 0 ? 'white' : '' }}">{{ number_format($rinc->jasa_rr_diterima,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_jp_perawat == 0 ? 'white' : '' }}">{{ number_format($rinc->n_jp_perawat,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->jp_perawat == 0 ? 'white' : '' }}">{{ number_format($rinc->jp_perawat,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_pen_anastesi == 0 ? 'white' : '' }}">{{ number_format($rinc->n_pen_anastesi,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->pen_anastesi == 0 ? 'white' : '' }}">{{ number_format($rinc->pen_anastesi,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_per_asisten_1 == 0 ? 'white' : '' }}">{{ number_format($rinc->n_per_asisten_1,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->per_asisten_1 == 0 ? 'white' : '' }}">{{ number_format($rinc->per_asisten_1,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_per_asisten_2 == 0 ? 'white' : '' }}">{{ number_format($rinc->n_per_asisten_2,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->per_asisten_2 == 0 ? 'white' : '' }}">{{ number_format($rinc->per_asisten_2,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_instrumen == 0 ? 'white' : '' }}">{{ number_format($rinc->n_instrumen,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->instrumen == 0 ? 'white' : '' }}">{{ number_format($rinc->instrumen,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_sirkuler == 0 ? 'white' : '' }}">{{ number_format($rinc->n_sirkuler,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->sirkuler == 0 ? 'white' : '' }}">{{ number_format($rinc->sirkuler,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_per_pendamping_1 == 0 ? 'white' : '' }}">{{ number_format($rinc->n_per_pendamping_1,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->per_pendamping_1 == 0 ? 'white' : '' }}">{{ number_format($rinc->per_pendamping_1,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_per_pendamping_2 == 0 ? 'white' : '' }}">{{ number_format($rinc->n_per_pendamping_2,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->per_pendamping_2 == 0 ? 'white' : '' }}">{{ number_format($rinc->per_pendamping_2,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_apoteker == 0 ? 'white' : '' }}">{{ number_format($rinc->n_apoteker,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->apoteker == 0 ? 'white' : '' }}">{{ number_format($rinc->apoteker,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_ass_apoteker == 0 ? 'white' : '' }}">{{ number_format($rinc->n_ass_apoteker,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->ass_apoteker == 0 ? 'white' : '' }}">{{ number_format($rinc->ass_apoteker,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_admin_farmasi == 0 ? 'white' : '' }}">{{ number_format($rinc->n_admin_farmasi,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->admin_farmasi == 0 ? 'white' : '' }}">{{ number_format($rinc->admin_farmasi,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_pemulasaran == 0 ? 'white' : '' }}">{{ number_format($rinc->n_pemulasaran,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->pemulasaran == 0 ? 'white' : '' }}">{{ number_format($rinc->pemulasaran,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_fisio == 0 ? 'white' : '' }}">{{ number_format($rinc->n_fisio,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->fisio == 0 ? 'white' : '' }}">{{ number_format($rinc->fisio,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_administrasi == 0 ? 'white' : '' }}">{{ number_format($rinc->n_administrasi,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->administrasi == 0 ? 'white' : '' }}">{{ number_format($rinc->administrasi,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_pos_remun == 0 ? 'white' : '' }}">{{ number_format($rinc->n_pos_remun,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->pos_remun == 0 ? 'white' : '' }}">{{ number_format($rinc->pos_remun,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_direksi == 0 ? 'white' : '' }}">{{ number_format($rinc->n_direksi,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->direksi == 0 ? 'white' : '' }}">{{ number_format($rinc->direksi,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->n_staf_direksi == 0 ? 'white' : '' }}">{{ number_format($rinc->n_staf_direksi,2) }}</td>
        <td style="text-align: right;" class="{{ $rinc->staf_direksi == 0 ? 'white' : '' }}">{{ number_format($rinc->staf_direksi,2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div>
    <div class="pull-left" style="font-size: 12px;">
      Menampilkan {{ number_format($rincian->firstItem(),0) }} - {{ number_format($rincian->lastItem(),0) }} dari {{ number_format($rincian->total(),0) }} data
    </div>                               
    <div class="pagination pagination-small pull-right">
      {!! $rincian->appends(request()->input())->render("pagination::bootstrap-4"); !!}
    </div>
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
        "order": [[ 1, "asc" ]],
        paging: false,
        searching: false,
        info: false,
      });
    });
  </script> 
@endsection