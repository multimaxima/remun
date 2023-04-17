@extends('layouts.content')
@section('title','Detil Data Pasien')

@section('content')
<div class="content">
  <table width="100%" style="font-size: 12px; line-height: 13px;">
    <tr>
      <td width="100">Nama Pasien</td>
      <td width="10">:</td>
      <td width="40%" style="font-weight: bold;">{{ strtoupper($pasien->nama) }}</td>
      <td width="100">Umur</td>
      <td width="10">:</td>
      <td>{{ $pasien->umur_thn }} Thn. {{ $pasien->umur_bln }} Bln.</td>
    </tr>
    <tr>
      <td>Alamat</td>
      <td>:</td>
      <td>{{ strtoupper($pasien->alamat) }}</td>
      <td>Jenis</td>
      <td>:</td>
      <td>{{ $pasien->jenis_pasien }}</td>
    </tr>
    <tr>
      <td>No. MR</td>
      <td>:</td>
      <td>{{ $pasien->no_mr }}</td>
      <td>Tagihan</td>
      <td>:</td>
      <td>Rp. {{ number_format($pasien->tagihan,0) }}</td>
    </tr>
    <tr>
      <td>Register</td>
      <td>:</td>
      <td>{{ $pasien->register }}</td>
      <td>DPJP</td>
      <td>:</td>
      <td>{{ $pasien->dpjp }}</td>
    </tr>
  </table>
</div>

