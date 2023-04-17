@extends('layouts.content')
@section('title','Data Pasien Dalam Perawatan')

@section('content')
<div class="navbar" style="margin-bottom:5px;">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12">
        <form class="form-inline" method="GET" action="{{ route('pasien_perawatan_data') }}" style="margin-top: 5px; margin-bottom: 0;">
        @csrf
          
          <label>Tanggal</label>
          <input type="date" name="awal" style="width: 130px;">
          <label>s/d</label>
          <input type="date" name="akhir" style="width: 130px;">

          <select name="id_pasien_jenis">
            <option value="">Pilih Jenis Pasien</option>
          </select>

          <select name="id_pasien_jenis_rawat">
            <option value="">Pilih Jenis Perawatan</option>
          </select>

          <select name="id_ruang">
            <option value="">Pilih Ruang</option>
          </select>

          <select name="id_dpjp">
            <option value="">Pilih DPJP</option>
          </select>          
          
          <button type="submit" class="btn btn-primary" style="margin-top: 0;">
            TAMPILKAN
          </button>
        </form>        
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
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        "order": [[ 1, "asc" ]],
      });
    });
  </script>
@endsection