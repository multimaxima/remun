@extends('layouts.content')
@section('title','Daftar Transaksi')

@section('content')
<div class="navbar" style="margin-bottom:5px;">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12">
        <form class="form-inline" method="GET" action="{{ route('pasien_gizi_transaksi') }}" style="margin-top: 5px; margin-bottom: 0;">
        @csrf

          <label>Data Tanggal</label>
          <input type="date" name="awal" required autofocus value="{{ $awal }}" style="width: 130px;">
          <label>s/d</label>
          <input type="date" name="akhir" required value="{{ $akhir }}" style="width: 130px;">

          <select name="jns">
            <option value="" style="font-style: italic;">JENIS PASIEN</option>
            @foreach($jenis as $jenis)
              <option value="{{ $jenis->id }}" {{ $jns == $jenis->id? 'selected' : null }}>{{ strtoupper($jenis->jenis) }}</option>
            @endforeach
          </select>
          <button type="submit" class="btn btn-primary" style="margin-top: 0;">TAMPILKAN</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 13px;">
    <thead>
      <th style="text-align: center; vertical-align: middle; text-transform: uppercase;">Waktu</th>
      <th style="text-align: center; vertical-align: middle; text-transform: uppercase;">Nama Pasien</th>
      <th style="text-align: center; vertical-align: middle; text-transform: uppercase;">Jenis</th>            
      <th style="text-align: center; vertical-align: middle; text-transform: uppercase;">Petugas</th>
      <th style="text-align: center; vertical-align: middle; text-transform: uppercase;">Ruang</th>
      <th width="80" style="text-align: center; vertical-align: middle; text-transform: uppercase;">Tarif</th>
      <th width="60" style="text-align: center; vertical-align: middle; text-transform: uppercase;">Medis</th>
      <th width="60" style="text-align: center; vertical-align: middle; text-transform: uppercase;">Perawat</th>
      <th width="60" style="text-align: center; vertical-align: middle; text-transform: uppercase;">Administrasi</th>
    </thead>
    <tbody>
      @foreach($transaksi as $transaksi)
      <tr>              
        <td class="min">{{ strtoupper($transaksi->waktu) }}</td>
        <td>{{ strtoupper($transaksi->nama) }}</td>
        <td>{{ strtoupper($transaksi->jenis_pasien) }}</td>
        <td>{{ strtoupper($transaksi->petugas) }}</td>
        <td>{{ strtoupper($transaksi->ruang) }}</td>
        <td style="text-align: right;">{{ number_format($transaksi->tarif,0) }}</td>
        <td style="text-align: right;">{{ number_format($transaksi->medis,0) }}</td>
        <td style="text-align: right;">{{ number_format($transaksi->jp_perawat,0) }}</td>
        <td style="text-align: right;">{{ number_format($transaksi->administrasi,0) }}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <th colspan="5" style="text-align: center;">J U M L A H</th>
      <th style="text-align: right;">{{ number_format($total->tarif,0) }}</th>
      <th style="text-align: right;">{{ number_format($total->medis,0) }}</th>
      <th style="text-align: right;">{{ number_format($total->jp_perawat,0) }}</th>
      <th style="text-align: right;">{{ number_format($total->administrasi,0) }}</th>
    </tfoot>
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