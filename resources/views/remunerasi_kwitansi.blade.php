@extends('layouts.cetak')
@section('title','Kwitansi Remunerasi')

@section('content')
  <center>
    <span style="font-weight: bold; font-size: 18px;">
      KWITANSI PERHITUNGAN REMUNERASI PASIEN {{ $master->jenis }}            
      <br>
      TANGGAL {{ strtoupper($master->awal) }} S/D {{ strtoupper($master->akhir) }}
    </span>
  </center>

  <table width="100%" class="table table-bordered" style="margin-top: 20px;">
    <thead>
      <th style="text-align: center; font-weight: bold; padding: 10px;" width="100">NO.</th>
      <th colspan="7" style="text-align: center; font-weight: bold; padding: 10px;">URAIAN</th>
      <th style="text-align: center; font-weight: bold; padding: 10px;" width="100">%</th>
      <th style="text-align: center; font-weight: bold; padding: 10px;" width="200">JUMLAH</th>
      <th style="text-align: center; font-weight: bold; padding: 10px;" width="200">PAJAK</th>
      <th style="text-align: center; font-weight: bold; padding: 10px;" width="200">JASA PELAYANAN<br>YANG DITERIMA</th>
    </thead>
    <tbody>
      <tr>
        <td align="center" valign="top" style="font-weight: bold; padding: 5px 10px; text-align: center;">C.</td>
        <td style="font-weight: bold; padding: 5px 10px;" colspan="7">
          JASA PELAYANAN SETELAH DIKURANGI JASA<br>
          TIM/KEPANITIAAN, LUAR JAM KERJA,
        </td>
        <td style="padding: 5px 10px; text-align: right; font-weight: bold;">100.00 %</td>
        <td style="font-weight: bold; padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->jp,2) }}
        </td>
        <td style="font-weight: bold; padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->jp_pajak,2) }}
        </td>
        <td style="font-weight: bold; padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->jp - $remun->jp_pajak,2) }}
        </td>
      </tr>      
      <tr>
        <td align="center"></td>
        <td style="font-weight: bold; padding: 5px 10px;" colspan="7">
          1. INSENTIF TIDAK LANGSUNG
        </td>
        <td style="font-weight: bold; padding: 5px 10px; text-align: right;">{{ $a_param->direksi + $a_param->staf + $a_param->pos_remun }} %</td>
        <td style="font-weight: bold; padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->nonpenghasil,2) }}
        </td>
        <td style="font-weight: bold; padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->nonpenghasil_pajak,2) }}
        </td>
        <td style="font-weight: bold; padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->nonpenghasil - $remun->nonpenghasil_pajak,2) }}
        </td>
      </tr>
      <tr>
        <td align="center"></td>
        <td align="center" width="50"></td>
        <td style="padding: 5px 10px;" colspan="5">
          Jasa Direktur
        </td>
        <td width="85" style="padding: 5px 10px; text-align: right;">{{ $a_param->direksi }} %</td>
        <td align="center"></td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->direksi,2) }}
        </td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->direksi_pajak,2) }}
        </td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->direksi - $remun->direksi_pajak,2) }}
        </td>
      </tr>
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td style="padding: 5px 10px;" colspan="5">
          Jasa Staf Direksi / Struktural
        </td>
        <td style="padding: 5px 10px; text-align: right;">{{ $a_param->staf }} %</td>
        <td align="center"></td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->staf_direksi,2) }}
        </td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->staf_pajak,2) }}
        </td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->staf_direksi - $remun->staf_pajak,2) }}
        </td>
      </tr>         
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td style="padding: 5px 10px;" colspan="5">
          Pos Remunerasi
        </td>
        <td style="padding: 5px 10px; text-align: right;">{{ number_format($a_param->pos_remun,2) }} %</td>
        <td align="center"></td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->pos_remun,2) }}
        </td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->pos_remun_pajak,2) }}
        </td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->pos_remun - $remun->pos_remun_pajak,2) }}
        </td>
      </tr>      
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td colspan="6" style="padding: 5px 10px;">TPP</td>        
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->tpp,2) }}
        </td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->tpp_pajak,2) }}
        </td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->tpp - $remun->tpp_pajak,2) }}
        </td>
      </tr>      
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td style="padding: 5px 10px;" colspan="6">Indek</td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->pos_remun - $remun->tpp,2) }}
        </td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->pos_remun_pajak - $remun->tpp_pajak,2) }}
        </td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->pos_remun - $remun->tpp - $remun->pos_remun_pajak + $remun->tpp_pajak,2) }}
        </td>
      </tr>      
      <tr>
        <td align="center"></td>
        <td style="font-weight: bold; padding: 5px 10px;" colspan="7">
          2. INSENTIF LANGSUNG
        </td>
        <td style="font-weight: bold; padding: 5px 10px; text-align: right;">{{ $a_param->medis_perawat + $a_param->admin }} %</td>
        <td style="font-weight: bold; padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->penghasil,2) }}
        </td>
        <td style="font-weight: bold; padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->penghasil_pajak,2) }}
        </td>
        <td style="font-weight: bold; padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->penghasil - $remun->penghasil_pajak,2) }}
        </td>
      </tr>
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td style="padding: 5px 10px;" colspan="4">
          Administrasi
        </td>
        <td style="padding: 5px 10px; text-align: right;">{{ $a_param->admin }} %</td>
        <td align="center"></td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->administrasi,2) }}
        </td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->administrasi_pajak,2) }}
        </td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->administrasi - $remun->administrasi_pajak,2) }}
        </td>
      </tr>
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td style="padding: 5px 10px;" colspan="4">
          Kembali Langsung
        </td>
        <td style="padding: 5px 10px; text-align: right;">{{ $a_param->medis_perawat }} %</td>
        <td align="center"></td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->medis,2) }}
        </td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->medis_pajak,2) }}
        </td>
        <td style="padding: 5px 10px; text-align: right;">
          <span style="float: left;">Rp.</span>
          {{ number_format($remun->medis - $remun->medis_pajak,2) }}
        </td>
      </tr>
    </tbody>              
  </table>

  <center>
  <table width="70%" style="margin-top: 30px; line-height: 20px;">
    <tr>
      <td width="70%"></td>
      <td>{{ ucwords(strtolower(str_replace('KABUPATEN ','',$a_param->kota))) }}, {{ $master->tanggal }}</td>
    </tr>
    <tr>
      <td>Mengetahui,</td>
      <td>Ketua Tim Remunerasi</td>
    </tr>
    <tr>
      <td>
        @if($param->direktur_plt == 1)
        Plt. 
        @endif
        Direktur RSUD GENTENG
      </td>
      <td>RSUD GENTENG</td>
    </tr>
    <tr>
      <td style="padding-top: 100px; font-weight: bold;">{{ $param->direktur }}</td>
      <td style="padding-top: 100px; font-weight: bold;">{{ $param->ketua }}</td>
    </tr>
    <tr>
      <td>Pembina Tk. I</td>
      <td>{{ $param->nip_ketua }}</td>
    </tr>
    <tr>
      <td>NIP. {{ $param->nip_direktur }}</td>
      <td></td>
    </tr>
  </table>
  </center>
@endsection