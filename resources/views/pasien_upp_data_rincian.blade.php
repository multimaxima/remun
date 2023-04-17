@extends('layouts.content')
@section('title','Rincian Pembayaran Pasien')

@section('judul')
  <h4 class="page-title"> <i class="dripicons-checklist"></i> @yield('title')</h4>
@endsection

@section('style')
  <style type="text/css">
    table {
      font-size: 13px;
      line-height: 15px;
    }
  </style>
@endsection

@section('content')
<div class="wrapper">
  <div class="col-12" style="padding: 0 50px;">
    <div class="card konten">
      <div class="card-body">        
        <table width="100%">
          <tr>
            <td width="100" valign="top">Nama Pasien</td>
            <td width="10" valign="top">:</td>
            <td width="40%" valign="top">{{ strtoupper($pasien->nama) }}</td>
            <td width="100" valign="top">No. Register</td>
            <td width="10" valign="top">:</td>
            <td valign="top">{{ $pasien->register }}</td>
          </tr>
          <tr>
            <td valign="top">Alamat</td>
            <td valign="top">:</td>
            <td valign="top">{{ strtoupper($pasien->alamat) }}</td>
            <td valign="top">No. MR</td>
            <td valign="top">:</td>
            <td valign="top">{{ $pasien->no_mr }}</td>
          </tr>
          <tr>
            <td valign="top">Umur</td>
            <td valign="top">:</td>
            <td valign="top">{{ $pasien->umur }}</td>
            <td valign="top">Masuk</td>
            <td valign="top">:</td>
            <td valign="top">{{ $pasien->masuk }}</td>
          </tr>
          <tr>
            <td valign="top">Jenis Pasien</td>
            <td valign="top">:</td>
            <td valign="top">{{ strtoupper($pasien->jenis_pasien) }}</td>
            <td valign="top">Keluar</td>
            <td valign="top">:</td>
            <td valign="top">{{ $pasien->keluar }}</td>
          </tr>
          <tr>
            <td valign="top">Total Tagihan</td>
            <td valign="top">:</td>
            <td valign="top" style="font-weight: bold; color: red; font-size: 16px;">Rp. {{ number_format($pasien->tagihan,0) }}</td>
            <td valign="top"></td>
            <td valign="top"></td>
            <td valign="top"></td>
          </tr>
        </table>        
      </div>
    </div>
  </div>
</div>

<div class="wrapper" style="margin-top: 5px;">
  <div class="col-12" style="padding: 0 50px;">
    <div class="card konten">
      <div class="card-body">
        <table width="100%" class="table table-hover table-striped" id="tabel">
          <thead>
            <th>WAKTU</th>
            <th>RUANG</th>            
            <th>LAYANAN</th>
            <th>NOMINAL</th>
          </thead>
          <tbody>
            @foreach($layanan as $layanan)
            <tr>              
              <td>{{ strtoupper($layanan->waktu) }}</td>
              <td>{{ strtoupper($layanan->ruang) }}</td>
              <td>{{ strtoupper($layanan->jasa) }}</td>
              <td align="right">{{ number_format($layanan->tarif,0) }}</td>
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
        "order": [[ 1, "asc" ]],        
      });
    });
  </script>
@endsection