@extends('layouts.cetak')
@section('title','Jasa Karyawan')

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
      <u>JASA KARYAWAN {{ strtoupper($a_param->nama) }}</u>
    </span>
  </center>

  <table width="100%" class="table table-bordered" style="margin: 10px 0; font-size: 12px;">
    <thead>      
      <tr>      
        <th width="300" rowspan="2" style="text-align: center; vertical-align: middle;">Nama Karyawan</th>
        <th colspan="5" style="text-align: center; vertical-align: middle;">Non Penghasil</th>
        <th colspan="11" style="text-align: center; vertical-align: middle;">Penghasil</th>
      </tr>
      <tr>
        <th style="text-align: center; vertical-align: middle;">Pos Renumerasi</th>
        <th style="text-align: center; vertical-align: middle;">Insentif Kel. Perawat / Setara</th>
        <th style="text-align: center; vertical-align: middle;">Direksi</th>
        <th style="text-align: center; vertical-align: middle;">Staf Direksi</th>
        <th style="text-align: center; vertical-align: middle;">JP Langsung Administrasi</th>
        <th style="text-align: center; vertical-align: middle;">JP Langsung Perawat Setara</th>
        <th style="text-align: center; vertical-align: middle;">Apoteker</th>
        <th style="text-align: center; vertical-align: middle;">Asisten Apoteker</th>
        <th style="text-align: center; vertical-align: middle;">Admin Farmasi</th>
        <th style="text-align: center; vertical-align: middle;">Penata Anastesi</th>
        <th style="text-align: center; vertical-align: middle;">Perawat Asistensi 1</th>
        <th style="text-align: center; vertical-align: middle;">Perawat Asistensi 2</th>
        <th style="text-align: center; vertical-align: middle;">Instrumen</th>
        <th style="text-align: center; vertical-align: middle;">Sirkuler</th>                    
        <th style="text-align: center; vertical-align: middle;">Perawat Pendamping 1</th>      
        <th style="text-align: center; vertical-align: middle;">Perawat Pendamping 2</th>      
      </tr>
    </thead>
    <tbody>
      @foreach($karyawan as $karyawan)
      <tr>              
        <td>{{ $karyawan->nama }}</td>
        <td style="text-align: center;">
          @if($karyawan->pos_remun == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;">
          @if($karyawan->insentif_perawat == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;">
          @if($karyawan->direksi == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;">
          @if($karyawan->staf == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;">
          @if($karyawan->jp_admin == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;">
          @if($karyawan->jp_perawat == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;">
          @if($karyawan->apoteker == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;">
          @if($karyawan->ass_apoteker == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;">
          @if($karyawan->admin_farmasi == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;">
          @if($karyawan->pen_anastesi == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;">
          @if($karyawan->per_asisten_1 == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;">
          @if($karyawan->per_asisten_2 == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;">
          @if($karyawan->instrumen == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;">
          @if($karyawan->sirkuler == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;">
          @if($karyawan->per_pendamping_1 == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;">
          @if($karyawan->per_pendamping_2 == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>          
  </table>     
@endsection