@extends('layouts.content')
@section('title','Data Pasien Dalam Perawatan')

@section('style')
  <style type="text/css">
    .DTFC_LeftBodyLiner { overflow-x: hidden; }
  </style>
@endsection

@section('content')

<div class="content">
  <table width="100%" style="font-size: 12px; line-height: 13px;">
    <tr>
      <td width="7%">Nama Pasien</td>
      <td width="1%">:</td>
      <td width="42%">{{ strtoupper($pasien->nama) }}</td>
      <td width="7%">Alamat</td>
      <td width="1%">:</td>
      <td width="42%">{{ strtoupper($pasien->alamat) }}</td>
    </tr>
    <tr>
      <td>No. MR</td>
      <td>:</td>
      <td>{{ $pasien->no_mr }}</td>
      <td>Umun</td>
      <td>:</td>
      <td>{{ $pasien->umur_thn }} Thn.</td>
    </tr>
    <tr>
      <td>Register</td>
      <td>:</td>
      <td>{{ $pasien->register }}</td>
      <td>Kelamin</td>
      <td>:</td>
      <td>{{ $pasien->kelamin }}</td>
    </tr>
    <tr>
      <td>Jenis</td>
      <td>:</td>
      <td>{{ strtoupper($pasien->jenis) }}</td>
      <td>Masuk</td>
      <td>:</td>
      <td>{{ $pasien->masuk }}</td>
    </tr>
    <tr>
      <td>Tagihan</td>
      <td>:</td>
      <td>Rp. {{ number_format($pasien->tagihan,0) }}</td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </table>
</div>

<div class="content content1">
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered">
    <thead>
      <tr>
        <th rowspan="2" style="padding: 0 10px;">Waktu</th>
        <th rowspan="2" style="padding: 0 10px;">Ruang Perawatan</th>
        <th rowspan="2" style="padding: 0 10px;">Ruang Tindakan</th>
        <th rowspan="2" style="padding: 0 10px;">Jasa</th>
        <th rowspan="2" style="padding: 0 10px;">Tarif</th>
        <th rowspan="2" style="padding: 0 10px;">JS</th>
        <th rowspan="2" style="padding: 0 10px;">JP</th>
        <th rowspan="2" style="padding: 0 10px;">Profit</th>
        <th rowspan="2" style="padding: 0 10px;">Penghasil</th>
        <th rowspan="2" style="padding: 0 10px;">Non Penghasil</th>
        <th rowspan="2" style="padding: 0 10px;">DPJP</th>
        <th colspan="2" style="padding: 0 10px;">DPJP Tindakan</th>
        <th colspan="2" style="padding: 0 10px;">Dokter Pengganti</th>
        <th colspan="2" style="padding: 0 10px;">Operator</th>
        <th colspan="2" style="padding: 0 10px;">Anastesi</th>
        <th colspan="2" style="padding: 0 10px;">Pendamping</th>
        <th colspan="2" style="padding: 0 10px;">Konsultasi</th>
        <th colspan="2" style="padding: 0 10px;">Laborat</th>
        <th colspan="2" style="padding: 0 10px;">Pen.Jawab</th>
        <th colspan="2" style="padding: 0 10px;">Radiologi</th>
        <th colspan="2" style="padding: 0 10px;">RR</th>
        <th rowspan="2" style="padding: 0 10px;">Perawat</th>
        <th rowspan="2" style="padding: 0 10px;">Pen.Anastesi</th>
        <th rowspan="2" style="padding: 0 10px;">Per.Ass 1</th>
        <th rowspan="2" style="padding: 0 10px;">Per.Ass 2</th>
        <th rowspan="2" style="padding: 0 10px;">Instrumen</th>
        <th rowspan="2" style="padding: 0 10px;">Sirkuler</th>
        <th rowspan="2" style="padding: 0 10px;">Per.Pend 1</th>
        <th rowspan="2" style="padding: 0 10px;">Per.Pend 2</th>
        <th rowspan="2" style="padding: 0 10px;">Fisioterapis</th>
        <th rowspan="2" style="padding: 0 10px;">Apoteker</th>
        <th rowspan="2" style="padding: 0 10px;">Ass.Apoteker</th>
        <th rowspan="2" style="padding: 0 10px;">Adm.Farmasi</th>
        <th rowspan="2" style="padding: 0 10px;">Administrasi</th>
        <th rowspan="2" style="padding: 0 10px;">Pemulasaran</th>
      </tr>
      <tr>
        <th style="padding: 0 10px;">Nama</th>
        <th style="padding: 0 10px;">Nominal</th>
        <th style="padding: 0 10px;">Nama</th>
        <th style="padding: 0 10px;">Nominal</th>
        <th style="padding: 0 10px;">Nama</th>
        <th style="padding: 0 10px;">Nominal</th>
        <th style="padding: 0 10px;">Nama</th>
        <th style="padding: 0 10px;">Nominal</th>
        <th style="padding: 0 10px;">Nama</th>
        <th style="padding: 0 10px;">Nominal</th>
        <th style="padding: 0 10px;">Nama</th>
        <th style="padding: 0 10px;">Nominal</th>
        <th style="padding: 0 10px;">Nama</th>
        <th style="padding: 0 10px;">Nominal</th>
        <th style="padding: 0 10px;">Nama</th>
        <th style="padding: 0 10px;">Nominal</th>
        <th style="padding: 0 10px;">Nama</th>
        <th style="padding: 0 10px;">Nominal</th>
        <th style="padding: 0 10px;">Nama</th>
        <th style="padding: 0 10px;">Nominal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($layanan as $lay)
      <tr>
        <td class="min">{{ $lay->waktu }}</td>
        <td class="min">{{ $lay->ruang }}</td>
        <td class="min">{{ $lay->ruang_tindakan }}</td>
        <td class="min">{{ $lay->jasa }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->tarif,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->js,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jp,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->profit,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->penghasil,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->non_penghasil,0) }}</td>
        <td class="min">{{ $lay->dpjp }}</td>
        <td class="min">{{ $lay->dpjp_real }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jasa_dpjp,0) }}</td>
        <td class="min">{{ $lay->pengganti }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jasa_pengganti,0) }}</td>
        <td class="min">{{ $lay->operator }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jasa_operator,0) }}</td>
        <td class="min">{{ $lay->anastesi }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jasa_anastesi,0) }}</td>
        <td class="min">{{ $lay->pendamping }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jasa_pendamping,0) }}</td>
        <td class="min">{{ $lay->konsul }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jasa_konsul,0) }}</td>
        <td class="min">{{ $lay->laborat }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jasa_laborat,0) }}</td>
        <td class="min">{{ $lay->tanggung }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jasa_tanggung,0) }}</td>
        <td class="min">{{ $lay->radiologi }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jasa_radiologi,0) }}</td>
        <td class="min">{{ $lay->rr }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jasa_rr,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jp_perawat,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->pen_anastesi,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->per_asisten_1,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->per_asisten_2,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->instrumen,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->sirkuler,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->per_pendamping_1,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->per_pendamping_2,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->fisio,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->apoteker,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->ass_apoteker,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->admin_farmasi,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->administrasi,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->pemulasaran,0) }}</td>
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
      var tinggi = box.clientHeight-(0.2*box.clientHeight);

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
