@extends('layouts.content')
@section('title','Detil Claim Asuransi')

@section('style')
  <style type="text/css">
    td a {
      color: black;
    }
  </style>
@endsection

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12">
        <div class="btn-group">
          <button type="button" onclick="goBack();" class="btn btn-primary" title="Kembali">
            KEMBALI
          </button>
          <a class="btn btn-primary" href="{{ route('bpjs_cetak',Crypt::encrypt($bpjs->id)) }}" target="_blank" title="Cetak">
            CETAK
          </a>
          <a class="btn btn-primary" href="{{ route('bpjs_export',Crypt::encrypt($bpjs->id)) }}" title="Export Excel">
            EXPORT
          </a>          
        </div>
      </div>
    </div>
  </div>
</div>

<div class="content">
  @include('layouts.pesan')
  <center>
    <label style="font-weight: bold; text-align: center; font-size: 16px;">
    CLAIM {{ strtoupper($bpjs->jenis) }} TANGGAL {{ strtoupper($bpjs->awal) }} - {{ strtoupper($bpjs->akhir) }}
  </label>
  </center>
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered">
    <thead>
      <tr>
        <th style="padding: 0 10px;" rowspan="2"></th>
        <th style="padding: 0 10px;" rowspan="2">NO.</th>
        <th style="padding: 0 10px;" rowspan="2">DOKTER DPJP</th>
        <th style="padding: 0 10px;" colspan="2">RAWAT JALAN</th>
        <th style="padding: 0 10px;" colspan="2">RAWAT INAP</th>
        <th style="padding: 0 10px;" colspan="2">TOTAL</th>
      </tr>
      <tr>
        <th style="padding: 0 10px;">TAGIHAN</th>
        <th style="padding: 0 10px;">CLAIM</th>
        <th style="padding: 0 10px;">TAGIHAN</th>
        <th style="padding: 0 10px;">CLAIM</th>
        <th style="padding: 0 10px;">TAGIHAN</th>
        <th style="padding: 0 10px;">CLAIM</th>
      </tr>            
    </thead>
    <tbody>
      <?php $no = 1 ?>
      @foreach($detil as $detil)         
      <tr>
        <td class="min">
          <a href="{{ route('bpjs_rincian',Crypt::encrypt($detil->id)) }}" title="Rincian Data" class="btn btn-info btn-mini">
            <i class="icon-list"></i>
          </a>
        </td>
        <td style="text-align: right;" class="min">{{ $no++ }}.</td>
        <td>{{ $detil->nama }}</td>
        <td style="text-align: right;">{{ number_format($detil->nominal_jalan,0) }}</td>
        <td style="text-align: right;">{{ number_format($detil->claim_jalan,0) }}</td>
        <td style="text-align: right;">{{ number_format($detil->nominal_inap,0) }}</td>
        <td style="text-align: right;">{{ number_format($detil->claim_inap,0) }}</td>
        <td style="background-color: #e4ebfe; text-align: right;">{{ number_format($detil->nominal_jalan + $detil->nominal_inap,0) }}</td>
        <td style="background-color: #e4ebfe; text-align: right;">{{ number_format($detil->claim_jalan + $detil->claim_inap,0) }}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <th colspan="3" style="text-align: center;">JUMLAH</th>
      <th style="text-align: right; padding-right: 5px;">{{ number_format($tag->t_jalan,0) }}</th>
      <th style="text-align: right; padding-right: 5px;">{{ number_format($tag->c_jalan,0) }}</th>            
      <th style="text-align: right; padding-right: 5px;">{{ number_format($tag->t_inap,0) }}</th>
      <th style="text-align: right; padding-right: 5px;">{{ number_format($tag->c_inap,0) }}</th>
      <th style="text-align: right; padding-right: 5px;">{{ number_format($tag->t_jalan + $tag->t_inap,0) }}</th>            
      <th style="text-align: right; padding-right: 5px;">{{ number_format($tag->c_jalan + $tag->c_inap,0) }}</th>                   
    </tfoot>
  </table>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.22*box.clientHeight);

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