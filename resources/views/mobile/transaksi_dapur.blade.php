@extends('mobile.layouts.content')

@section('bawah')
  <li>
    <a href="#" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne"><i class="fa fa-filter"></i>Filter</a>
  </li>
  <li><a href="{{ route('informasi_software') }}"><i class="fa fa-info"></i>Informasi</a></li>
@endsection

@section('content')
<div class="row isi">
  <div class="container" style="margin-top: 8vh;">    
  </div>
</div>

<div class="collapse" id="collapseOne" aria-labelledby="headingOne" style="margin-top: 1vh;">
  <div class="row isi">
    <div class="container">    
      <div class="card">
        <div class="card-body user-data-card" style="font-size: 3vw;"> 
          <form class="form-horizontal" method="GET" id="form_transaksi_gizi" action="{{ route('pasien_gizi_transaksi') }}" style="margin-top: 5px; margin-bottom: 0;">
          @csrf

            <div class="mb-1">
              <div class="title mb-1">Data Tanggal</div>
              <input type="date" class="form-control" name="awal" required autofocus value="{{ $awal }}">
            </div>

            <div class="mb-1">
              <div class="title mb-1">Sampai</div>
              <input type="date" class="form-control" name="akhir" required value="{{ $akhir }}">
            </div>

            <div class="mb-1">
              <div class="title mb-1">Jenis Pasien</div>
              <select name="jns" class="form-control">
                <option value="" style="font-style: italic;">JENIS PASIEN</option>
                @foreach($jenis as $jenis)
                  <option value="{{ $jenis->id }}" {{ $jns == $jenis->id? 'selected' : null }}>{{ strtoupper($jenis->jenis) }}</option>
                @endforeach
              </select>
            </div>          
          </form>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary btn-sm" form="form_transaksi_gizi" style="margin-top: 0;">TAMPILKAN</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row isi" style="padding-bottom: 10vh;">
  <div class="container">
    <div class="card" style="margin-top: 1vh;">
      <div class="card-body" style="font-size: 3vw; overflow-x: auto;">
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 3vw;">
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
        <td class="min">{{ strtoupper($transaksi->nama) }}</td>
        <td class="min">{{ strtoupper($transaksi->jenis_pasien) }}</td>
        <td class="min">{{ strtoupper($transaksi->petugas) }}</td>
        <td class="min">{{ strtoupper($transaksi->ruang) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($transaksi->tarif,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($transaksi->medis,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($transaksi->jp_perawat,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($transaksi->administrasi,0) }}</td>
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
</div>
</div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $(document).ready(function() {
        var box = document.querySelector('.content');
        var tinggi = box.clientHeight-(0.34*box.clientHeight);

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
    });
  </script>
@endsection