@extends('layouts.cetak')
@section('title','Data Layanan')

@section('content')
  <label style="font-weight: bold; font-size: 20px; text-align: center; margin-bottom: 10px;">
    DATA LAYANAN RUANG {{ strtoupper($ruang->ruang) }} TANGGAL {{ strtoupper($tgl_awal) }} S/D {{ strtoupper($tgl_akhir) }}
  </label>

  <table width="100%" id="tabel" class="table table-bordered" style="font-size: 13px;">
    <thead>
      <tr>
        <th rowspan="2" style="text-align: center; padding: 0 5px;">WAKTU</th>              
        <th rowspan="2" style="text-align: center; padding: 0 5px;">NAMA PASIEN</th>
        <th rowspan="2" style="text-align: center; padding: 0 5px;">REGISTER</th>
        <th rowspan="2" style="text-align: center; padding: 0 5px;">NO. MR</th>
        <th rowspan="2" style="text-align: center; padding: 0 5px;">JENIS</th>        
        <th rowspan="2" style="text-align: center; padding: 0 5px;">PETUGAS</th>
        <th rowspan="2" style="text-align: center; padding: 0 5px;">JASA</th>              
        <th rowspan="2" style="text-align: center; padding: 0 5px;">TARIF</th>
        <th colspan="10" style="text-align: center; padding: 0 5px;">Medis</th>        
        <th colspan="13" style="text-align: center; padding: 0 5px;">Perawat Setara</th>        
      </tr>
      <tr>
        <th style="text-align: center; padding: 0 5px;">DPJP</th>
        <th style="text-align: center; padding: 0 5px;">Pengganti</th>
        <th style="text-align: center; padding: 0 5px;">Operator</th>
        <th style="text-align: center; padding: 0 5px;">Anastesi</th>
        <th style="text-align: center; padding: 0 5px;">Pendamping</th>
        <th style="text-align: center; padding: 0 5px;">Konsul</th>
        <th style="text-align: center; padding: 0 5px;">Laborat</th>
        <th style="text-align: center; padding: 0 5px;">Penanggung Jawab</th>
        <th style="text-align: center; padding: 0 5px;">Radiologi</th>
        <th style="text-align: center; padding: 0 5px;">RR</th>
        <th style="text-align: center; padding: 0 5px;">Perawat</th>
        <th style="text-align: center; padding: 0 5px;">Penata Anastesi</th>
        <th style="text-align: center; padding: 0 5px;">Per. Ass. 1</th>
        <th style="text-align: center; padding: 0 5px;">Per. Ass. 2</th>
        <th style="text-align: center; padding: 0 5px;">Instrumen</th>
        <th style="text-align: center; padding: 0 5px;">Sirkuler</th>
        <th style="text-align: center; padding: 0 5px;">Per. Pend. 1</th>
        <th style="text-align: center; padding: 0 5px;">Per. Pend. 2</th>
        <th style="text-align: center; padding: 0 5px;">Apoteker</th>
        <th style="text-align: center; padding: 0 5px;">Ass. Apoteker</th>
        <th style="text-align: center; padding: 0 5px;">Admin Farmasi</th>
        <th style="text-align: center; padding: 0 5px;">Pemulasaran</th>
        <th style="text-align: center; padding: 0 5px;">Fisio</th>
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
        <td style="text-align: right;">{{ number_format($pas->tarif,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->jasa_dpjp,0) }}</td>        
        <td style="text-align: right;">{{ number_format($pas->jasa_pengganti,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->jasa_operator,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->jasa_anastesi,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->jasa_pendamping,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->jasa_konsul,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->jasa_laborat,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->jasa_tanggung,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->jasa_radiologi,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->jasa_rr,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->jp_perawat,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->pen_anastesi,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->per_asisten_1,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->per_asisten_2,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->instrumen,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->sirkuler,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->per_pendamping_1,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->per_pendamping_2,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->apoteker,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->ass_apoteker,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->admin_farmasi,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->pemulasaran,0) }}</td>
        <td style="text-align: right;">{{ number_format($pas->fisio,0) }}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <th colspan="7"></th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->tarif,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_dpjp,0) }}</th>        
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_pengganti,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_operator,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_anastesi,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_pendamping,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_konsul,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_laborat,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_tanggung,0) }}</th>
      <th style="text-align: right; padding: 5px;">{{ number_format($total->jasa_radiologi,0) }}</th>
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
@endsection