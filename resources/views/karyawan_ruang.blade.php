@extends('layouts.content')
@section('title','Absensi Karyawan')

@section('style')
  <style type="text/css">
    td a{
      color: #424242;
    }
  </style>
@endsection

@section('content')
<div class="navbar">
  <div class="navbar-inner">
    <label class="pull-right" style="margin-top: 10px; font-weight: bold;">ABSENSI KARYAWAN {{ strtoupper($tanggal->tanggal) }}</label>
  </div>
</div>

<div class="content">
  @include('layouts.pesan')
  <table id="tabel" width="100%" class="table table-striped table-hover table-bordered" style="font-size: 12px;">
    <thead>
      <tr>
        <th rowspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;"></th>
        <th rowspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">KEHADIRAN</th>
        <th rowspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">No.</th>
        <th rowspan="2" width="300" style="vertical-align: middle; text-align: center; padding: 0 10px;">Nama</th>
        <th rowspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">Bagian</th>
        <th colspan="3" style="vertical-align: middle; text-align: center; padding: 0 10px;">Ruang Kerja</th>
      </tr>
      <tr>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;">Utama</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;">Tambahan 1</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;">Tambahan 2</th>
      </tr>      
    </thead>
    <tbody>
      <?php $no = 0;?>
      @foreach($karyawan as $kary)
      <?php $no++ ;?>
      <tr>        
        <td class="min" style="text-align: center; padding: 3px 5px;">          
          <div class="btn-group">
            <a href="#" class="btn btn-info edit" style="font-size: 12px;" data-id="{{ $kary->id }}">
              PINDAH RUANG
            </a>          
            @if($a_param->histori == 1)
            <button class="btn btn-info edit_history" title="Histori Data Karyawan" data-id="{{ $kary->id_users }}">
              <i class="fa fa-clock"></i>
            </button>
            @endif
          </div>
        </td>        
        <td class="min">
          @if($kary->cuti == 0)
          <select class="hadir" data-id="{{ $kary->id }}" style="margin-bottom: 0;">
            @foreach($absen as $abs)
            <option value="{{ $abs->id }}" {{ $kary->hadir == $abs->id? 'selected' : null }}>{{ $abs->absen }}</option>
            @endforeach
          </select>
          @else
          <select style="margin-bottom: 0;" disabled>
            @foreach($absen as $abs)
            <option value="{{ $abs->id }}" {{ $kary->hadir == $abs->id? 'selected' : null }}>{{ $abs->absen }}</option>
            @endforeach
          </select>
          @endif
        </td>
        <td class="min" style="text-align: right; padding-right: 5px;">{{ $no }}.</td>
        <td>{{ $kary->nama }}</td>
        <td class="min">{{ strtoupper($kary->bagian) }}</td>
        <td class="min">{{ strtoupper($kary->ruang) }}</td>
        <td class="min">{{ strtoupper($kary->ruang_1) }}</td>
        <td class="min">{{ strtoupper($kary->ruang_2) }}</td>                 
      </tr>
      @endforeach
    </tbody>
  </table>
</div>    

<form hidden method="GET" action="{{ route('karyawan_histori') }}" id="form_histori" target="_blank">
@csrf
  <input type="text" name="id" id="id_edit_history">
</form> 

<div class="modal hide fade" id="modal_karyawan_pindah">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <label class="modal-title" style="font-size: 15px; padding: 5px; font-weight: bold;" id="edit_judul"></label>
  </div>
  <div class="modal-body">
    <form method="POST" id="pindah_ruang" action="{{ route('karyawan_pindah_ruang') }}">
    @csrf
      <input type="hidden" name="id" id="edit_id">

      <select name="id_ruang" id="edit_id_ruang" class="form-control" size="15">
        @foreach($ruang as $rng)
        <option value="{{ $rng->id }}">{{ $rng->ruang }}</option>
        @endforeach
      </select>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">             
      <button type="submit" form="pindah_ruang" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>  
@endsection

@section('script')
  <script type="text/javascript">
    window.onload=function() {
      $id_ruang = document.getElementById('id_ruang').value;
      document.getElementById('c_id_ruang').value = $id_ruang;
    }
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('.hadir').on("change",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{ route('karyawan_hadir') }}?id="+id,
          type: "GET",
          dataType: "JSON"          
        });
      });      
    });

    $('.edit_history').on("click",function() {
      var id = $(this).attr('data-id');
      $('#id_edit_history').val(id);
      $('#form_histori').submit();
    });

    $(document).ready(function() {
      $('.edit').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{route('karyawan_pindah_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#edit_id').val(data.id);
            $('#edit_id_ruang').val(data.id_ruang);
            $('#edit_judul').html(data.nama);
            $('#modal_karyawan_pindah').modal('show');
          }
        });
      });
    });

    $(document).ready(function() {
      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.11*box.clientHeight);

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
  </script>
@endsection