@extends('layouts.content')
@section('title','Rincian Layanan Pasien')

@section('content')
<div class="navbar" style="margin-bottom:5px;">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12">
        <a href="{{ route('pasien_upp') }}" style="float: right;" class="btn btn-primary" title="Kembali">
          KEMBALI
        </a>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <table width="100%" style="margin-bottom: 15px;">
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
      <td valign="top">
        {{ $pasien->umur_thn }} Thn.
        @if($pasien->umur_bln)
          {{ $pasien->umur_bln }}} Bln.
        @endif
      </td>
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

  <table width="100%" class="table table-hover table-striped" id="tabel" style="font-size: 13px;">
    <thead>
      <th></th>
      <th>WAKTU</th>
      <th>RUANG</th>            
      <th>LAYANAN</th>
      <th>NOMINAL</th>
    </thead>
    <tbody>
      @foreach($layanan as $layanan)
      <tr>
        <td class="min">
          <a href="{{ route('pasien_layanan_hapus',Crypt::encrypt($layanan->id)) }}" class="btn btn-danger btn-mini" title="Hapus Layanan" onclick="return confirm('Hapus Layanan ?')">
            <i class="icon-trash"></i>
          </a>
        </td>
        <td>{{ strtoupper($layanan->waktu) }}</td>
        <td>{{ strtoupper($layanan->ruang) }}</td>
        <td>{{ strtoupper($layanan->jasa) }}</td>
        <td style="text-align: right;">{{ number_format($layanan->tarif,0) }}</td>
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