@extends('layouts.content')
@section('title','Tandon Jasa Remunerasi')

@section('content')
<div class="content" style="margin-bottom: 5px; max-height: 81vh;">
  <table id="tabel" width="100%" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
    <thead>
      <tr>
        <th style="text-align: center;">NAMA KARYAWAN</th>
        <th style="text-align: center;">BAGIAN</th>
        <th style="text-align: center;">PROFESI</th>
        <th style="text-align: center;">MASUK</th>
        <th style="text-align: center;">KELUAR</th>
        <th style="text-align: center;">SISA</th>
        <th style="text-align: center;">KETERANGAN</th>
      </tr>      
    </thead>
    <tbody>
      @foreach($tandon as $tand)
      <tr>
        <td class="min">{{ $tand->nama }}</td>
        <td class="min">{{ $tand->bagian }}</td>
        <td class="min">{{ $tand->tenaga }}</td>
        <td class="min" style="text-align: right; background-color: #e5e4fd;">{{ number_format($tand->masuk,2) }}</td>
        <td class="min" style="text-align: right; background-color: #e5e4fd;">{{ number_format($tand->keluar,2) }}</td>
        <td class="min" style="text-align: right; background-color: #e5e4fd;">{{ number_format($tand->sisa,2) }}</td>
        <td class="min">{{ $tand->keterangan }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection

@section('script')
  <script type="text/javascript">    
    $(document).ready(function() {
      if(window.screen.height < 900){
        $('#tabel').DataTable( {
          scrollY:        "74vh",
          scrollCollapse: true,
          paging:         false,
          searching:      false,
          info:           false,
          sort:           false,
        });
      } else {
        $('#tabel').DataTable( {
          scrollY:        "78vh",
          scrollCollapse: true,
          paging:         false,
          searching:      false,
          info:           false,
          sort:           false,
        });
      }      
    });
  </script>
@endsection