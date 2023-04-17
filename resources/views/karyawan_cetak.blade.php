@extends('layouts.cetak')
@section('title','Daftar Karyawan')

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
        <th style="padding: 10px; text-align: center; vertical-align: middle;">NAMA KARYAWAN</th>
        <th style="padding: 10px; text-align: center; vertical-align: middle;">STATUS</th>
        <th style="padding: 10px; text-align: center; vertical-align: middle;">GOL</th>
        <th style="padding: 10px; text-align: center; vertical-align: middle;">NPWP</th>
        <th style="padding: 10px; text-align: center; vertical-align: middle;" width="30">PAJAK</th>
        <th style="padding: 10px; text-align: center; vertical-align: middle;">BAGIAN</th>
        <th style="padding: 10px; text-align: center; vertical-align: middle;">RUANG</th>
        <th style="padding: 10px; text-align: center; vertical-align: middle;">MASA KERJA</th>
        <th style="padding: 10px; text-align: center; vertical-align: middle;" width="50">GAPOK</th>
        <th style="padding: 10px; text-align: center; vertical-align: middle;" width="50">TPP</th>
        <th style="padding: 10px; text-align: center; vertical-align: middle;" width="30">SCORE</th>
        <th style="padding: 10px; text-align: center; vertical-align: middle;" width="30">REKENING</th>
      </tr>  
    </thead>
    <tbody>
      @foreach($karyawan as $karyawan)
      <tr>              
        <td>{{ $karyawan->nama }}</td>
        <td>{{ strtoupper($karyawan->status) }}</td>
        <td>{{ strtoupper($karyawan->golongan) }}</td>
        <td class="min">{{ strtoupper($karyawan->npwp) }}</td>
        <td style="text-align: right;">{{ number_format($karyawan->pajak,2) }}%</td>
        <td>{{ strtoupper($karyawan->bagian) }}</td>
        <td>{{ strtoupper($karyawan->ruang) }}</td>
        <td class="min">{{ strtoupper($karyawan->masa_kerja) }}</td>
        <td style="text-align: right;">{{ number_format($karyawan->gapok,0) }}</td>
        <td style="text-align: right;">{{ number_format($karyawan->tpp,0) }}</td>
        <td style="text-align: right;">{{ number_format($karyawan->skore,2) }}</td>
        <td class="min">{{ strtoupper($karyawan->rekening) }}</td>
      </tr>
      @endforeach
    </tbody>          
  </table>     
@endsection