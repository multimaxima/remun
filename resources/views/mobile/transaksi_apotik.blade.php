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
          <form class="form-horizontal" method="GET" id="form_transaksi" action="{{ route('pasien_apotik_transaksi') }}">
          @csrf

            <div class="mb-1">
              <div class="title mb-1">Transaksi Tanggal</div>
              <input type="date" class="form-control" name="awal" required autofocus value="{{ $awal }}">
            </div>

            <div class="mb-1">
              <div class="title mb-1">Sampai</div>
              <input type="date" class="form-control" name="akhir" required value="{{ $akhir }}">
            </div>

            <div class="mb-1">
              <div class="title mb-1">Jenis Pasien</div>
              <select name="jns" class="form-control">
                <option value="" style="font-style: italic;">SEMUA</option>
                @foreach($jenis as $jenis)
                  <option value="{{ $jenis->id }}" {{ $jns == $jenis->id? 'selected' : null }}>{{ strtoupper($jenis->jenis) }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-1">
              <div class="title mb-1">Jenis Perawatan</div>
              <select name="rwt" class="form-control">
                <option value="" style="font-style: italic;">SEMUA</option>
                @foreach($rawat as $rawat)
                <option value="{{ $rawat->id }}" {{ $rwt == $rawat->id? 'selected' : null }}>{{ strtoupper($rawat->jenis_rawat) }}</option>
                @endforeach
              </select>
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
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 3vw;>
    <thead>
      <th style="text-align: center; vertical-align: middle;">Waktu</th>
      <th style="text-align: center; vertical-align: middle;">Nama Pasien</th>
      <th style="text-align: center; vertical-align: middle;">Jenis</th>            
      <th style="text-align: center; vertical-align: middle;">Petugas</th>
      <th style="text-align: center; vertical-align: middle;">Ruang</th>
      <th style="text-align: center; vertical-align: middle;">Jasa</th>
      <th width="80" style="text-align: center; vertical-align: middle;">Tarif</th>
      <th width="60" style="text-align: center; vertical-align: middle;">Apoteker</th>
      <th width="60" style="text-align: center; vertical-align: middle;">Ass. Apoteker</th>
      <th width="60" style="text-align: center; vertical-align: middle;">Adm. Farmasi</th>
    </thead>
    <tbody>
      @foreach($transaksi as $trans)
      <tr>              
        <td class="min">{{ strtoupper($trans->waktu) }}</td>
        <td class="min">{{ strtoupper($trans->nama) }}</td>
        <td class="min">{{ strtoupper($trans->jenis_pasien) }}</td>
        <td class="min">{{ strtoupper($trans->petugas) }}</td>
        <td class="min">{{ strtoupper($trans->ruang) }}</td>
        <td class="min">{{ strtoupper($trans->jasa) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($trans->tarif,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($trans->apoteker,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($trans->ass_apoteker,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($trans->admin_farmasi,0) }}</td>
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