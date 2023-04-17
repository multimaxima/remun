@extends('layouts.content')
@section('title','Data Tindakan Pasien')

@section('judul')
  <h4 class="page-title"> <i class="dripicons-user-id"></i> @yield('title')</h4>
@endsection

@section('content')
<div class="wrapper">
  <div class="col-12" style="padding: 0 50px;">
    <div id="accordion" style="box-shadow: 1px 1px 10px #8d8d8d;">
      <div class="card">
        <div class="card-header p-3" id="headingOne">
          <h6 class="m-0">
            <a href="#collapseOne" class="text-dark" data-toggle="collapse" aria-expanded="true" aria-controls="collapseOne">
              FILTER DATA
            </a>
          </h6>
        </div>
        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
          <div class="card-body">            
            <form class="form-horizontal col-8 offset-2" method="GET" action="{{ route('pasien_per_ruang') }}">
            @csrf    

              <div class="form-group row">
                <label class="control-label col-2" style="margin-top: 5px;">Tanggal</label>
                <div class="col-4">
                  <input type="date" name="awal" value="{{ $awal }}" class="form-control form-control-sm" style="margin: 0 10px;" required autofocus>
                </div>
              </div>

              <div class="form-group row">
                <label class="control-label col-2" style="margin-top: 5px;">Sampai Dengan</label>
                <div class="col-4">
                  <input type="date" name="akhir" value="{{ $akhir }}" class="form-control form-control-sm" style="margin: 0 10px;" required>
                </div>
              </div>

              <div class="form-group row">
                <label class="control-label col-2" style="margin-top: 5px;">Ruang</label>
                <div class="col-8">
                  <select class="form-control form-control-sm" name="id_ruang" style="margin: 0 10px;" required>
                    <option value=""></option>
                    @foreach($ruang as $ruang)
                      <option value="{{ $ruang->id }}" {{ $id_ruang == $ruang->id? 'selected' : null }}>{{ strtoupper($ruang->ruang) }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="control-label col-2" style="margin-top: 5px;">DPJP</label>
                <div class="col-8">
                  <select class="form-control form-control-sm" name="id_dpjp" style="margin: 0 10px;">
                    <option value=""></option>
                    @foreach($dpjp as $dpjp)
                      <option value="{{ $dpjp->id }}" {{ $id_dpjp == $dpjp->id? 'selected' : null }}>{{ strtoupper($dpjp->nama) }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="control-label col-2" style="margin-top: 5px;">Jenis</label>
                <div class="col-8">
                  <select class="form-control form-control-sm" name="id_jenis" style="margin: 0 10px;">
                    <option value=""></option>
                    @foreach($jenis as $jenis)
                      <option value="{{ $jenis->id }}" {{ $id_jenis == $jenis->id? 'selected' : null }}>{{ strtoupper($jenis->jenis_pasien) }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-8 offset-2">
                  <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-check"></i> TAMPILKAN
                  </button>
                </div>
              </div>
            </form>            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="wrapper" style="margin-top: 10px;">
  <div class="col-12" style="padding: 0 50px;">
    <div class="card m-b-30 konten">
      <div class="card-body">
        @if(count($pasien) > 0)
        <table width="100%" class="table table-hover table-striped col-6 offset-3" style="font-size: 13px; margin-bottom: 10px;">
          <thead>
            <th>JENIS PASIEN</th>
            <th width="150">JUMLAH PASIEN</th>
            <th width="150">JUMLAH TARIF</th>
          </thead>
          <tbody>
            <tr>
              <td>RAWAT JALAN UMUM</td>
              <td align="right">{{ number_format($rajal_umum,0) }}</td>
              <td align="right">{{ number_format($tarif->rajal_umum,0) }}</td>
            </tr>
            <tr>
              <td>RAWAT JALAN JKN</td>
              <td align="right">{{ number_format($rajal_jkn,0) }}</td>
              <td align="right">{{ number_format($tarif->rajal_jkn,0) }}</td>
            </tr>
            <tr>
              <td>RAWAT INAP UMUM</td>
              <td align="right">{{ number_format($ranap_umum,0) }}</td>
              <td align="right">{{ number_format($tarif->ranap_umum,0) }}</td>
            </tr>
            <tr>
              <td>RAWAT INAP JKN</td>
              <td align="right">{{ number_format($ranap_jkn,0) }}</td>
              <td align="right">{{ number_format($tarif->ranap_jkn,0) }}</td>
            </tr>
          </tbody>
          <tfoot>
            <th>JUMLAH</th>
            <th style="text-align: right;">{{ number_format($rajal_umum + $rajal_jkn + $ranap_umum + $ranap_jkn,0) }}</th>
            <th style="text-align: right;">{{ number_format($tarif->rajal_umum + $tarif->rajal_jkn + $tarif->ranap_umum + $tarif->ranap_jkn,0) }}</th>
          </tfoot>
        </table>
        <hr>
        @endif

        <table width="100%" id="tabel" class="table table-hover table-striped" style="font-size: 13px; margin-bottom: 10px;">
          <thead>
            <th>KELUAR</th>
            <th>TANGGAL</th>
            <th>NAMA PASIEN</th>
            <th width="60">MR</th>
            <th>REGISTER</th>            
            <th>JENIS</th>
            <th>RUANG</th>
            <th>DPJP TINDAKAN</th>
            <th>JASA</th>
            <th>TARIF</th>
          </thead>
          <tbody>
            @foreach($pasien as $pas)  
              <tr>                              
                <td>{{ strtoupper($pas->keluar) }}</td>
                <td>{{ strtoupper($pas->waktu) }}</td>
                <td>{{ strtoupper($pas->pasien) }}</td>
                <td class="min" align="center">{{ $pas->no_mr }}</td>
                <td class="min" align="center">{{ $pas->register }}</td>
                <td>{{ strtoupper($pas->jenis_pasien) }}</td>
                <td>{{ strtoupper($pas->ruang) }}</td>
                <td>{{ $pas->dpjp }}</td>
                <td>{{ strtoupper($pas->jasa) }}</td>
                <td align="right">{{ number_format($pas->tarif,0) }}</td>
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
      $('#tabel').DataTable( {
        "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": 0
        }],
        "stateSave": true,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        "order": [[ 0, "asc" ]],
      });
    });
  </script>
@endsection