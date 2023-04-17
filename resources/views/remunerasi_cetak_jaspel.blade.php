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
      PENERIMAAN JASA PELAYANAN PASIEN
      @if($remun->id_bpjs)
        JKN
      @else
        UMUM
      @endif
      <br>
      RUMAH SAKIT UMUM DAERAH GENTENG<br>
      TANGGAL {{ strtoupper($remun->awal) }} S/D {{ strtoupper($remun->akhir) }}
    </span>
  </center>

  <table width="100%" class="table table-bordered" style="margin: 10px 0; font-size: 12px;">
    <thead>
      <tr>
        <th style="padding: 10px; text-align: center;">NO.</th>
        <th style="padding: 10px; text-align: center;">NAMA</th>
        <th style="padding: 10px; text-align: center;">SKORE</th>
        <th style="padding: 10px; text-align: center;">GOL</th>
        <th style="padding: 10px; text-align: center;">STATUS</th>
        <th style="padding: 10px; text-align: center;">RUANG</th>
        <th style="padding: 10px; text-align: center;" width="50">TPP</th>
        <th style="padding: 10px; text-align: center;" width="50">INDEK</th>
        <th style="padding: 10px; text-align: center;" width="50">PENGEMB. LANGSUNG DIREKSI</th>
        <th style="padding: 10px; text-align: center;" width="50">PENGEMB. LANGSUNG STAF DIREKSI</th>
        <th style="padding: 10px; text-align: center;" width="50">KELOMPOK ADMINIST.</th>
        <th style="padding: 10px; text-align: center;" width="50">JAS. LANG. MEDIS/PER. SETARA</th>        
        <th style="padding: 10px; text-align: center;" width="50">JASA TOTAL</th>
        <th style="padding: 10px; text-align: center;" width="50">PAJAK (%)</th>
        <th style="padding: 10px; text-align: center;" width="50">NOMINAL PAJAK</th>
        <th style="padding: 10px; text-align: center;" width="50">JASA DITERIMA</th>
        <th style="padding: 10px; text-align: center;" width="50">NPWP</th>
        <th style="padding: 10px; text-align: center;" width="50">BANK</th>
        <th style="padding: 10px; text-align: center;" width="50">NO. REK</th>
      </tr>  
    </thead>
    <tbody>
      <?php $no = 0;?>
      @foreach($detil as $det)
      <?php $no++ ;?>
      <tr>
        <td class="min" style="text-align: center; padding: 0 5px;">{{ $no }}.</td>
        <td class="min" style="padding: 0 5px;">{{ $det->nama }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($det->indek,2) }}</td>
        <td class="min" style="padding: 0 5px;">{{ $det->golongan }}</td>
        <td class="min" style="padding: 0 5px;">{{ $det->status }}</td>
        <td class="min" style="padding: 0 5px;">{{ $det->ruang }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($det->tpp,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($det->r_indek,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($det->r_direksi,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($det->r_staf_direksi,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($det->r_administrasi,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($det->r_medis,2) }}</td>        
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($det->jasa,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($det->pajak,2) }} %</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($det->nom_pajak,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($det->total,2) }}</td>
        <td class="min" style="padding: 0 5px;">{{ $det->npwp }}</td>
        <td class="min" style="padding: 0 5px;">{{ $det->bank }}</td>
        <td class="min" style="padding: 0 5px;">{{ $det->rekening }}</td>
      </tr>
      @endforeach      
    </tbody>         
    <tfoot>
      <th colspan="6" style="text-align: center;">TOTAL</th>
      <th style="text-align: right;">{{ number_format($total->tpp,2) }}</th>
      <th style="text-align: right;">{{ number_format($total->r_indek,2) }}</th>
      <th style="text-align: right;">{{ number_format($total->r_direksi,2) }}</th>
      <th style="text-align: right;">{{ number_format($total->r_staf_direksi,2) }}</th>
      <th style="text-align: right;">{{ number_format($total->r_administrasi,2) }}</th>
      <th style="text-align: right;">{{ number_format($total->r_medis,2) }}</th>
      <th style="text-align: right;">{{ number_format($total->jasa,2) }}</th>
      <th></th>
      <th style="text-align: right;">{{ number_format($total->nom_pajak,2) }}</th>
      <th style="text-align: right;">{{ number_format($total->total,2) }}</th>
      <th></th>
      <th></th>
      <th></th>
    </tfoot> 
  </table>     

  <table width="100%" style="margin: 10px 0; line-height: 20px;">
    <tr>
      <td>Mengetahui,</td>
      <td></td>
      <td></td>
      <td>{{ ucwords(strtolower(str_replace('KABUPATEN ','',$a_param->kota))) }}, {{ $remun->tanggal }}</td>
    </tr>
    <tr>
      <td>
        @if($a_param->direktur_plt == 1)
        Plt.
        @endif
        DIREKTUR RSUD GENTENG
      </td>
      <td>BENDAHARA PENGELUARAN PEMBANTU</td>
      <td>PEJABAT PELAKSANA TEKNIS KEGIATAN</td>
      <td>KETUA TIM REMUNERASI</td>
    </tr>
    <tr>
      <td style="padding-top: 100px; font-weight: bold;">{{ $param->direktur }}</td>
      <td style="padding-top: 100px; font-weight: bold;">{{ $param->bendahara }}</td>
      <td style="padding-top: 100px; font-weight: bold;">{{ $param->pelaksana }}</td>
      <td style="padding-top: 100px; font-weight: bold;">{{ $param->ketua }}</td>
    </tr>
    <tr>
      <td>Pembina Tk. I</td>
      <td>NIP. {{ $param->nip_bendahara }}</td>
      <td>NIP. {{ $param->nip_pelaksana }}</td>
      <td>NIP. {{ $param->nip_ketua }}</td>
    </tr>
    <tr>
      <td>NIP. {{ $param->nip_direktur }}</td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </table>
@endsection