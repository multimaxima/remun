@extends('mobile.layouts.content')

@section('bawah')
  <li><a href="{{ route('jasa_remun') }}"><i class="fa fa-heart"></i>Remunerasi</a></li>
  <li><a href="{{ route('informasi_software') }}"><i class="fa fa-info"></i>Informasi</a></li>
@endsection

@section('content')
<div class="row isi">
  <div class="container" style="margin-top: 9vh;">
    <div class="btn-group float-end">
      <button type="submit" form="kembali" class="btn btn-primary btn-sm" title="Kembali">KEMBALI</button>
      <button type="submit" form="karyawan_baru" class="btn btn-primary btn-sm">SIMPAN</button>
    </div>

    <form hidden method="GET" action="{{ route('karyawan') }}" id="kembali">
    @csrf
    </form>
  </div>
</div>

@include('mobile.layouts.pesan')

<div class="row isi" style="padding-bottom: 10vh;">
  <div class="container">
    <div class="card" style="margin-top: 1vh;">
      <div class="card-body" style="font-size: 3vw;">  
        <form class="form-horizontal fprev user-data-card" id="karyawan_baru" method="POST" action="{{ route('karyawan_edit_simpan') }}" files="true" enctype="multipart/form-data">
        @csrf
          <input type="hidden" name="id" value="{{ Crypt::encrypt($karyawan->id) }}">

          @if($karyawan->foto)
            <img src="/{{ $karyawan->foto }}" width="100%">
          @else
            <img src="/images/noimage.jpg" width="100%">
          @endif          
          <input type="file" name="foto" style="margin-top: 10px;" accept=".jpg" value="{{ $karyawan->foto }}">

          <div class="mb-1">
            <div class="title mb-1">Nama</div>
            <input type="text" id="nama" name="nama" class="form-control" required autofocus value="{{ $karyawan->nama }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">NIP</div>
            <input type="text" id="nip" name="nip" class="form-control" value="{{ $karyawan->nip }}">
          </div>

          <div class="mb-1 row">
            <div class="col-6">
              <div class="title mb-1">Gelar Depan</div>
              <input type="text" id="gelar_depan" name="gelar_depan" class="form-control" value="{{ $karyawan->gelar_depan }}">
            </div>

            <div class="col-6">
              <div class="title mb-1">Gelar Belakang</div>
              <input type="text" id="gelar_belakang" name="gelar_belakang" class="form-control" value="{{ $karyawan->gelar_belakang }}">
            </div>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Alamat</div>
            <input type="text" id="alamat" name="alamat" class="form-control" value="{{ $karyawan->alamat }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Dusun</div>
            <input type="text" id="dusun" name="dusun" class="form-control" value="{{ $karyawan->dusun }}">
          </div>
              
          <div class="mb-1 row">
            <div class="col-6">
              <div class="title mb-1">RT</div>
              <input type="text" id="rt" name="rt" class="form-control" value="{{ $karyawan->rt }}">
            </div>

            <div class="col-6">
              <div class="title mb-1">RW</div>
              <input type="text" id="rw" name="rw" class="form-control" value="{{ $karyawan->rw }}">
            </div>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Kelurahan</div>
            <input type="text" id="kelurahan" name="kelurahan" class="form-control" value="{{ $karyawan->kelurahan }}">
          </div>
              
          <div class="mb-1">
            <div class="title mb-1">Kecamatan</div>
            <input type="text" id="kecamatan" name="kecamatan" class="form-control" value="{{ $karyawan->kecamatan }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Kota</div>
            <input type="text" id="kota" name="kota" class="form-control" value="{{ $karyawan->kota }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Tempat Lahir</div>
            <input type="text" id="temp_lahir" name="temp_lahir" class="form-control" value="{{ $karyawan->temp_lahir }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Tanggal Lahir</div>
            <input type="date" id="tgl_lahir" name="tgl_lahir" class="form-control" value="{{ $karyawan->tgl_lahir }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Jenis Kelamin</div>
            <select class="form-control" id="id_kelamin" name="id_kelamin">
              <option value="1" {{ $karyawan->id_kelamin == 1? 'selected' : null }}>LAKI-LAKI</option>
              <option value="2" {{ $karyawan->id_kelamin == 2? 'selected' : null }}>PEREMPUAN</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Email</div>
            <input type="text" id="email" name="email" class="form-control" value="{{ $karyawan->email }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Telepon</div>
            <input type="text" id="telp" name="telp" class="form-control" value="{{ $karyawan->telp }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">HP</div>
            <input type="text" id="hp" name="hp" class="form-control" value="{{ $karyawan->hp }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Mulai Kerja</div>
            <input type="date" id="mulai_kerja" name="mulai_kerja" class="form-control" value="{{ $karyawan->mulai_kerja }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Status</div>
            <select class="form-control" id="id_status" name="id_status" required>
              <option value=""></option>
              @foreach($status as $status)
                <option value="{{ $status->id }}" {{ $karyawan->id_status == $status->id? 'selected' : null }}>{{ strtoupper($status->status) }}</option>
              @endforeach                   
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Bagian</div>
            <select class="form-control" id="id_tenaga_bagian" name="id_tenaga_bagian">
              <option value=""></option>
              @foreach($bagian as $bagian)
                <option value="{{ $bagian->id }}" {{ $karyawan->id_tenaga_bagian == $bagian->id? 'selected' : null }}>{{ strtoupper($bagian->bagian) }}</option>
              @endforeach                   
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">R. Kerja Utama</div>
            <select class="form-control" id="id_ruang" name="id_ruang">
              <option value=""></option>
              @foreach($ruang as $rng)
                <option value="{{ $rng->id }}" {{ $karyawan->id_ruang == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
              @endforeach                   
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">R. Kerja Tambahan</div>
            <select class="form-control" id="id_ruang_1" name="id_ruang_1">
              <option value=""></option>
              @foreach($ruang as $rng)
                <option value="{{ $rng->id }}" {{ $karyawan->id_ruang_1 == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
              @endforeach                   
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Pendidikan</div>
            <input type="text" id="pendidikan" name="pendidikan" class="form-control" value="{{ $karyawan->pendidikan }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Tempat Tugas</div>
            <input type="text" id="temp_tugas" name="temp_tugas" class="form-control" value="{{ $karyawan->temp_tugas }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Jabatan</div>
            <input type="text" id="jabatan" name="jabatan" class="form-control" value="{{ $karyawan->jabatan }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Golongan</div>
            <input type="text" id="golongan" name="golongan" class="form-control" value="{{ $karyawan->golongan }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">NPWP</div>
            <input type="text" id="npwp" name="npwp" class="form-control" value="{{ $karyawan->npwp }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Bank</div>
            <select class="form-control" id="bank" name="bank">
              <option value=""></option>
              @foreach($bank as $bank)
                <option value="{{ $bank->bank }}" {{ $karyawan->bank == $bank->bank? 'selected' : null }}>{{ $bank->bank }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Nomor Rekening</div>
            <input type="text" id="rekening" name="rekening" class="form-control" value="{{ $karyawan->rekening }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Username</div>
            <input type="text" class="form-control" style="text-transform: lowercase;" readonly value="{{ $karyawan->username }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Hak Akses</div>
            <select class="form-control" name="id_akses">
              <option value=""></option>
              @foreach($akses as $akses)
                <option value="{{ $akses->id }}" {{ $karyawan->id_akses == $akses->id? 'selected' : null }}>{{ strtoupper($akses->akses) }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Gaji Pokok</div>
            <div class="col-6">
              <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Rp.</span>
                <input type="text" class="form-control nominal" style="text-align: right;" id="gapok" name="gapok" required value="{{ $karyawan->gapok }}">
              </div>
            </div>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Koreksi</div>
            <div class="col-6">
              <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Rp.</span>
                <input type="text" class="form-control nominal" style="text-align: right;" id="koreksi" name="koreksi" required value="{{ $karyawan->koreksi }}">
              </div>
            </div>
          </div>

          <div class="mb-1">
            <div class="title mb-1">TPP</div>
            <div class="col-6">
              <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Rp.</span>
                <input type="text" class="form-control nominal" style="text-align: right;" id="tpp" name="tpp" required value="{{ $karyawan->tpp }}">
              </div>
            </div>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Pajak</div>
            <div class="col-6">
              <div class="input-group mb-3">
                <input type="text" class="form-control nominal" id="pajak" name="pajak" required value="{{ $karyawan->pajak }}">
                <span class="input-group-text" id="basic-addon1">%</span>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection