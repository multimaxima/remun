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
          <form class="form-horizontal" method="GET" id="form_transaksi" action="{{ route('pasien_operasi_transaksi') }}">
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
              <div class="title mb-1">Operator</div>
              <select name="oper" class="form-control">
                <option value="" style="font-style: italic;">SEMUA</option>
                @foreach($operator as $opr)
                  <option value="{{ $opr->id }}" {{ $oper == $opr->id? 'selected' : null }}>{{ $opr->nama }}</option>
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
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 3vw;">
    <thead style="text-transform: uppercase;">
      <tr>
        <th rowspan="2" valign="middle" style="text-align: center; padding: 0 15px;">Waktu</th>
        <th rowspan="2" valign="middle" style="text-align: center; padding: 0 15px;">Nama Pasien</th>
        <th rowspan="2" valign="middle" style="text-align: center; padding: 0 15px;">Ruang</th>
        <th rowspan="2" valign="middle" style="text-align: center; padding: 0 15px;">Dokter Operator</th>
        <th rowspan="2" valign="middle" style="text-align: center; padding: 0 15px;">Dokter Anastesi</th>
        <th rowspan="2" valign="middle" style="text-align: center; padding: 0 15px;">Dokter Pendamping</th>
        <th rowspan="2" valign="middle" style="text-align: center; padding: 0 15px;">Layanan</th>
        <th rowspan="2" valign="middle" style="text-align: center; padding: 0 15px;">Tarif</th>
        <th colspan="9" valign="middle" style="text-align: center; padding: 0 15px;">Jasa</th>
      </tr>
      <tr>
        <th valign="middle" style="text-align: center; padding: 0 15px;">Operator</th>
        <th valign="middle" style="text-align: center; padding: 0 15px;">Anastesi</th>
        <th valign="middle" style="text-align: center; padding: 0 15px;">Pendamping</th>
        <th valign="middle" style="text-align: center; padding: 0 15px;">Pen.Anastesi</th>
        <th valign="middle" style="text-align: center; padding: 0 15px;">Per.Asisten 1</th>
        <th valign="middle" style="text-align: center; padding: 0 15px;">Per.Asisten 2</th>
        <th valign="middle" style="text-align: center; padding: 0 15px;">Instrumen</th>
        <th valign="middle" style="text-align: center; padding: 0 15px;">Sirkuler</th>
        <th valign="middle" style="text-align: center; padding: 0 15px;">Administrasi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($layanan as $lay)
      <tr>
        <td class="min">{{ strtoupper($lay->waktu) }}</td>
        <td class="min">{{ strtoupper($lay->nama) }}</td>
        <td class="min">{{ strtoupper($lay->ruang) }}</td>
        <td class="min">{{ $lay->operator }}</td>
        <td class="min">{{ $lay->anastesi }}</td>
        <td class="min">{{ $lay->pendamping }}</td>
        <td class="min">{{ strtoupper($lay->jasa) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->tarif,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jasa_operator,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jasa_anastesi,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->jasa_pendamping,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->pen_anastesi,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->per_asisten_1,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->per_asisten_2,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->instrumen,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($lay->sirkuler,0) }}</td>
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