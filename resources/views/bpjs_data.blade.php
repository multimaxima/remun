@extends('layouts.content')
@section('title','Beranda')

@section('style')
  <style type="text/css">
    td a {
      color: black;
    }
  </style>
@endsection

@section('content')
<div class="content">
  @include('layouts.pesan')
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered">
    <thead>
      <tr>
        <th style="padding: 0 10px;" rowspan="2"></th>
        <th style="padding: 0 10px;" rowspan="2">TANGGAL PERHITUNGAN</th>
        <th style="padding: 0 10px;" rowspan="2">JENIS</th>
        <th style="padding: 0 10px;" colspan="2">PERIODE</th>
        <th style="padding: 0 10px;" colspan="2">RAWAT JALAN</th>
        <th style="padding: 0 10px;" colspan="2">RAWAT INAP</th>
        <th style="padding: 0 10px;" colspan="2">TOTAL</th>
      </tr>
      <tr>
        <th style="padding: 0 10px;">DARI</th>
        <th style="padding: 0 10px;">SAMPAI</th>
        <th style="padding: 0 10px;">TAGIHAN</th>
        <th style="padding: 0 10px;">CLAIM</th>
        <th style="padding: 0 10px;">TAGIHAN</th>
        <th style="padding: 0 10px;">CLAIM</th>
        <th style="padding: 0 10px;">TAGIHAN</th>
        <th style="padding: 0 10px;">CLAIM</th>
      </tr>
    </thead>
    <tbody>
      @foreach($bpjs as $bpjs)
      <tr>
        <td class="min">
          <a href="{{ route('bpjs_data_detil',Crypt::encrypt($bpjs->id)) }}" title="Rincian Data" class="btn btn-info btn-mini">
            <i class="icon-list"></i>
          </a>
        </td>
        <td>{{ strtoupper($bpjs->tanggal) }}</td>
        <td>{{ strtoupper($bpjs->jenis) }}</td>
        <td style="text-align: center;">{{ strtoupper($bpjs->dari) }}</td>
        <td style="text-align: center;">{{ strtoupper($bpjs->sampai) }}</td>
        <td style="text-align: right;">{{ number_format($bpjs->nominal_jalan,2) }}</td>
        <td style="text-align: right;">{{ number_format($bpjs->claim_jalan,2) }}</td>
        <td style="text-align: right;">{{ number_format($bpjs->nominal_inap,2) }}</td>
        <td style="text-align: right;">{{ number_format($bpjs->claim_inap,2) }}</td>
        <td style="text-align: right;">{{ number_format($bpjs->nominal_jalan + $bpjs->nominal_inap,2) }}</td>
        <td style="text-align: right;">{{ number_format($bpjs->claim_jalan + $bpjs->claim_inap,2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('#tabel').DataTable( {     
        "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": 0
        }],
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        sort : false,
      });
    });    
  </script>
@endsection