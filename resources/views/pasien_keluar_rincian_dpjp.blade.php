@extends('layouts.content')
@section('title','Rincian Pasien Keluar Per DPJP')

@section('judul')
  <div class="btn-group float-right" role="group" aria-label="Basic example">
    <a href="#" class="btn btn-primary">
      <i class="fa fa-file-excel-o"></i> Export
    </a>
  </div>

  <h4 class="page-title"> <i class="dripicons-user-id"></i> @yield('title')</h4>
@endsection

@section('content')
<div class="wrapper">
  <div class="col-12" style="padding: 0 50px;">
    <div class="card m-b-30 konten">
      <div class="card-body">
        <form class="form-inline" method="GET" action="{{ route('pasien_keluar_rincian_dpjp') }}">
        @csrf                    

          <div class="col-12">
            <center>
              <span>Nama DPJP</span>
              <select class="form-control" name="id_dpjp" required autofocus style="margin: 0 10px;">
                <option value=""></option>
                @foreach($dpjp as $dpjp)
                  <option value="{{ Crypt::encrypt($dpjp->id) }}" {{ $id_dpjp == $dpjp->id? 'selected' : null }}>{{ $dpjp->nama }}</option>
                @endforeach
              </select>

              <span>Tanggal Pasien Keluar</span>
              <input type="date" class="form-control" name="awal" style="margin: 0 10px;" required value="{{ $awal }}">

              <span>s/d</span>
              <input type="date" class="form-control" name="akhir" style="margin: 0 10px;" required value="{{ $akhir }}">          

              <button type="submit" class="btn btn-warning btn-sm">TAMPILKAN</button>
            </center>
          </div>
        </form>
      </div>      
    </div>
  </div>
</div>

