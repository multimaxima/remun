@extends('mobile.layouts.content')

@section('bawah')
  <li><a href="{{ route('jasa_remun') }}"><i class="fa fa-heart"></i>Remunerasi</a></li>
  <li><a href="{{ route('informasi_software') }}"><i class="fa fa-info"></i>Informasi</a></li>
@endsection

@section('content')
<div class="page-content-wrapper">
<div class="container">
  <div class="profile-wrapper-area py-3">
    @include('mobile.layouts.pesan')
    <form class="form-horizontal fprev" method="POST" action="{{ route('parameter_simpan') }}">
    @csrf

      <input type="hidden" name="id" value="{{ Crypt::encrypt($a_param->id) }}">

      <div class="card user-data-card">
        <div class="card-body">
          <div class="mb-4">
            <div class="title">LOGO</div>
            <center>
              <img src="/images/logo.png" width="100">
            </center>
            <input type="file" class="form-control" name="logo" style="margin-top: 1vh;" accept=".png">
          </div>       

          <div class="mb-2">
            <div class="title">LOGO WEB</div>
            <center>
              <img src="/images/web_logo.png" width="200">
            </center>
            <input type="file" class="form-control" name="web_logo" style="margin-top: 1vh;" accept=".png">
          </div>       

          <div class="mb-2">
            <div class="title">Nama</div>
            <input type="text" name="nama" class="form-control" value="{{ $a_param->nama }}">
          </div>

          <div class="mb-2">
            <div class="title">Nama Pendek</div>
            <input type="text" name="alias" class="form-control" value="{{ $a_param->alias }}">
          </div>

          <div class="mb-2">
            <div class="title">Alamat</div>
            <input type="text" name="alamat" class="form-control" value="{{ $a_param->alamat }}">
          </div>

          <div class="mb-2">
            <div class="title">Kecamatan</div>
            <input type="text" name="kecamatan" value="{{ $a_param->kecamatan }}" class="form-control">
          </div>

          <div class="mb-2">
            <div class="title">Kota</div>
            <input type="text" name="kota" value="{{ $a_param->kota }}" class="form-control">
          </div>

          <div class="mb-2">
            <div class="title">Telepon</div>
            <input type="text" name="telp" value="{{ $a_param->telp }}" class="form-control">
          </div>

          <div class="mb-2">
            <div class="title">Faximile</div>
            <input type="text" name="fax" value="{{ $a_param->fax }}" class="form-control">
          </div>

          <div class="mb-2">
            <div class="title">Email</div>
            <input type="email" name="email" value="{{ $a_param->email }}" class="form-control">
          </div>

          <div class="mb-2">
            <div class="title">Website</div>
            <input type="text" name="web" value="{{ $a_param->web }}" class="form-control">
          </div>

          <div class="mb-2">
            <div class="title">Direktur</div>
            <select class="form-control" name="id_direktur">
              <option value=""></option>
              @foreach($karyawan as $kary)
                <option value="{{ $kary->id }}" {{ $kary->id == $a_param->id_direktur? 'selected' : null }}>{{ $kary->nama }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-2">
            <div class="title">Status Direktur PLT</div>
            <select class="form-control" name="direktur_plt">
              <option value="0" {{ $a_param->direktur_plt == '0'? 'selected' : null }}>TIDAK</option>
              <option value="1" {{ $a_param->direktur_plt == '1'? 'selected' : null }}>YA</option>
            </select>
          </div>

          <div class="mb-2">
            <div class="title">Ketua Tim Remun</div>
            <select class="form-control" name="id_ketua_tim">
              <option value=""></option>
              @foreach($karyawan as $kary)
                <option value="{{ $kary->id }}" {{ $kary->id == $a_param->id_ketua_tim? 'selected' : null }}>{{ $kary->nama }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-2">
            <div class="title">Bendahara</div>
            <select class="form-control" name="id_bendahara">
              <option value=""></option>
              @foreach($karyawan as $kary)
                <option value="{{ $kary->id }}" {{ $kary->id == $a_param->id_bendahara? 'selected' : null }}>{{ $kary->nama }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-2">
            <div class="title">Pelaksana</div>
            <select class="form-control" name="id_pelaksana">
              <option value=""></option>
              @foreach($karyawan as $kary)
                <option value="{{ $kary->id }}" {{ $kary->id == $a_param->id_pelaksana? 'selected' : null }}>{{ $kary->nama }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-2">
            <div class="title">Facebook</div>
            <input type="text" name="facebook" value="{{ $a_param->facebook }}" class="form-control">
          </div>

          <div class="mb-2">
            <div class="title">Twitter</div>
            <input type="text" name="twitter" value="{{ $a_param->twitter }}" class="form-control">
          </div>

          <div class="mb-2">
            <div class="title">Instagram</div>
            <input type="text" name="instagram" value="{{ $a_param->instagram }}" class="form-control">
          </div>

          <div class="mb-2">              
            <div class="title">Linkedin</div>
            <input type="text" name="likedin" value="{{ $a_param->likedin }}" class="form-control">
          </div>

          <div class="mb-2">
            <div class="title">Google</div>
            <input type="text" name="google" value="{{ $a_param->google }}" class="form-control">
          </div>

          <div class="mb-2">              
            <div class="title">Youtube</div>
            <input type="text" name="youtube" value="{{ $a_param->youtube }}" class="form-control">
          </div>

          <button class="btn btn-success w-100 bprev" type="submit">SIMPAN</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
@endsection