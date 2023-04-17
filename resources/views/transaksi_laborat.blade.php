@extends('layouts.content')
@section('title','Data Transaksi')

@section('content')
<div class="navbar" style="margin-bottom:5px;">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12">
        <form class="form-inline" method="GET" action="{{ route('pasien_laborat_transaksi') }}" style="margin-top: 5px; margin-bottom: 0;">
        @csrf
          <label>Tanggal</label>
          <input type="date" name="dari" value="{{ $dari }}" style="width: 130px;">
          <label>s/d</label>
          <input type="date" name="sampai" value="{{ $sampai }}" style="width: 130px;">
          <button type="submit" class="btn btn-primary" style="margin-top: 0;">TAMPILKAN</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
    <thead style="text-transform: uppercase;">
      <th>Waktu</th>
      <th>Nama Pasien</th>
      <th>Ruang</th>
      <th>Jenis</th>
      <th>Petugas Entri</th>
      <th>Medis</th>
      <th>Layanan</th>
      <th>Tarif</th>
      <th>Medis</th>
      <th>Perawat</th>
      <th>Admin</th>
    </thead>
    <tbody>
      @foreach($layanan as $lay)
      <tr>
        <td class="min">{{ strtoupper($lay->waktu) }}</td>
        <td>{{ strtoupper($lay->nama) }}</td>
        <td>{{ strtoupper($lay->ruang) }}</td>
        <td>{{ strtoupper($lay->jenis) }}</td>
        <td>{{ $lay->petugas }}</td>
        <td>{{ $lay->dpjp }}</td>
        <td>{{ strtoupper($lay->jasa) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->tarif,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jasa_medis,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jp_perawat,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->administrasi,0) }}</td>
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
        "order": [[ 0, "asc" ]],
      });
    });
  </script>
@endsection