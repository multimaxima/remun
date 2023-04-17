@extends('layouts.content')
@section('title','Jasa Dokter Umum')

@section('content')
<div class="navbar" style="margin-bottom:5px;">
  <div class="navbar-inner">
    <div class="btn-group pull-right">
      <button type="submit" form="kembali" class="btn btn-primary">KEMBALI</button>
      <form hidden method="GET" action="{{ route('remunerasi') }}" id="kembali">
      @csrf
      </form>
    </div>
  </div>
</div>

<div class="content" style="margin-bottom:5px;">
  <center>
    <form class="form-inline" method="POST" action="{{ route('remunerasi_umum_simpan') }}" style="margin-top: 5px; margin-bottom: 0;">
    @csrf
      <input type="hidden" name="id_remun" id="id_remun" value="{{ $remun }}">

      <label>Jasa Dokter Umum Asal</label>
      <div class="input-prepend">
        <span class="add-on">Rp.</span>
        <input type="text" name="lama" class="form-control" style="width: 130px; text-align: right;" readonly value="{{ number_format($total->r_medis,2) }}">
      </div>

      <label style="margin-left: 10px;">Jasa Dokter Umum Baru</label>
      <div class="input-prepend">
        <span class="add-on">Rp.</span>
        <input type="text" name="baru" id="baru" class="form-control nominal" style="width: 130px;" required autofocus>
      </div>

      <button type="submit" class="btn btn-primary">SIMPAN</button>
    </form>
  </center>
</div>

<div class="content">
  @include('layouts.pesan')
  <table width="100%" id="tabel" class="table table-hover table-striped" style="font-size: 13px;">
    <thead>
      <tr>
        <th class="min" style="text-align: center;">No.</th>
        <th style="text-align: center; vertical-align: middle;">Nama Dokter</th>
        <th style="text-align: center; vertical-align: middle;">Bagian</th>
        <th style="text-align: center; vertical-align: middle;">Jasa Asal</th>
        <th style="text-align: center; vertical-align: middle;">Jasa Baru</th>
      </tr>      
    </thead>
    <tbody id="rincian">      
    </tbody>
  </table>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    window.onload=function() {
      $id_remun  = document.getElementById('id_remun').value;

      $.ajax({
        type : 'get',
        url : '{{ route("remunerasi_umum_jasa") }}',
        data: {'baru': 0,'id_remun': $id_remun},
        success: function(data){
          $('#rincian').html(data);
        }
      });
    };

    $(document).ready(function() {
      $('#baru').on('input',function(){
        $baru   = $(this).val();
        $id_remun  = document.getElementById('id_remun').value;

        $.ajax({
          type : 'get',
          url : '{{ route("remunerasi_umum_jasa") }}',
          data: {'baru': $baru,'id_remun': $id_remun},
          success: function(data){
            $('#rincian').html(data);
          }
        });
      });
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#tabel').DataTable( {             
        scrollY:        "60vh",
        paging:         false,
        searching:      false,
        stateSave:      true,
        sort:           false,
        info:           false,
      });
    });
  </script>
@endsection