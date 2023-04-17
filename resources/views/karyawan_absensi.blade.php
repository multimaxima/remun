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
    <div class="row-fluid">
      <div class="span12" style="display: inline-flex;">
        <form class="form-inline" method="GET" action="{{ route('karyawan_absensi') }}" style="margin-top: 5px; margin-bottom: 0;">
        @csrf
          <input type="hidden" name="cari" value="{{ $cari }}">
          <input type="hidden" name="tampil" value="{{ $tampil }}">

          <label style="margin-top: 5px;">Tanggal</label>
          <input type="date" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit();" style="width: 130px;">

          <select name="id_ruang" id="id_ruang" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">=== SEMUA RUANG ===</option>
            @foreach($ruang as $rng)
              <option value="{{ $rng->id }}" {{ $id_ruang == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
            @endforeach
          </select>

          <select name="id_bagian" id="id_bagian" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">=== SEMUA BAGIAN ===</option>
            @foreach($bagian as $bag)
              <option value="{{ $bag->id }}" {{ $id_bagian == $bag->id? 'selected' : null }}>{{ strtoupper($bag->bagian) }}</option>
            @endforeach
          </select>
        </form>
        <button class="btn btn-primary" style="margin-left: 5px;" data-toggle="modal" data-target="#data_baru">
          EXPORT
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal-lg hide fade" id="data_baru">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title">Export Absensi Karyawan</h5>
  </div>
  <div class="modal-body">
    <form class="form-horizontal" id="baru_data">
    @csrf

      <div class="control-group">
        <label class="control-label span3">Ruang</label>
        <div class="controls span7">
          <select class="form-control" name="jenis" id="jenis" required autofocus>
            <option value="">SEMUA RUANG</option>
            @foreach($ruang as $rng)
              <option value="{{ $rng->id }}">{{ strtoupper($rng->ruang) }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Bagian</label>
        <div class="controls span7">
          <select class="form-control" name="jenis" id="jenis" required autofocus>
            <option value="">SEMUA BAGIAN</option>
            @foreach($bagian as $bag)
              <option value="{{ $bag->id }}">{{ strtoupper($bag->bagian) }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Periode</label>
        <div class="controls span7 form-inline">
          <input type="date" name="dari" style="width: 150px;">
          <label style="margin: 0 5px;">s/d</label>
          <input type="date" name="sampai" style="width: 150px;">
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">             
      <button type="submit" form="baru_data" class="btn bprev">EXPORT</button>
      <button type="button" class="btn" data-dismiss="modal">BATAL</button>
    </div>
  </div>
</div>

<div class="content">
  <form method="GET" action="{{ route('karyawan_absensi') }}" class="form-inline" style="margin-bottom: 5px;">
  @csrf
    <input type="hidden" name="id_ruang" value="{{ $id_ruang }}">
    <input type="hidden" name="id_bagian" value="{{ $id_bagian }}">
    <input type="hidden" name="tanggal" value="{{ $tanggal }}">

    Menampilkan
    <select onchange="this.form.submit();" name="tampil">
      <option value="10" {{ $tampil == '10'? 'selected' : null }}>10</option>
      <option value="25" {{ $tampil == '25'? 'selected' : null }}>25</option>
      <option value="50" {{ $tampil == '50'? 'selected' : null }}>50</option>
      <option value="100" {{ $tampil == '100'? 'selected' : null }}>100</option>
      <option value="9999999999999" {{ $tampil == '9999999999999'? 'selected' : null }}>Semua</option>
    </select> data

    <input type="text" name="cari" class="pull-right" placeholder="Cari..." value="{{ $cari }}">
  </form>

  @include('layouts.pesan')
  <table id="tabel" width="100%" class="table table-striped table-hover table-bordered">
    <thead>
      <tr>
        <th rowspan="2"></th>
        <th rowspan="2" style="padding: 0 10px;">No.</th>
        <th rowspan="2" style="padding: 0 10px;">Nama</th>
        <th rowspan="2" style="padding: 0 10px;">Bagian</th>
        <th colspan="3" style="padding: 0 10px;">Ruang Kerja</th>        
        <th rowspan="2" style="padding: 0 10px;">Kehadiran</th>
      </tr>
      <tr>
        <th style="padding: 0 10px;">Utama</th>
        <th style="padding: 0 10px;">1</th>
        <th style="padding: 0 10px;">2</th>
      </tr>      
    </thead>
    <tbody>
      <?php $no = 0;?>
      @foreach($karyawan as $kary)
      <?php $no++ ;?>
      <tr>
        <td class="min">
          <div class="btn-group">
            @if($kary->tanggal == date("Y-m-d"))        
              <a href="#" class="btn btn-info btn-mini edit" data-id="{{ $kary->id }}" title="Pindah Ruang">
                <i class="icon-refresh"></i>
              </a>
            @endif
            <button type="submit" form="histori{{ $kary->id_users }}" class="btn btn-info btn-mini" title="Histori Data Karyawan">
              <i class="icon-time"></i>
            </button>
          </div>

          <form hidden method="GET" action="{{ route('karyawan_histori') }}" id="histori{{ $kary->id_users }}" target="_blank">
          @csrf
            <input type="text" name="id" value="{{ $kary->id_users }}">
          </form>
        </td>
        <td class="min" style="text-align: right; padding-right: 5px;">{{ $no }}.</td>
        <td>{{ $kary->nama }}</td>        
        <td class="min">{{ strtoupper($kary->bagian) }}</td>
        <td class="min">{{ strtoupper($kary->ruang) }}</td>
        <td class="min">{{ strtoupper($kary->ruang_1) }}</td>
        <td class="min">{{ strtoupper($kary->ruang_2) }}</td>                   
        <td class="min" style="text-align: center;">
          @if($kary->cuti == 0)
          <select class="hadir" data-id="{{ $kary->id }}"  style="margin-bottom: 0;">
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
      </tr>
      @endforeach
    </tbody>
  </table>

  <div>
    <div class="pull-left" style="font-size: 12px;">
      Menampilkan {{ number_format($karyawan->firstItem(),0) }} - {{ number_format($karyawan->lastItem(),0) }} dari {{ number_format($karyawan->total(),0) }} data
    </div>                               
    <div class="pagination pagination-small pull-right">
      {!! $karyawan->appends(request()->input())->render("pagination::bootstrap-4"); !!}
    </div>
  </div>
</div>       

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
    $(document).ready(function() {
      $('.hadir').on("change",function() {
        $id    = $(this).attr('data-id');
        $hadir = $(this).val();

        $.ajax({
          url : "{{ route('karyawan_his_absen') }}",
          type: "GET",
          data: {'id': $id, 'hadir': $hadir},
        });
      });

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
    
      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.22*box.clientHeight);

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