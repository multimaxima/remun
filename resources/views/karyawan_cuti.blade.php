@extends('layouts.content')
@section('title','Cuti Karyawan')

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
      <div class="span12">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#data_baru">
          TAMBAH
        </button>
      </div>
    </div>
  </div>
</div>

<div class="content">
  @include('layouts.pesan')
  <form method="GET" action="{{ route('karyawan_cuti') }}" class="form-inline" style="margin-bottom: 5px;">
  @csrf

    Menampilkan
    <select onchange="this.form.submit();" name="tampil">
      <option value="10" {{ $tampil == '10'? 'selected' : null }}>10</option>
      <option value="25" {{ $tampil == '25'? 'selected' : null }}>25</option>
      <option value="50" {{ $tampil == '50'? 'selected' : null }}>50</option>
      <option value="100" {{ $tampil == '100'? 'selected' : null }}>100</option>
      <option value="999999999999999" {{ $tampil == '999999999999999'? 'selected' : null }}>Semua</option>
    </select> data

    <input type="text" name="cari" class="pull-right" placeholder="Cari..." value="{{ $cari }}">
  </form>

  <table id="tabel" width="100%" class="table table-striped table-hover table-bordered" style="font-size: 13px;">
    <thead>
      <th></th>
      <th>Nama Karyawan</th>
      <th>Jenis</th>
      <th>Mulai</th>
      <th>Sampai</th>
      <th>Keterangan</th>
    </thead>
    <tbody>
      @foreach($cuti as $cuti)
      <tr>
        <td class="min">
          <div class="btn-group">            
            <a href="{{ route('karyawan_cuti_hapus',Crypt::encrypt($cuti->id)) }}" class="btn btn-info btn-mini" title="Hapus Data" onclick="return confirm('Hapus data cuti {{ $cuti->nama }} ?')">
              <i class="icon-trash"></i>
            </a>
          </div>
        </td>
        <td>{{ $cuti->nama }}</td>
        <td>{{ $cuti->absen }}</td>
        <td>{{ strtoupper($cuti->tgl_awal) }}</td>
        <td>{{ strtoupper($cuti->tgl_akhir) }}</td>
        <td>{{ strtoupper($cuti->keterangan) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="modal hide fade" id="data_baru">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Tambah Cuti Karyawan</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal container-fluid fprev" id="baru_data" method="POST" action="{{ route('karyawan_cuti_baru') }}">
    @csrf

      <div class="control-group">
        <label class="control-label span3">Nama Karyawan</label>
        <div class="controls span9">
          <select class="form-control select2" name="id_karyawan" autofocus required style="width: 104%;">
            <option value=""></option>
            @foreach($karyawan as $kary)
              <option value="{{ $kary->id }}">{{ $kary->nama }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Jenis Cuti</label>
        <div class="controls span9">
          <select class="form-control" name="id_jenis" required style="width: 104%;">
            <option value=""></option>
            @foreach($jenis as $jns)
              <option value="{{ $jns->id }}">{{ $jns->absen }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Cuti Dari</label>
        <div class="controls span4">
          <input type="date" class="form-control" name="awal" required value="{{ date('Y-m-d') }}">
        </div>
          
        <label class="control-label span1">s/d</label>
        <div class="controls span4">
          <input type="date" class="form-control" name="akhir" required value="{{ date('Y-m-d') }}">
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Keterangan</label>
        <div class="controls span9">
          <input type="text" class="form-control" name="keterangan" required>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="baru_data" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {      
      $('.select2').select2();     
    
      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.21*box.clientHeight);

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