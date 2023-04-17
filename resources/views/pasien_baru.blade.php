@extends('layouts.content')
@section('title','Pasien Baru')

@section('content')
<div class="navbar" style="margin-bottom:5px;">
  <div class="navbar-inner">
    <div class="btn-group pull-right">
      <button type="submit" form="data" class="btn btn-primary">SIMPAN</button>
    </div>
  </div>
</div>

<div class="content">
  <form class="form-horizontal container-fluid fprev" id="data" method="POST" action="{{ route('pasien_baru') }}">
  @csrf
    @if($c_ruang->jalan == 1)
      <input type="hidden" name="id_pasien_jenis_rawat" value="1">
    @endif

    @if($c_ruang->inap == 1)
      <input type="hidden" name="id_pasien_jenis_rawat" value="2">
    @endif

    <div class="control-group">
      <label class="control-label span2">Nama</label>
      <div class="controls span9">
        <input type="text" class="form-control" name="nama" required autofocus oninvalid="this.setCustomValidity('Silahkan mengisi nama pasien')">
      </div>
    </div>

    <div class="control-group">
      <label class="control-label span2">Alamat</label>
      <div class="controls span9">
        <input type="text" class="form-control" name="alamat">
      </div>
    </div>          

    <div class="control-group">
      <label class="control-label span2">Umur</label>
      <div class="controls span1">
        <div class="input-append">
          <input type="number" name="umur_thn" step="1" class="form-control" required oninvalid="this.setCustomValidity('Silahkan mengisi umur pasien')">
          <span class="add-on">Thn.</span>
        </div>
      </div>            
      <div class="controls span1 offset1">
        <div class="input-append">
          <input type="number" name="umur_bln" step="1" class="form-control">
          <span class="add-on">Bln.</span>
        </div>
      </div>

      <label class="control-label span2">Nomor MR</label>
      <div class="controls span4">
        <input type="text" class="form-control" name="no_mr" required oninvalid="this.setCustomValidity('Silahkan mengisi Nomor MR pasien')">
      </div>            
    </div>

    <div class="control-group">
      <label class="control-label span2">Kelamin</label>
      <div class="controls span9">
        <select class="form-control" name="id_kelamin" required size="2" oninvalid="this.setCustomValidity('Tentukan jenis kelamin pasien')" style="width: 101.5%;">
          <option value="1">LAKI-LAKI</option>
          <option value="2">PEREMPUAN</option>
        </select>
      </div>            
    </div>      

    <div class="control-group">
      <label class="control-label span2">Jenis Pasien</label>
      <div class="controls span9">
        <select class="form-control" name="id_pasien_jenis" required size="6" oninvalid="this.setCustomValidity('Tentukan jenis pasien')" style="width: 101.5%;">
          @foreach($jenis as $jns)
            <option value="{{ $jns->id }}">{{ strtoupper($jns->jenis) }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label span2">DPJP</label>
      <div class="controls span9">
        <select class="form-control" name="id_dpjp" required size="10" oninvalid="this.setCustomValidity('Tentukan DPJP yang menangani pasien')" style="width: 101.5%;">
          @foreach($dpjp as $dok)
            <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="control-group">
      <div class="controls span9 offset2">
        <div class="alert alert-danger bg-danger text-white" role="alert">
          * <b><i>Pastikan</i></b> bahwa data pasien yang Anda entri sudah benar !!!
        </div>
      </div>
    </div>
  </form>
</div>
@endsection