@if($pasien)
<div class="wrapper" style="margin-top: -20px;">
  <div class="col-12" style="padding: 0 50px;">
    <div class="card m-b-30 konten">
      <div class="card-body">
        <table width="600%" id="tabel" class="table table-hover table-striped" style="font-size: 13px;">
          <thead>
            <tr>
              <th rowspan="3" style="text-align: center; vertical-align: middle;">WAKTU</th>
              <th rowspan="3" style="text-align: center; vertical-align: middle;">NAMA PASIEN</th>
              <th rowspan="3" style="text-align: center; vertical-align: middle;">JENIS PASIEN</th>
              <th rowspan="3" style="text-align: center; vertical-align: middle;">RUANG PERAWATAN</th>
              <th rowspan="3" style="text-align: center; vertical-align: middle;">RUANG TINDAKAN</th>
              <th rowspan="3" style="text-align: center; vertical-align: middle;">JASA</th>
              <th rowspan="3" style="text-align: center; vertical-align: middle;">TARIF</th>
              <th colspan="2" style="text-align: center;">JS</th>
              <th colspan="2" style="text-align: center;">JP</th>
              <th colspan="2" style="text-align: center;">PROFIT</th>
              <th colspan="2" style="text-align: center;">PENGHASIL</th>
              <th colspan="2" style="text-align: center;">NON PENGHASIL</th>

              <th colspan="2" style="text-align: center;">DPJP</th>
              <th colspan="2" style="text-align: center;">PENGGANTI</th>
              <th colspan="2" style="text-align: center;">OPERATOR</th>
              <th colspan="2" style="text-align: center;">ANASTESI</th>
              <th colspan="2" style="text-align: center;">PENDAMPING</th>
              <th colspan="2" style="text-align: center;">KONSUL</th>
              <th colspan="2" style="text-align: center;">LABORAT</th>
              <th colspan="2" style="text-align: center;">PENANGGUNG JWB.</th>
              <th colspan="2" style="text-align: center;">RADIOLOGI</th>
              <th colspan="2" style="text-align: center;">RR</th>

              <th colspan="11">PENUNJANG</th>
            </tr>
            <tr>
              <th style="text-align: center; width: 20px;">%</th>
              <th style="text-align: center; width: 50px;">NOMINAL</th>
              <th style="text-align: center; width: 20px;">%</th>
              <th style="text-align: center; width: 50px;">NOMINAL</th>
              <th style="text-align: center; width: 20px;">%</th>
              <th style="text-align: center; width: 50px;">NOMINAL</th>
              <th style="text-align: center; width: 20px;">%</th>
              <th style="text-align: center; width: 50px;">NOMINAL</th>
              <th style="text-align: center; width: 20px;">%</th>
              <th style="text-align: center; width: 50px;">NOMINAL</th> 

              <th style="text-align: center; width: 100px;">DOKTER</th>
              <th style="text-align: center; width: 50">JASA</th>
              <th style="text-align: center; width: 100px;">DOKTER</th>
              <th style="text-align: center; width: 50">JASA</th>
              <th style="text-align: center; width: 100px;">DOKTER</th>
              <th style="text-align: center; width: 50">JASA</th>
              <th style="text-align: center; width: 100px;">DOKTER</th>
              <th style="text-align: center; width: 50">JASA</th>
              <th style="text-align: center; width: 100px;">DOKTER</th>
              <th style="text-align: center; width: 50">JASA</th>
              <th style="text-align: center; width: 100px;">DOKTER</th>
              <th style="text-align: center; width: 50">JASA</th>
              <th style="text-align: center; width: 100px;">DOKTER</th>
              <th style="text-align: center; width: 50">JASA</th>
              <th style="text-align: center; width: 100px;">DOKTER</th>
              <th style="text-align: center; width: 50">JASA</th>
              <th style="text-align: center; width: 100px;">DOKTER</th>
              <th style="text-align: center; width: 50">JASA</th>
              <th style="text-align: center; width: 100px;">DOKTER</th>
              <th style="text-align: center; width: 50">JASA</th>

              <th style="text-align: center; width: 50px;">JP PERAWAT</th>
              <th style="text-align: center; width: 50px;">PEN. ANASTESI</th>
              <th style="text-align: center; width: 50px;">PER. ASS. 1</th>
              <th style="text-align: center; width: 50px;">PER. ASS. 2</th>
              <th style="text-align: center; width: 50px;">INSTRUMEN</th>
              <th style="text-align: center; width: 50px;">SIRKULER</th>
              <th style="text-align: center; width: 50px;">PER. PEND. 1</th>
              <th style="text-align: center; width: 50px;">PER. PEND. 2</th>
              <th style="text-align: center; width: 50px;">APOTEKER</th>
              <th style="text-align: center; width: 50px;">ASS. APOTEKER</th>
              <th style="text-align: center; width: 50px;">ADM. FARMASI</th>
            </tr>            
          </thead>
          <tbody>
            @foreach($pasien as $pasien)
            <tr>
              <td>{{ strtoupper($pasien->waktu) }}</td>
              <td>{{ strtoupper($pasien->nama_pasien) }}</td>
              <td>{{ strtoupper($pasien->jenis_pasien) }}</td>
              <td>{{ strtoupper($pasien->ruang) }}</td>
              <td>{{ strtoupper($pasien->ruang_sub) }}</td>
              <td>{{ strtoupper($pasien->jasa) }}</td>
              <td align="right">{{ number_format($pasien->tarif,0) }}</td>
              <td align="right">{{ number_format($pasien->n_js,0) }} %</td>
              <td align="right">{{ number_format($pasien->js,0) }}</td>
              <td align="right">{{ number_format($pasien->n_jp,0) }} %</td>
              <td align="right">{{ number_format($pasien->jp,0) }}</td>
              <td align="right">{{ number_format($pasien->n_profit,0) }} %</td>
              <td align="right">{{ number_format($pasien->profit,0) }}</td>
              <td align="right">{{ number_format($pasien->n_penghasil,0) }} %</td>
              <td align="right">{{ number_format($pasien->penghasil,0) }}</td>
              <td align="right">{{ number_format($pasien->n_non_penghasil,0) }} %</td>
              <td align="right">{{ number_format($pasien->non_penghasil,0) }}</td>

              <td>{{ $pasien->dpjp_real }}</td>             
              <td align="right">{{ number_format($pasien->jasa_dpjp,0) }}</td>
              <td>{{ $pasien->pengganti }}</td>
              <td align="right">{{ number_format($pasien->jasa_pengganti,0) }}</td>
              <td>{{ $pasien->operator }}</td>
              <td align="right">{{ number_format($pasien->jasa_operator,0) }}</td>
              <td>{{ $pasien->anastesi }}</td>
              <td align="right">{{ number_format($pasien->jasa_anastesi,0) }}</td>
              <td>{{ $pasien->pendamping }}</td>
              <td align="right">{{ number_format($pasien->jasa_pendamping,0) }}</td>
              <td>{{ $pasien->konsul }}</td>
              <td align="right">{{ number_format($pasien->jasa_konsul,0) }}</td>
              <td>{{ $pasien->laborat }}</td>
              <td align="right">{{ number_format($pasien->jasa_laborat,0) }}</td>
              <td>{{ $pasien->tanggung }}</td>
              <td align="right">{{ number_format($pasien->jasa_tanggung,0) }}</td>
              <td>{{ $pasien->radiologi }}</td>
              <td align="right">{{ number_format($pasien->jasa_radiologi,0) }}</td>
              <td>{{ $pasien->rr }}</td>
              <td align="right">{{ number_format($pasien->jasa_rr,0) }}</td>

              <td align="right">{{ number_format($pasien->jp_perawat,0) }}</td>
              <td align="right">{{ number_format($pasien->pen_anastesi,0) }}</td>
              <td align="right">{{ number_format($pasien->per_asisten_1,0) }}</td>
              <td align="right">{{ number_format($pasien->per_asisten_2,0) }}</td>
              <td align="right">{{ number_format($pasien->instrumen,0) }}</td>
              <td align="right">{{ number_format($pasien->sirkuler,0) }}</td>
              <td align="right">{{ number_format($pasien->per_pendamping_1,0) }}</td>
              <td align="right">{{ number_format($pasien->per_pendamping_2,0) }}</td>
              <td align="right">{{ number_format($pasien->apoteker,0) }}</td>
              <td align="right">{{ number_format($pasien->ass_apoteker,0) }}</td>
              <td align="right">{{ number_format($pasien->admin_farmasi,0) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>      
    </div>
  </div>
</div>
@endif
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('#tabel').DataTable( {
        "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": 0
        }],
        "stateSave": true,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        "order": [[ 1, "asc" ]],
        "scrollX": true,
      });
    });
  </script>
@endsection