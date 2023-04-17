@extends('layouts.content')
@section('title','Gaji Pokok, TPP, Pajak & Koreksi Gaji Karyawan')

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
        <form class="form-inline" method="GET" action="{{ route('karyawan_gapok') }}" style="margin-top: 5px; margin-bottom: 0; float: left;">
        @csrf
          <input type="hidden" name="cari" value="{{ $cari }}">
          <input type="hidden" name="tampil" value="{{ $tampil }}">

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
      </div>
    </div>
  </div>
</div>

<div class="content">
  <form method="GET" action="{{ route('karyawan_gapok') }}" class="form-inline" style="margin-bottom: 5px;">
  @csrf
    <input type="hidden" name="id_ruang" value="{{ $id_ruang }}">
    <input type="hidden" name="id_bagian" value="{{ $id_bagian }}">

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
  <table id="tabel" class="table table-hover table-striped table-bordered">
    <thead>
      <th></th>
      <th style="padding: 0 10px;">No.</th>
      <th style="padding: 0 10px;">Nama Karyawan</th>
      <th style="padding: 0 10px;">Bagian</th>
      <th style="padding: 0 10px;">Tenaga</th>
      <th style="padding: 0 10px;">Gapok</th>
      <th style="padding: 0 10px;">Indeks Dasar</th>
      <th style="padding: 0 10px;">Score</th>
      <th style="padding: 0 10px;">TPP</th>
      <th style="padding: 0 10px;">Koreksi</th>
      <th style="padding: 0 10px;">Pajak</th>
    </thead>
    <tbody>
      <?php $no = 0;?>
      @foreach($karyawan as $kary)       
      <?php $no++ ;?>      
<div class="modal hide fade" id="modal_edit{{ $kary->id }}">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <label style="font-size: 14px; font-weight: bold; margin-top: 5px;" id="judul"></label>
  </div>
  <div class="modal-body">
    <form class="form-horizontal container-fluid fprev" id="form_edit{{ $kary->id }}" method="POST" action="{{ route('karyawan_gapok_simpan') }}">
    @csrf
      <input type="hidden" name="id" value="{{ $kary->id }}">

      <div class="control-group row" style="margin-bottom: 0;">
        <label class="control-label span5">Gaji Pokok</label>
        <div class="controls span3">
          <div class="input-prepend">
            <span class="add-on">Rp.</span>
            <input type="text" class="form-control nominal" style="text-align: right;" name="gapok" value="{{ $kary->gapok }}">
          </div>
        </div>
      </div>

      <div class="control-group row" style="margin-bottom: 0;">
        <label class="control-label span5">TPP</label>
        <div class="controls span3">
          <div class="input-prepend">
            <span class="add-on">Rp.</span>
            <input type="text" class="form-control nominal" style="text-align: right;" name="tpp" value="{{ $kary->tpp }}">
          </div>
        </div>
      </div>

      <div class="control-group row" style="margin-bottom: 0;">
        <label class="control-label span5">Koreksi Gaji</label>
        <div class="controls span3">
          <div class="input-prepend">
            <span class="add-on">Rp.</span>
            <input type="text" class="form-control nominal" style="text-align: right;" name="koreksi" value="{{ $kary->koreksi }}">
          </div>
        </div>
      </div>

      <div class="control-group row" style="margin-bottom: 0;">
        <label class="control-label span5">Pajak</label>
        <div class="controls span2">
          <div class="input-append">
            <input type="number" class="form-control" name="pajak" value="{{ $kary->pajak }}" step="any">
            <span class="add-on">%</span>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit{{ $kary->id }}" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>
      <tr>
        <td class="min">
          <button class="btn btn-info btn-mini edit" data-toggle="modal" data-target="#modal_edit{{ $kary->id }}">
            <i class="icon-edit"></i>
          </button>
        </td>
        <td class="min" style="text-align: right; padding-right: 5px;">{{ $no }}.</td>
        <td>{{ $kary->nama }}</td>
        <td>{{ strtoupper($kary->bagian) }}</td>
        <td>{{ strtoupper($kary->tenaga) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->gapok,0) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->indeks_dasar,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->skore,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->tpp,0) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->koreksi,0) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->pajak,2) }} %</td>
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
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {      
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