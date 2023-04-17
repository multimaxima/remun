@extends('layouts.content')
@section('title','Karyawan Baru')

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12">
        <div class="btn-group">
          <button type="submit" form="kembali" class="btn btn-primary" title="Kembali">KEMBALI</button>
          <button type="submit" form="karyawan_baru" class="btn btn-primary bprev">SIMPAN</button>

          <form hidden method="GET" action="{{ route('karyawan') }}" id="kembali">
          @csrf
            <input type="text" name="cari" value="{{ $cari }}">
            <input type="text" name="tampil" value="{{ $tampil }}">
            <input type="text" name="id_ruang" value="{{ $id_ruang }}">
            <input type="text" name="id_bagian" value="{{ $id_bagian }}">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <form class="form-horizontal fprev" id="karyawan_baru" method="POST" action="{{ route('karyawan_baru_simpan') }}" files="true" enctype="multipart/form-data">
  @csrf

  <div class="container-fluid">
    <div class="span2">
      <img src="images/noimage.jpg" width="100%">
      <input type="file" name="foto" style="margin-top: 10px;" accept=".jpg" value="{{ old('foto') }}">
    </div>

    <div class="span10">          
      <div class="control-group">
        <label for="nama" class="control-label span2">Nama</label>
        <div class="controls span4">
          <input type="text" id="nama" name="nama" class="form-control" required autofocus value="{{ old('nama') }}">
        </div>

        <label for="nip" class="control-label span2">NIP</label>
        <div class="controls span4">
          <input type="text" id="nip" name="nip" class="form-control" value="{{ old('nip') }}">
        </div>
      </div>

      <div class="control-group">
        <label for="gelar_depan" class="control-label span2">Gelar Depan</label>
        <div class="controls span4">
          <input type="text" id="gelar_depan" name="gelar_depan" class="form-control" value="{{ old('gelar_depan') }}">
        </div>

        <label for="gelar_belakang" class="control-label span2">Gelar Belakang</label>
        <div class="controls span4">
          <input type="text" id="gelar_belakang" name="gelar_belakang" class="form-control" value="{{ old('gelar_belakang') }}">
        </div>
      </div>

      <div class="control-group">
        <label for="alamat" class="control-label span2">Alamat</label>
        <div class="controls span10">
          <input type="text" id="alamat" name="alamat" class="form-control" value="{{ old('alamat') }}">
        </div>
      </div>

      <div class="control-group">
        <label for="dusun" class="control-label span2">Dusun</label>
        <div class="controls span4">
          <input type="text" id="dusun" name="dusun" class="form-control" value="{{ old('dusun') }}">
        </div>
              
        <label for="rt" class="control-label span2">RT</label>
        <div class="controls span1">
          <input type="text" id="rt" name="rt" class="form-control" value="{{ old('rt') }}">
        </div>

        <label for="rw" class="control-label span1">RW</label>
        <div class="controls span1">
          <input type="text" id="rw" name="rw" class="form-control" value="{{ old('rw') }}">
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span2">Propinsi</label>
        <div class="controls span4">
          <select class="form-control" name="no_prop" id="no_prop" style="width: 104.5%"></select>
        </div>

        <label class="control-label span2">Kota</label>
        <div class="controls span4">
          <select class="form-control" name="no_kab" id="no_kab" style="width: 104.5%"></select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span2">Kecamatan</label>
        <div class="controls span4">
          <select class="form-control" name="no_kec" id="no_kec" style="width: 104.5%"></select>
        </div>

        <label class="control-label span2">Desa</label>
        <div class="controls span4">
          <select class="form-control" name="no_kel" id="no_kel" style="width: 104.5%"></select>
        </div>
      </div>

      <div class="control-group">
        <label for="temp_lahir" class="control-label span2">Tempat Lahir</label>
        <div class="controls span4">
          <input type="text" id="temp_lahir" name="temp_lahir" class="form-control" value="{{ old('temp_lahir') }}">
        </div>

        <label for="tgl_lahir" class="control-label span2">Tanggal Lahir</label>
        <div class="controls span4">
          <input type="date" id="tgl_lahir" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir') }}">
        </div>
      </div>

      <div class="control-group">
        <label for="id_kelamin" class="control-label span2">Jenis Kelamin</label>
        <div class="controls span4">
          <select class="form-control" id="id_kelamin" name="id_kelamin" style="width: 105%;" required>
            <option value="1" {{ old('id_kelamin') == 1? 'selected' : null }}>LAKI-LAKI</option>
            <option value="2" {{ old('id_kelamin') == 2? 'selected' : null }}>PEREMPUAN</option>
          </select>
        </div>

        <label for="email" class="control-label span2">Email</label>
        <div class="controls span4">
          <input type="text" id="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>                
      </div>

      <div class="control-group">
        <label for="telp" class="control-label span2">Telepon</label>
        <div class="controls span4">
          <input type="text" id="telp" name="telp" class="form-control" value="{{ old('telp') }}">
        </div>

        <label for="hp" class="control-label span2">HP</label>
        <div class="controls span4">
          <input type="text" id="hp" name="hp" class="form-control" value="{{ old('hp') }}">
        </div>
      </div>

      <div class="control-group">
        <label for="mulai_kerja" class="control-label span2">Mulai Kerja</label>
        <div class="controls span4">
          <input type="date" id="mulai_kerja" name="mulai_kerja" class="form-control" value="{{ old('mulai_kerja') }}" required>
        </div>

        <label for="id_status" class="control-label span2">Status</label>
        <div class="controls span4">
          <select class="form-control" id="id_status" name="id_status" required style="width: 105%;">
            <option value=""></option>
            @foreach($status as $status)
              <option value="{{ $status->id }}" {{ old('id_status') == $status->id? 'selected' : null }}>{{ strtoupper($status->status) }}</option>
            @endforeach                   
          </select>
        </div>                
      </div>

      <div class="control-group">                
        <label for="id_tenaga_bagian" class="control-label span2">Bagian</label>
        <div class="controls span4">
          <select class="form-control" id="id_tenaga_bagian" name="id_tenaga_bagian" style="width: 105%;" required>
            <option value=""></option>
            @foreach($bagian as $bagian)
              <option value="{{ $bagian->id }}" {{ old('id_tenaga_bagian') == $bagian->id? 'selected' : null }}>{{ strtoupper($bagian->bagian) }}</option>
            @endforeach                   
          </select>
        </div>
      </div>

      <div style="background-color: #e8e8e8; padding: 10px 0;">
      <div class="control-group">                
        <label for="id_ruang" class="control-label span2">R. Kerja Utama</label>
        <div class="controls span4">
          <select class="form-control" id="id_ruang" name="id_ruang" style="width: 105%;" required>
            <option value=""></option>
            @foreach($ruang as $rng)
              <option value="{{ $rng->id }}" {{ old('id_ruang') == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
            @endforeach                   
          </select>
        </div>
      </div>

      <div class="control-group">                
        <label for="id_ruang_1" class="control-label span2">Tambahan 1</label>
        <div class="controls span4">
          <select class="form-control" id="id_ruang_1" name="id_ruang_1" style="width: 105%;">
            <option value=""></option>
            @foreach($ruang as $rng)
              <option value="{{ $rng->id }}" {{ old('id_ruang_1') == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
            @endforeach                   
          </select>
        </div>
      </div>  

      <div class="control-group">                
        <label for="id_ruang_2" class="control-label span2">Tambahan 2</label>
        <div class="controls span4">
          <select class="form-control" id="id_ruang_2" name="id_ruang_2" style="width: 105%;">
            <option value=""></option>
            @foreach($ruang as $rng)
              <option value="{{ $rng->id }}" {{ old('id_ruang_2') == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
            @endforeach                   
          </select>
        </div>
      </div>  
      </div>            

      <div class="control-group" style="margin-top: 10px;">
        <label for="pendidikan" class="control-label span2">Pendidikan</label>
        <div class="controls span4">
          <input type="text" id="pendidikan" name="pendidikan" class="form-control" value="{{ old('pendidikan') }}">
        </div>

        <label for="temp_tugas" class="control-label span2">Tempat Tugas</label>
        <div class="controls span4">
          <input type="text" id="temp_tugas" name="temp_tugas" class="form-control" value="{{ old('temp_tugas') }}">
        </div>
      </div>

      <div class="control-group">
        <label for="jabatan" class="control-label span2">Jabatan</label>
        <div class="controls span4">
          <input type="text" id="jabatan" name="jabatan" class="form-control" value="{{ old('jabatan') }}">
        </div>

        <label for="golongan" class="control-label span2">Golongan</label>
        <div class="controls span4">
          <input type="text" id="golongan" name="golongan" class="form-control" value="{{ old('golongan') }}">
        </div>
      </div>

      <div class="control-group">
        <label for="npwp" class="control-label span2">NPWP</label>
        <div class="controls span4">
          <input type="text" id="npwp" name="npwp" class="form-control" value="{{ old('npwp') }}">
        </div>
      </div>

      <div class="control-group">
        <label for="bank" class="control-label span2">Bank</label>
        <div class="controls span4">
          <select class="form-control" id="bank" name="bank" style="width: 105%;">
            <option value=""></option>
            @foreach($bank as $bank)
              <option value="{{ $bank->bank }}" {{ old('bank') == $bank->bank? 'selected' : null }}>{{ $bank->bank }}</option>
            @endforeach
          </select>
        </div>

        <label for="rekening" class="control-label span2">Nomor Rekening</label>
        <div class="controls span4">
          <input type="text" id="rekening" name="rekening" class="form-control" value="{{ old('rekening') }}">
        </div>
      </div>

      <div class="control-group">
        <label for="bank" class="control-label span2">Username</label>
        <div class="controls span4">
          <input type="text" name="username" class="form-control" style="text-transform: lowercase;" required>
        </div>

        <label for="id_akses" class="control-label span2">Hak Akses</label>
        <div class="controls span4">
          <select class="form-control" name="id_akses" style="width: 105%;" required>
            <option value=""></option>
            @foreach($akses as $akses)
              <option value="{{ $akses->id }}" {{ old('id_akses') == $akses->id? 'selected' : null }}>{{ strtoupper($akses->akses) }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label for="gapok" class="control-label span2">Gaji Pokok</label>
        <div class="controls span2">
          <div class="input-prepend">
            <span class="add-on">Rp.</span>
            <input type="text" class="form-control nominal" style="text-align: right;" id="gapok" name="gapok" required value="{{ old('gapok') }}">
          </div>
        </div>

        <label for="koreksi" class="control-label span4">Koreksi</label>
        <div class="controls span2">
          <div class="input-prepend">
            <span class="add-on">Rp.</span>
            <input type="text" class="form-control nominal" style="text-align: right;" id="koreksi" name="koreksi" required value="{{ old('koreksi') }}">
          </div>
        </div>
      </div>

      <div class="control-group">
        <label for="tpp" class="control-label span2">TPP</label>
        <div class="controls span2">
          <div class="input-prepend">
            <span class="add-on">Rp.</span>
            <input type="text" class="form-control nominal" style="text-align: right;" id="tpp" name="tpp" required value="{{ old('tpp') }}">
          </div>
        </div>
      
        <label for="pajak" class="control-label span4">Pajak</label>
        <div class="controls span1">
          <div class="input-append">            
            <input type="text" class="form-control nominal" id="pajak" name="pajak" required value="{{ old('pajak') }}">
            <span class="add-on">%</span>
          </div>
        </div>
      </div>      
    </div>
  </div>
  </form>
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
@endsection