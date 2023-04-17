@extends('layouts.content')
@section('title','Pengumuman')

@section('style')
  <link rel="stylesheet" type="text/css" href="/vendor/summernote/summernote-lite.css">
@endsection

@section('content')
<div class="navbar">
  <div class="navbar-inner">
    <div class="btn-group">
      <button type="submit" form="kembali" class="btn btn-primary">KEMBALI</button>
      <button type="submit" form="pengumuman" class="btn btn-primary">SIMPAN</button>
    </div>

    <form hidden method="GET" action="{{ route('pengumuman') }}" id="kembali">
    @csrf
    </form>
  </div>
</div>

<div class="content">
  <form class="form-horizontal container-fluid" method="POST" action="{{ route('pengumuman_baru_simpan') }}" id="pengumuman">
  @csrf

    <div class="control-group">
      <label class="control-label span2">Judul</label>
      <div class="controls span10">
        <input type="text" name="judul" class="form-control" required autofocus>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label span2">Isi</label>
      <div class="controls span10">
        <textarea class="summernote" name="isi" required></textarea>
      </div>
    </div>

    <div class="control-group" style="margin-top: 5px;">
      <label class="control-label span2">Penanggung Jawab</label>
      <div class="controls span10">
        <select class="form-control" name="id_karyawan" required>
          <option value=""></option>
          @foreach($karyawan as $kar)
            <option value="{{ $kar->id }}">{{ $kar->nama }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="control-group" style="margin-top: 5px;">
      <label class="control-label span2">Tampil dari</label>
      <div class="controls span10" style="display: inline-flex;">
        <input type="date" name="awal">
        <label style="margin: 5px 10px;">Sampai</label>
        <input type="date" name="akhir">
      </div>
    </div>
  </form>
</div>
@endsection

@section('script')
  <script type="text/javascript" src="/vendor/summernote/summernote-lite.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('.summernote').summernote({
        height: 300,
        tabsize: 2
      });
    });
  </script>
@endsection