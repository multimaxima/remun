@extends('layouts.content')
@section('title','Daftar Transaksi')

@section('content')
<div class="navbar" style="margin-bottom:5px;">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12">
        <form class="form-inline" method="GET" action="{{ route('pasien_jenasah_transaksi') }}" style="margin-top: 5px; margin-bottom: 0;">
        @csrf

          <label>Transaksi Tanggal</label>
          <input type="date" name="awal" required autofocus value="{{ $awal }}" style="width: 130px;">
          <label>s/d</label>
          <input type="date" name="akhir" required value="{{ $akhir }}" style="width: 130px;">
          <button type="submit" class="btn btn-primary" style="margin-top: 0;">TAMPILKAN</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
    <thead>
      <th style="text-align: center; vertical-align: middle;">Waktu</th>
      <th style="text-align: center; vertical-align: middle;">Nama Pasien</th>
      <th style="text-align: center; vertical-align: middle;">Jenis</th>            
      <th style="text-align: center; vertical-align: middle;">Petugas</th>
      <th style="text-align: center; vertical-align: middle;">Ruang</th>
      <th style="text-align: center; vertical-align: middle;">Jasa</th>
      <th width="80" style="text-align: center; vertical-align: middle;">Tarif</th>
      <th width="60" style="text-align: center; vertical-align: middle;">Pemulasaran</th>
    </thead>
    <tbody>
      @foreach($transaksi as $transaksi)
      <tr>              
        <td class="min">{{ strtoupper($transaksi->waktu) }}</td>
        <td>{{ strtoupper($transaksi->nama) }}</td>
        <td>{{ strtoupper($transaksi->jenis_pasien) }}</td>
        <td>{{ strtoupper($transaksi->petugas) }}</td>
        <td>{{ strtoupper($transaksi->ruang) }}</td>
        <td>{{ strtoupper($transaksi->jasa) }}</td>
        <td style="text-align: right;">{{ number_format($transaksi->tarif,0) }}</td>
        <td style="text-align: right;">{{ number_format($transaksi->pemulasaran,0) }}</td>
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
        "order": [[ 0, "desc" ]],        
      });
    });
  </script>
@endsection