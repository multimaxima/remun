@extends('layouts.cetak')
@section('title','Rincian BPJS')

@section('content')
  <center>
    <span style="font-weight: bold; font-size: 16px; margin-bottom: 20px;">
      <u>CLAIM BPJS TANGGAL {{ strtoupper($bpjs->tgl_awal) }} - {{ strtoupper($bpjs->tgl_akhir) }}</u>
    </span>
  </center>

  <table width="100%" class="table table-bordered" style="font-size: 13px; margin-top: 20px;">
    <thead>
      <tr>
        <th style="padding: 0 10px;" rowspan="2">NO.</th>
        <th style="padding: 0 10px;" rowspan="2">DOKTER DPJP</th>
        <th style="padding: 0 10px;" colspan="2">RAWAT JALAN</th>
        <th style="padding: 0 10px;" colspan="2">RAWAT INAP</th>
        <th style="padding: 0 10px; min-width: 100px;" colspan="2">TOTAL MEDIS</th>
      </tr>
      <tr>
        <th style="padding: 0 10px;" width="8%">TAGIHAN</th>
        <th style="padding: 0 10px;" width="8%">CLAIM</th>
        <th style="padding: 0 10px;" width="8%">TAGIHAN</th>
        <th style="padding: 0 10px;" width="8%">CLAIM</th>
        <th style="padding: 0 10px;" width="8%">TAGIHAN</th>
        <th style="padding: 0 10px;" width="8%">CLAIM</th>
      </tr>      
    </thead>
    <tbody>
      <?php $no = 1 ?>
      @foreach($detil as $detil)          
      <tr>
        <td style="text-align: right; padding-right: 10px;">{{ $no++ }}.</td>
        <td>{{ $detil->nama }}</td>
        <td style="text-align: right;">{{ number_format($detil->nominal_jalan,0) }}</td>              
        <td style="text-align: right;">{{ number_format($detil->claim_jalan,0) }}</td>
        <td style="text-align: right;">{{ number_format($detil->nominal_inap,0) }}</td>
        <td style="text-align: right;">{{ number_format($detil->claim_inap,0) }}</td>
        <td style="text-align: right;">{{ number_format($detil->nominal_jalan + $detil->nominal_inap,0) }}</td>
        <td style="text-align: right;">{{ number_format($detil->claim_jalan + $detil->claim_inap,0) }}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <th colspan="2" style="text-align: center;">JUMLAH</th>
      <th style="text-align: right;">{{ number_format($tag->t_jalan,0) }}</th>
      <th style="text-align: right;">{{ number_format($tag->c_jalan,0) }}</th>            
      <th style="text-align: right;">{{ number_format($tag->t_inap,0) }}</th>
      <th style="text-align: right;">{{ number_format($tag->c_inap,0) }}</th>
      <th style="text-align: right;">{{ number_format($tag->t_jalan + $tag->t_inap,0) }}</th>
      <th style="text-align: right;">{{ number_format($tag->c_jalan + $tag->c_inap,0) }}</th>
    </tfoot>
  </table>  
@endsection