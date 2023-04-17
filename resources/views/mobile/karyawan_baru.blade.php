@extends('mobile.layouts.content')

@section('bawah')
  <li><a href="#" onclick="document.getElementById('kembali').submit();"><i class="fa fa-angle-left"></i>Kembali</a></li>
  <li><a href="#" id="simpan"><i class="fa fa-save"></i>Simpan</a></li>

  <form hidden method="GET" action="{{ route('karyawan') }}" id="kembali">
  @csrf
  </form>
@endsection

@section('content')
@include('mobile.layouts.pesan')

<div class="row isi" style="padding-bottom: 10vh; margin-top: 8vh;">
  <div class="container">
    <div class="card" style="margin-top: 1vh;">
      <div class="card-body" style="font-size: 3vw;">  
        <form class="form-horizontal fprev user-data-card" id="karyawan_baru" method="POST" action="{{ route('karyawan_baru_simpan') }}" files="true" enctype="multipart/form-data">
        @csrf

          <center>
            <img src="images/noimage.jpg" width="40%">
            <input type="file" name="foto" style="margin-top: 10px;" accept=".jpg" value="{{ old('foto') }}">
          </center>

          <div class="mb-1">
            <div class="title mb-1">Nama</div>
            <input type="text" id="nama" name="nama" class="form-control" required autofocus value="{{ old('nama') }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">NIP</div>
            <input type="text" id="nip" name="nip" class="form-control" value="{{ old('nip') }}">
          </div>

          <div class="mb-1 row">
            <div class="col-6">
              <div class="title mb-1">Gelar Depan</div>
              <input type="text" id="gelar_depan" name="gelar_depan" class="form-control" value="{{ old('gelar_depan') }}">
            </div>

            <div class="col-6">
              <div class="title mb-1">Gelar Belakang</div>
              <input type="text" id="gelar_belakang" name="gelar_belakang" class="form-control" value="{{ old('gelar_belakang') }}">
            </div>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Alamat</div>
            <input type="text" id="alamat" name="alamat" class="form-control" value="{{ old('alamat') }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Dusun</div>
            <input type="text" id="dusun" name="dusun" class="form-control" value="{{ old('dusun') }}">
          </div>

          <div class="mb-1 row">
            <div class="col-6">
              <div class="title mb-1">RT</div>
              <input type="text" id="rt" name="rt" class="form-control" value="{{ old('rt') }}">
            </div>

            <div class="col-6">
              <div class="title mb-1">RW</div>
              <input type="text" id="rw" name="rw" class="form-control" value="{{ old('rw') }}">
            </div>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Propinsi</div>
            <select class="form-control" name="no_prop" id="no_prop"></select>
          </div>
              
          <div class="mb-1">
            <div class="title mb-1">Kota</div>
            <select class="form-control" name="no_kab" id="no_kab"></select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Kecamatan</div>
            <select class="form-control" name="no_kec" id="no_kec"></select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Desa</div>
            <select class="form-control" name="no_kel" id="no_kel"></select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Tempat Lahir</div>
            <input type="text" id="temp_lahir" name="temp_lahir" class="form-control" value="{{ old('temp_lahir') }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Tanggal Lahir</div>
            <input type="date" id="tgl_lahir" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir') }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Jenis Kelamin</div>
            <select class="form-control" id="id_kelamin" name="id_kelamin" required>
              <option value="1" {{ old('id_kelamin') == 1? 'selected' : null }}>LAKI-LAKI</option>
              <option value="2" {{ old('id_kelamin') == 2? 'selected' : null }}>PEREMPUAN</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Email</div>
            <input type="text" id="email" name="email" class="form-control" value="{{ old('email') }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Telepon</div>
            <input type="text" id="telp" name="telp" class="form-control" value="{{ old('telp') }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">HP</div>
            <input type="text" id="hp" name="hp" class="form-control" value="{{ old('hp') }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Mulai Kerja</div>
            <input type="date" id="mulai_kerja" name="mulai_kerja" class="form-control" value="{{ old('mulai_kerja') }}" required>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Status</div>
            <select class="form-control" id="id_status" name="id_status" required>
              <option value=""></option>
              @foreach($status as $status)
                <option value="{{ $status->id }}" {{ old('id_status') == $status->id? 'selected' : null }}>{{ strtoupper($status->status) }}</option>
              @endforeach                   
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Bagian</div>
            <select class="form-control" id="id_tenaga_bagian" name="id_tenaga_bagian">
              <option value=""></option>
              @foreach($bagian as $bagian)
                <option value="{{ $bagian->id }}" {{ old('id_tenaga_bagian') == $bagian->id? 'selected' : null }}>{{ strtoupper($bagian->bagian) }}</option>
              @endforeach                   
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">R. Kerja Utama</div>
            <select class="form-control" id="id_ruang" name="id_ruang">
              <option value=""></option>
              @foreach($ruang as $rng)
                <option value="{{ $rng->id }}" {{ old('id_ruang') == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
              @endforeach                   
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">R. Kerja Tambahan</div>
            <select class="form-control" id="id_ruang_1" name="id_ruang_1">
              <option value=""></option>
              @foreach($ruang as $rng)
                <option value="{{ $rng->id }}" {{ old('id_ruang_1') == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
              @endforeach                   
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Pendidikan</div>
            <input type="text" id="pendidikan" name="pendidikan" class="form-control" value="{{ old('pendidikan') }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Tempat Tugas</div>
            <input type="text" id="temp_tugas" name="temp_tugas" class="form-control" value="{{ old('temp_tugas') }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Jabatan</div>
            <input type="text" id="jabatan" name="jabatan" class="form-control" value="{{ old('jabatan') }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Golongan</div>
            <input type="text" id="golongan" name="golongan" class="form-control" value="{{ old('golongan') }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">NPWP</div>
            <input type="text" id="npwp" name="npwp" class="form-control" value="{{ old('npwp') }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Bank</div>
            <select class="form-control" id="bank" name="bank">
              <option value=""></option>
              @foreach($bank as $bank)
                <option value="{{ $bank->bank }}" {{ old('bank') == $bank->bank? 'selected' : null }}>{{ $bank->bank }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Nomor Rekening</div>
            <input type="text" id="rekening" name="rekening" class="form-control" value="{{ old('rekening') }}">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Username</div>
            <input type="text" name="username" class="form-control" style="text-transform: lowercase;" required>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Hak Akses</div>
            <select class="form-control" name="id_akses">
              <option value=""></option>
              @foreach($akses as $akses)
                <option value="{{ $akses->id }}" {{ old('id_akses') == $akses->id? 'selected' : null }}>{{ strtoupper($akses->akses) }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Gaji Pokok</div>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">Rp.</span>
              <input type="text" class="form-control nominal" style="text-align: right;" id="gapok" name="gapok" required value="{{ old('gapok') }}">
            </div>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Koreksi</div>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">Rp.</span>
              <input type="text" class="form-control nominal" style="text-align: right;" id="koreksi" name="koreksi" required value="{{ old('koreksi') }}">
            </div>
          </div>

          <div class="mb-1">
            <div class="title mb-1">TPP</div>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">Rp.</span>
              <input type="text" class="form-control nominal" style="text-align: right;" id="tpp" name="tpp" required value="{{ old('tpp') }}">
            </div>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Pajak</div>
            <div class="input-group mb-3">
              <input type="text" class="form-control nominal" id="pajak" name="pajak" required value="{{ old('pajak') }}">
              <span class="input-group-text" id="basic-addon1">%</span>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    window.onload=function() {
      $.ajax({
        type : 'get',
        url : '{{ route("pilih_propinsi") }}',
        success: function(data){
          $('#no_prop').html(data);
        }
      });      
    };

    $('#no_prop').on('change',function(){
      $no_prop   = $(this).val();

      $.ajax({
        type : 'get',
        url : '{{ route("pilih_kota") }}',
        data: {'no_prop':$no_prop},
        success: function(data){
          $('#no_kab').html(data);
          $('#no_kec').html('');
          $('#no_kel').html('');
        }
      });
    });

    $('#no_kab').on('change',function(){
      $no_kab   = $(this).val();
      $no_prop  = document.getElementById('no_prop').value;

      $.ajax({
        type : 'get',
        url : '{{ route("pilih_kecamatan") }}',
        data: {'no_kab': $no_kab, 'no_prop': $no_prop},
        success: function(data){
          $('#no_kec').html(data);
          $('#no_kel').html('');
        }
      });
    });

    $('#no_kec').on('change',function(){
      $no_kec   = $(this).val();
      $no_kab   = document.getElementById('no_kab').value;
      $no_prop  = document.getElementById('no_prop').value;

      $.ajax({
        type : 'get',
        url : '{{ route("pilih_desa") }}',
        data: {'no_kec': $no_kec, 'no_kab': $no_kab, 'no_prop': $no_prop},
        success: function(data){
          $('#no_kel').html(data);
        }
      });
    });
  </script>

  <script>
    $(document).ready(function () {
        $('#simpan').on('click',function(e){
          e.preventDefault();
          $('#karyawan_baru').submit();
        });
    });
  </script>
@endsection