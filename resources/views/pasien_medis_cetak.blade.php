@extends('layouts.cetak')
@section('title','Jasa Medis')

@section('content')
  <center>
    <span style="font-weight: bold; font-size: 16px; margin-bottom: 20px;">
      <u>PERHITUNGAN JASA MEDIS</u>
    </span>
  </center>

  <table width="100%" class="table-bordered" style="margin-top: 20px; font-size: 13px;">
    <thead>
            <tr>
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
@endsection