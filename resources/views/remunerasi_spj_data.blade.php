@extends('layouts.content')
@section('title','Cetak SPJ & Kwitansi Remunerasi')

@section('judul')  
  <h4 class="page-title"> <i class="dripicons-document-edit"></i> @yield('title')</h4>
@endsection

@section('content')
<div class="content">
  <table id="tabel" width="100%" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
    <thead style="text-transform: uppercase;">
      <tr>
        <th rowspan="2"></th>
        <th rowspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">Tanggal Remunerasi</th>
        <th colspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">Tanggal</th>
        <th rowspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">Jenis</th>
        <th rowspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">Total JP</th>
        <th rowspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">Status</th>            
      </tr>
      <tr>
        <th style="text-align: center; padding: 0 10px;">Dari</th>
        <th style="text-align: center; padding: 0 10px;">Sampai</th>
      </tr>
    </thead>
    <tbody>
      @foreach($remun as $remun)
      <tr>
        <td class="min">
          <form hidden method="GET" action="{{ route('remunerasi_olah_spj') }}" id="rincian{{ $remun->id }}">
          @csrf
            <input type="text" name="id_remun" value="{{ Crypt::encrypt($remun->id) }}">
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
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        "order": [[ 1, "asc" ]],
        sort: false,
      });
    });
  </script>
@endsection