@extends('layouts.content')
@section('title','Pengumuman')

@section('content')
<div class="navbar">
  <div class="navbar-inner">
    <button type="submit" form="kembali" class="btn btn-primary">KEMBALI</button>
    <form hidden method="GET" action="{{ route('pengumuman_user') }}" id="kembali">
    @csrf
    </form>
  </div>
</div>

<div class="content">
  <div class="span12">
    <div class="control-group">
      <label class="control-label span2">Judul</label>
      <label class="span10" style="margin-top: 5px;">{{ $umum->judul }}</label>
    </div>

    <div class="control-group">
      <label class="control-label span2">Pengumuman</label>
      <label class="span10" style="margin-top: 5px;">
        {!!html_entity_decode(strip_tags($umum->isi))!!}
      </label>
    </div>

    <div class="control-group">
      <label class="control-label span2">Penanggung Jawab</label>
      <label class="span10" style="margin-top: 5px;">{{ $umum->nama }}</label>
    </div>
  </div>
</div>
@endsection