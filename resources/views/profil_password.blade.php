@extends('layouts.content')
@section('title','Profil')

@section('content')
<div class="navbar" style="margin-bottom:5px;">
  <div class="navbar-inner">
    <ul class="nav">
      <li><a href="{{ route('profil') }}">Data Profil</a></li>
      <li class="active"><a href="#">Ganti Password</a></li>
    </ul>
    <button type="submit" form="data" class="btn btn-primary pull-right">SIMPAN</button>
  </div>
</div>

<div class="content">
  @include('layouts.pesan')
  <form class="form-horizontal fprev" id="data" method="POST" action="{{ route('profil_password') }}">
  @csrf

    <div class="control-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
      <label for="current_password" class="span5 control-label">Password Saat Ini</label>
      <div class="controls span2">
        <input id="current_password" type="password" class="form-control" name="current_password" required autofocus>
      </div>
    </div>

    <div class="control-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
      <label for="new_password" class="span5 control-label">Password Baru</label>
      <div class="controls span2">
        <input id="new_password" type="password" class="form-control" name="new_password" required placeholder="Minimal 8 karakter" minlength="8">
      </div>
    </div>

    <div class="control-group">
      <label for="new_password_confirm" class="span5 control-label">Konfirmasi Password Baru</label>
      <div class="controls span2">
        <input id="new_password_confirm" type="password" class="form-control" name="new_password_confirm" required placeholder="Minimal 8 karakter" minlength="8">
      </div>
    </div>
  </form>
</div>
@endsection