@extends('layouts.content')
@section('title','Data Remunerasi')

@section('content')
<div class="content">
  <table id="tabel" width="100%" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
    <thead>
      <tr>
        <th rowspan="2"></th>
        <th rowspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">Tanggal Perhitungan</th>
        <th colspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">Periode</th>
        <th rowspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">Jenis Pasien</th>
        <th rowspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">Nominal JP</th>
        <th rowspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">Status</th>
        <th rowspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">Waktu Perhitungan</th>
        <th rowspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">Petugas</th>
      </tr>
      <tr>
        <th style="vertical-align: middle; text-align: center; padding: 0 10px;">Dari</th>
        <th style="vertical-align: middle; text-align: center; padding: 0 10px;">Sampai</th>
      </tr>
    </thead>
    <tbody>
      @foreach($remun as $remun)       
      <tr>
        <td class="min">
          <form hidden method="GET" action="{{ route('remunerasi_data_detil') }}" id="rincian{{ $remun->id }}">
          @csrf
            <input type="hidden" name="id" value="{{ Crypt::encrypt($remun->id) }}">                  
          </form>

          <button type="submit" form="rincian{{ $remun->id }}" class="btn btn-info btn-mini" title="Rincian">
            <i class="icon-list"></i>
          </button>
        </td>
        <td>{{ strtoupper($remun->tanggal) }}</td>
        <td style="text-align: center;">{{ strtoupper($remun->awal) }}</td>
        <td style="text-align: center;">{{ strtoupper($remun->akhir) }}</td>
        <td>{{ $remun->jkn }}</td>
        <td style="text-align: right;">{{ number_format($remun->a_jp,2) }}</td>              
        <td>{{ strtoupper($remun->status) }}</td>
        <td style="text-align: center;">{{ strtoupper($remun->waktu) }}</td>
        <td style="text-align: center;">{{ strtoupper($remun->petugas) }}</td>
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
        "sort": false,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
      });
    });
  </script>
@endsection