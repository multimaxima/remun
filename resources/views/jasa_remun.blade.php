@extends('layouts.content')
@section('title','Jasa Remunerasi')

@section('content')
<div class="content">
  <table id="tabel" width="100%" class="table table-hover table-striped table-bordered" style="font-size: 13px;">
    <thead>
      <tr>
        <th rowspan="2"></th>
        <th rowspan="2" style="text-align: center;">TANGGAL PERHITUNGAN</th>
        <th colspan="2" style="text-align: center;">PERIODE</th>
        <th rowspan="2" style="text-align: center;">JENIS</th>
        <th rowspan="2" style="text-align: center;">TOTAL JP</th>      
      </tr>
      <tr>
        <th style="text-align: center;">DARI</th>
        <th style="text-align: center;">SAMPAI</th>
      </tr>
    </thead>
    <tbody>
      @foreach($remun as $remun)
      <tr>
        <td class="min">
          <a href="{{ route('jasa_remun_rincian',Crypt::encrypt($remun->id)) }}" class="btn btn-info btn-mini">
            <i class="icon-list"></i>
          </a>
        </td>
        <td>{{ strtoupper($remun->tanggal) }}</td>
        <td style="text-align: center;">{{ strtoupper($remun->awal) }}</td>
        <td style="text-align: center;">{{ strtoupper($remun->akhir) }}</td>
        <td style="text-align: center;">PASIEN {{ strtoupper($remun->jenis) }}</td>
        <td style="text-align: right;">{{ number_format($remun->r_jp,2) }}</td>
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
        "sort": false,
      });
    });
  </script>
@endsection