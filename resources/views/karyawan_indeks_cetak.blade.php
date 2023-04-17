@extends('layouts.cetak')
@section('title','Indeks Karyawan')

@section('style')
  <style type="text/css">
    td {
      padding: 0 5px;
    }
  </style>
@endsection

@section('content')
<center>
  <span style="font-weight: bold; font-size: 16px; margin-bottom: 20px;">
    <u>DAFTAR KARYAWAN {{ strtoupper($a_param->nama) }}</u>
  </span>
</center>

<table width="100%" class="table table-bordered" style="margin: 10px 0; font-size: 12px;">
  <thead>
    <tr>
      <th rowspan="3" width="300" style="vertical-align: middle; text-align: center; padding: 5px;">Nama Karyawan</th>
      <th rowspan="3" width="200" style="vertical-align: middle; text-align: center; padding: 5px;">Pendidikan</th>
      <th colspan="7" style="vertical-align: middle; text-align: center; padding: 5px;">Indeks Dasar</th>
      <th colspan="7" style="vertical-align: middle; text-align: center; padding: 5px;">Indeks Kompetensi</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px;" width="200" rowspan="3">Tempat Tugas</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px;" colspan="3">Indeks Resiko</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px;" colspan="3">Indeks Kegawat Daruratan</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px;" width="200" rowspan="3">Jabatan</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px;" colspan="7">Indeks Jabatan</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px;" colspan="3">Indeks Performance</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px;" rowspan="3">Total Score</th>
    </tr>
    <tr>
      <th style="vertical-align: middle; text-align: center; padding: 5px;" colspan="3">Koreksi</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px;" colspan="3">Masa Kerja</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px; min-width: 30px;" rowspan="2">S</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px;" colspan="3">Pendidikan</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px;" colspan="3">Diklat</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px; min-width: 30px;" rowspan="2">Jml</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px; min-width: 30px;" rowspan="2">N</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px; min-width: 30px;" rowspan="2">B</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px; min-width: 30px;" rowspan="2">S</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px; min-width: 30px;" rowspan="2">N</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px; min-width: 30px;" rowspan="2">B</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px; min-width: 30px;" rowspan="2">S</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px;" colspan="3">Jabatan</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px;" colspan="3">Kepanitiaan</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px; min-width: 30px;" rowspan="2">Jml</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px; min-width: 30px;" rowspan="2">N</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px; min-width: 30px;" rowspan="2">B</th>
      <th style="vertical-align: middle; text-align: center; padding: 5px; min-width: 30px;" rowspan="2">S</th>
    </tr>
    <tr>
      <th style="padding: 5px; text-align: center; min-width: 30px;">N</th>
      <th style="padding: 5px; text-align: center; min-width: 30px;">B</th>
      <th style="padding: 5px; text-align: center; min-width: 30px;">S</th>
      <th style="padding: 5px; text-align: center; min-width: 30px;">N</th>
      <th style="padding: 5px; text-align: center; min-width: 30px;">B</th>
      <th style="padding: 5px; text-align: center; min-width: 30px;">S</th>
      <th style="padding: 5px; text-align: center; min-width: 30px;">N</th>
      <th style="padding: 5px; text-align: center; min-width: 30px;">B</th>
      <th style="padding: 5px; text-align: center; min-width: 30px;">S</th>
      <th style="padding: 5px; text-align: center; min-width: 30px;">N</th>
      <th style="padding: 5px; text-align: center; min-width: 30px;">B</th>
      <th style="padding: 5px; text-align: center; min-width: 30px;">S</th>
      <th style="padding: 5px; text-align: center; min-width: 30px;">N</th>
      <th style="padding: 5px; text-align: center; min-width: 30px;">B</th>
      <th style="padding: 5px; text-align: center; min-width: 30px;">S</th>
      <th style="padding: 5px; text-align: center; min-width: 30px;">N</th>
      <th style="padding: 5px; text-align: center; min-width: 30px;">B</th>
      <th style="padding: 5px; text-align: center; min-width: 30px;">S</th>
    </tr>
  </thead>
  <tbody>
    @foreach($karyawan as $karyawan)
    <tr>              
      <td class="min">{{ $karyawan->nama }}</td>
      <td class="min">{{ $karyawan->pendidikan }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->indeks_dasar,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->dasar_bobot,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->skor_indek,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->masa_kerja,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->masa_kerja_bobot,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->indeks_masa_kerja,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->skor_dasar,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->pend_nilai,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->pend_bobot,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->skor_pend,2) }}</td>              
      <td style="text-align: right;" class="min">{{ number_format($karyawan->diklat_nilai,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->diklat_bobot,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->skor_diklat,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->indeks_komp,2) }}</td>
      <td class="min">{{ $karyawan->temp_tugas }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->resiko_nilai,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->resiko_bobot,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->indeks_resiko,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->gawat_nilai,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->gawat_bobot,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->indeks_kegawat,2) }}</td>
      <td class="min">{{ $karyawan->jabatan }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->jab_nilai,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->jab_bobot,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->skor_jab,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->panitia_nilai,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->panitia_bobot,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->skor_pan,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->indeks_jabatan,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->perform_nilai,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->perform_bobot,2) }}</td>
      <td style="text-align: right;" class="min">{{ number_format($karyawan->indeks_perform,2) }}</td>              
      <td style="text-align: right;">{{ number_format($karyawan->total_indeks,2) }}</td>
    </tr>
    @endforeach
  </tbody>          
</table>     
@endsection