<div class="content content1">
  <table id="tabel" class="table table-hover table-striped table-bordered">
    <thead>
      <tr>
        <th rowspan="2" style="padding: 0 15px;">DPJP</th>
        <th rowspan="2" style="padding: 0 15px;">Waktu Entri</th>
        <th rowspan="2" style="padding: 0 15px;">Ruang Perawatan</th>
        <th rowspan="2" style="padding: 0 15px;">Ruang Tindakan</th>
        <th rowspan="2" style="padding: 0 15px;">Jasa</th>
        <th rowspan="2" style="padding: 0 15px;">Tarif (Rp.)</th>
        <th colspan="2" style="padding: 0 15px;">JS</th>
        <th colspan="2" style="padding: 0 15px;">JP</th>
        <th colspan="2" style="padding: 0 15px;">Profit</th>
        <th colspan="2" style="padding: 0 15px;">Penghasil</th>
        <th colspan="2" style="padding: 0 15px;">Non Penghasil</th>
        <th colspan="2" style="padding: 0 15px;">DPJP</th>
        <th colspan="2" style="padding: 0 15px;">Pengganti</th>
        <th colspan="2" style="padding: 0 15px;">Operator</th>
        <th colspan="2" style="padding: 0 15px;">Anastesi</th>
        <th colspan="2" style="padding: 0 15px;">Pendamping</th>
        <th colspan="2" style="padding: 0 15px;">Konsul</th>
        <th colspan="2" style="padding: 0 15px;">Laborat</th>
        <th colspan="2" style="padding: 0 15px;">Penanggung Jawab</th>
        <th colspan="2" style="padding: 0 15px;">Radiologi</th>
        <th colspan="2" style="padding: 0 15px;">RR</th>
        <th rowspan="2" style="padding: 0 15px;">Total Medis (Rp.)</th>
      </tr>
      <tr>
        <th style="padding: 0 15px;" width="30">%</th>
        <th style="padding: 0 15px;" width="40">Rp.</th>
        <th style="padding: 0 15px;" width="30">%</th>
        <th style="padding: 0 15px;" width="40">Rp.</th>
        <th style="padding: 0 15px;" width="30">%</th>
        <th style="padding: 0 15px;" width="40">Rp.</th>
        <th style="padding: 0 15px;" width="30">%</th>
        <th style="padding: 0 15px;" width="40">Rp.</th>
        <th style="padding: 0 15px;" width="30">%</th>
        <th style="padding: 0 15px;" width="40">Rp.</th>
        <th style="padding: 0 15px;" width="30">%</th>
        <th style="padding: 0 15px;" width="40">Rp.</th>
        <th style="padding: 0 15px;" width="30">%</th>
        <th style="padding: 0 15px;" width="40">Rp.</th>
        <th style="padding: 0 15px;" width="30">%</th>
        <th style="padding: 0 15px;" width="40">Rp.</th>
        <th style="padding: 0 15px;" width="30">%</th>
        <th style="padding: 0 15px;" width="40">Rp.</th>
        <th style="padding: 0 15px;" width="30">%</th>
        <th style="padding: 0 15px;" width="40">Rp.</th>
        <th style="padding: 0 15px;" width="30">%</th>
        <th style="padding: 0 15px;" width="40">Rp.</th>
        <th style="padding: 0 15px;" width="30">%</th>
        <th style="padding: 0 15px;" width="40">Rp.</th>
        <th style="padding: 0 15px;" width="30">%</th>
        <th style="padding: 0 15px;" width="40">Rp.</th>
        <th style="padding: 0 15px;" width="30">%</th>
        <th style="padding: 0 15px;" width="40">Rp.</th>
        <th style="padding: 0 15px;" width="30">%</th>
        <th style="padding: 0 15px;" width="40">Rp.</th>
      </tr>
    </thead>
    <tbody>
    @foreach($ruang as $rng)
      <tr>
        <td class="min">{{ $rng->dpjp }}</td>
        <td class="min">{{ $rng->waktu }}</td>
        <td class="min">{{ $rng->ruang }}</td>
        <td class="min">{{ $rng->ruang_sub }}</td>
        <td class="min">{{ $rng->jasa }}</td>
        <td class="min" style="text-align: right;">@if($rng->tarif > 0){{ number_format($rng->tarif,0) }}@endif</td>
        <td class="min" style="text-align: right;">@if($rng->n_js > 0){{ number_format($rng->n_js,0) }} %@endif</td>
        <td class="min" style="text-align: right;">@if($rng->js > 0){{ number_format($rng->js,0) }}@endif</td>
        <td class="min" style="text-align: right;">@if($rng->n_jp > 0){{ number_format($rng->n_jp,0) }} %@endif</td>
        <td class="min" style="text-align: right;">@if($rng->jp > 0){{ number_format($rng->jp,0) }}@endif</td>
        <td class="min" style="text-align: right;">@if($rng->n_profit > 0){{ number_format($rng->n_profit,0) }} %@endif</td>
        <td class="min" style="text-align: right;">@if($rng->profit > 0){{ number_format($rng->profit,0) }}@endif</td>
        <td class="min" style="text-align: right;">@if($rng->n_penghasil > 0){{ number_format($rng->n_penghasil,0) }} %@endif</td>
        <td class="min" style="text-align: right;">@if($rng->penghasil > 0){{ number_format($rng->penghasil,0) }}@endif</td>
        <td class="min" style="text-align: right;">@if($rng->n_non_penghasil > 0){{ number_format($rng->n_non_penghasil,0) }} %@endif</td>
        <td class="min" style="text-align: right;">@if($rng->non_penghasil > 0){{ number_format($rng->non_penghasil,0) }}@endif</td>
        <td class="min" style="text-align: right;">@if($rng->n_dpjp > 0){{ number_format($rng->n_dpjp,0) }} %@endif</td>
        <td class="min" style="text-align: right;">@if($rng->jasa_dpjp > 0){{ number_format($rng->jasa_dpjp,0) }}@endif</td>
        <td class="min" style="text-align: right;">@if($rng->n_pengganti > 0){{ number_format($rng->n_pengganti,0) }} %@endif</td>
        <td class="min" style="text-align: right;">@if($rng->jasa_pengganti > 0){{ number_format($rng->jasa_pengganti,0) }}@endif</td>
        <td class="min" style="text-align: right;">@if($rng->n_operator > 0){{ number_format($rng->n_operator,0) }} %@endif</td>
        <td class="min" style="text-align: right;">@if($rng->jasa_operator > 0){{ number_format($rng->jasa_operator,0) }}@endif</td>
        <td class="min" style="text-align: right;">@if($rng->n_anastesi > 0){{ number_format($rng->n_anastesi,0) }} %@endif</td>
        <td class="min" style="text-align: right;">@if($rng->jasa_anastesi > 0){{ number_format($rng->jasa_anastesi,0) }}@endif</td>
        <td class="min" style="text-align: right;">@if($rng->n_pendamping > 0){{ number_format($rng->n_pendamping,0) }} %@endif</td>
        <td class="min" style="text-align: right;">@if($rng->jasa_pendamping > 0){{ number_format($rng->jasa_pendamping,0) }}@endif</td>
        <td class="min" style="text-align: right;">@if($rng->n_konsul > 0){{ number_format($rng->n_konsul,0) }} %@endif</td>
        <td class="min" style="text-align: right;">@if($rng->jasa_konsul > 0){{ number_format($rng->jasa_konsul,0) }}@endif</td>
        <td class="min" style="text-align: right;">@if($rng->n_laborat > 0){{ number_format($rng->n_laborat,0) }} %@endif</td>
        <td class="min" style="text-align: right;">@if($rng->jasa_laborat > 0){{ number_format($rng->jasa_laborat,0) }}@endif</td>
        <td class="min" style="text-align: right;">@if($rng->n_tanggung > 0){{ number_format($rng->n_tanggung,0) }} %@endif</td>
        <td class="min" style="text-align: right;">@if($rng->jasa_tanggung > 0){{ number_format($rng->jasa_tanggung,0) }}@endif</td>
        <td class="min" style="text-align: right;">@if($rng->n_radiologi > 0){{ number_format($rng->n_radiologi,0) }} %@endif</td>
        <td class="min" style="text-align: right;">@if($rng->jasa_radiologi > 0){{ number_format($rng->jasa_radiologi,0) }}@endif</td>
        <td class="min" style="text-align: right;">@if($rng->n_rr > 0){{ number_format($rng->n_rr,0) }} %@endif</td>
        <td class="min" style="text-align: right;">@if($rng->jasa_rr > 0){{ number_format($rng->jasa_rr,0) }}@endif</td>
        <td class="min" style="text-align: right;">@if($rng->medis > 0){{ number_format($rng->medis,0) }}@endif</td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      var box = document.querySelector('.content1');
      var tinggi = box.clientHeight-(0.18*box.clientHeight);

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
