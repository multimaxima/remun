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
          <form class="form-inline" method="GET" id="form_transaksi" action="{{ route('pasien_laborat_transaksi') }}">
          @csrf
            <div class="mb-1">
              <div class="title mb-1">Transaksi Tanggal</div>
              <input type="date" class="form-control" name="dari" required autofocus value="{{ $dari }}">
            </div>

            <div class="mb-1">
              <div class="title mb-1">Sampai</div>
              <input type="date" class="form-control" name="sampai" required value="{{ $sampai }}">
            </div>            
          </form>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-secondary btn-sm" form="form_transaksi">TAMPILKAN</button>
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
        <td class="min">{{ strtoupper($lay->nama) }}</td>
        <td class="min">{{ strtoupper($lay->ruang) }}</td>
        <td class="min">{{ strtoupper($lay->jenis) }}</td>
        <td class="min">{{ $lay->petugas }}</td>
        <td class="min">{{ $lay->dpjp }}</td>
        <td class="min">{{ strtoupper($lay->jasa) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->tarif,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jasa_medis,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jp_perawat,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->administrasi,0) }}</td>
      </tr>
      @endforeach
    </tbody>          
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