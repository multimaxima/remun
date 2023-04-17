@extends('layouts.content')
@section('title','Parameter Rumah Sakit')

@section('content')
<div class="navbar">
  <div class="navbar-inner">
    <ul class="nav">
      <li class="active"><a href="#">Data Rumah Sakit</a></li>
      <li><a href="{{ route('parameter_software') }}">Parameter Remunerasi</a></li>
    </ul>
    <button type="submit" form="data" class="btn btn-primary pull-right">SIMPAN</button>
  </div>
</div>

<form class="form-horizontal" id="data" method="POST" action="{{ route('parameter_simpan') }}">
@csrf  
  <div class="content">
    @include('layouts.pesan')
    <div class="container-fluid">
      <div class="span2">
        <label style="text-align: center; font-weight: bold;">LOGO RUMAH SAKIT</label>
        <img src="/images/logo.png">
        <input type="file" name="logo" style="margin-top: 10px;" accept=".png">

        <label style="text-align: center; font-weight: bold; margin-top: 20px;">LOGO WEBSITE</label>
        <img src="/images/web_logo.png">
        <input type="file" name="web_logo" style="margin-top: 10px;" accept=".png">
      </div>
      <div class="span10">      
        <input type="hidden" name="id" value="{{ Crypt::encrypt($a_param->id) }}">

        <div class="control-group">
          <label class="control-label span2">Nama</label>
          <div class="controls span10">
            <input type="text" name="nama" class="form-control" value="{{ $a_param->nama }}">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label span2">Nama Pendek</label>
          <div class="controls span10">
            <input type="text" name="alias" class="form-control" value="{{ $a_param->alias }}">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label span2">Alamat</label>
          <div class="controls span10">
            <input type="text" name="alamat" class="form-control" value="{{ $a_param->alamat }}">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label span2">Propinsi</label>
          <div class="controls span4">
            <select class="form-control" name="no_prop" id="no_prop" style="width: 104.5%" required></select>
          </div>

          <label class="control-label span2">Kota</label>
          <div class="controls span4">
            <select class="form-control" name="no_kab" id="no_kab" style="width: 104.5%" required></select>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label span2">Kecamatan</label>
          <div class="controls span4">
            <select class="form-control" name="no_kec" id="no_kec" style="width: 104.5%" required></select>
          </div>

          <label class="control-label span2">Desa</label>
          <div class="controls span4">
            <select class="form-control" name="no_kel" id="no_kel" style="width: 104.5%" required></select>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label span2">Telepon</label>
          <div class="controls span4">
            <input type="text" name="telp" value="{{ $a_param->telp }}" class="form-control">
          </div>
              
          <label class="control-label span2">Faximile</label>
          <div class="controls span4">
            <input type="text" name="fax" value="{{ $a_param->fax }}" class="form-control">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label span2">Email</label>
          <div class="controls span4">
            <input type="email" name="email" value="{{ $a_param->email }}" class="form-control">
          </div>

          <label class="control-label span2">Website</label>
          <div class="controls span4">
            <input type="text" name="web" value="{{ $a_param->web }}" class="form-control">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label span2">Direktur</label>
          <div class="controls span4">
            <select class="form-control" name="id_direktur" style="width: 104.5%">
              <option value=""></option>
              @foreach($karyawan as $kary)
                <option value="{{ $kary->id }}" {{ $kary->id == $a_param->id_direktur? 'selected' : null }}>{{ $kary->nama }}</option>
              @endforeach
            </select>
          </div>

          <label class="control-label span2">Status Direktur PLT</label>
          <div class="controls span4">
            <select class="form-control" name="direktur_plt" style="width: 104.5%">
              <option value="0" {{ $a_param->direktur_plt == '0'? 'selected' : null }}>TIDAK</option>
              <option value="1" {{ $a_param->direktur_plt == '1'? 'selected' : null }}>YA</option>
            </select>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label span2">Ketua Tim Remun</label>
          <div class="controls span4">
            <select class="form-control" name="id_ketua_tim" style="width: 104.5%">
              <option value=""></option>
              @foreach($karyawan as $kary)
                <option value="{{ $kary->id }}" {{ $kary->id == $a_param->id_ketua_tim? 'selected' : null }}>{{ $kary->nama }}</option>
              @endforeach
            </select>
          </div>

          <label class="control-label span2">Bendahara</label>
          <div class="controls span4">
            <select class="form-control" name="id_bendahara" style="width: 104.5%">
              <option value=""></option>
              @foreach($karyawan as $kary)
                <option value="{{ $kary->id }}" {{ $kary->id == $a_param->id_bendahara? 'selected' : null }}>{{ $kary->nama }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label span2">Pelaksana</label>
          <div class="controls span4">
            <select class="form-control" name="id_pelaksana" style="width: 104.5%">
              <option value=""></option>
              @foreach($karyawan as $kary)
                <option value="{{ $kary->id }}" {{ $kary->id == $a_param->id_pelaksana? 'selected' : null }}>{{ $kary->nama }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="control-group" style="margin-top: 20px;">
          <label class="control-label span2">Facebook</label>
          <div class="controls span4">
            <input type="text" name="facebook" value="{{ $a_param->facebook }}" class="form-control">
          </div>

          <label class="control-label span2">Twitter</label>
          <div class="controls span4">
            <input type="text" name="twitter" value="{{ $a_param->twitter }}" class="form-control">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label span2">Instagram</label>
          <div class="controls span4">
            <input type="text" name="instagram" value="{{ $a_param->instagram }}" class="form-control">
          </div>
              
          <label class="control-label span2">Linkedin</label>
          <div class="controls span4">
            <input type="text" name="likedin" value="{{ $a_param->likedin }}" class="form-control">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label span2">Google</label>
          <div class="controls span4">
            <input type="text" name="google" value="{{ $a_param->google }}" class="form-control">
          </div>
              
          <label class="control-label span2">Youtube</label>
          <div class="controls span4">
            <input type="text" name="youtube" value="{{ $a_param->youtube }}" class="form-control">
          </div>
        </div>
      
    </div>
  </div>
</div>
</form>

<input type="hidden" name="no_prop_asal" id="no_prop_asal" value="{{ $a_param->no_prop }}">
<input type="hidden" name="no_kab_asal" id="no_kab_asal" value="{{ $a_param->no_kab }}">
<input type="hidden" name="no_kec_asal" id="no_kec_asal" value="{{ $a_param->no_kec }}">
<input type="hidden" name="no_kel_asal" id="no_kel_asal" value="{{ $a_param->no_kel }}">
@endsection

@section('script')
  <script type="text/javascript">
    window.onload=function() {
      $no_prop  = document.getElementById('no_prop_asal').value;
      $no_kab   = document.getElementById('no_kab_asal').value;
      $no_kec   = document.getElementById('no_kec_asal').value;
      $no_kel   = document.getElementById('no_kel_asal').value;

      if($no_prop){
        $.ajax({
          type : 'get',
          url : '{{ route("pilih_propinsi_edit") }}',
          data: {'no_prop':$no_prop},
          success: function(data){
            $('#no_prop').html(data);
          }
        });
      } else {
        $.ajax({
          type : 'get',
          url : '{{ route("pilih_propinsi") }}',
          success: function(data){
            $('#no_prop').html(data);
          }
        });
      }

      if($no_kab){
        $.ajax({
          type : 'get',
          url : '{{ route("pilih_kota_edit") }}',
          data: {'no_kab': $no_kab, 'no_prop':$no_prop},
          success: function(data){
            $('#no_kab').html(data);
          }
        });
      } else {
        $.ajax({
          type : 'get',
          url : '{{ route("pilih_kota") }}',
          data: {'no_kab': $no_kab, 'no_prop':$no_prop},
          success: function(data){
            $('#no_kab').html(data);
          }
        });
      }

      if($no_kec){
        $.ajax({
          type : 'get',
          url : '{{ route("pilih_kecamatan_edit") }}',
          data: {'no_kec': $no_kec, 'no_kab': $no_kab, 'no_prop':$no_prop},
          success: function(data){
            $('#no_kec').html(data);
          }
        });
      } else {
        $.ajax({
          type : 'get',
          url : '{{ route("pilih_kecamatan") }}',
          data: {'no_kec': $no_kec, 'no_kab': $no_kab, 'no_prop':$no_prop},
          success: function(data){
            $('#no_kec').html(data);
          }
        });
      }

      if($no_kel){
        $.ajax({
          type : 'get',
          url : '{{ route("pilih_desa_edit") }}',
          data: {'no_kel': $no_kel, 'no_kec': $no_kec, 'no_kab': $no_kab, 'no_prop':$no_prop},
          success: function(data){
            $('#no_kel').html(data);
          }
        });
      } else {
        $.ajax({
          type : 'get',
          url : '{{ route("pilih_desa") }}',
          data: {'no_kel': $no_kel, 'no_kec': $no_kec, 'no_kab': $no_kab, 'no_prop':$no_prop},
          success: function(data){
            $('#no_kel').html(data);
          }
        });
      }
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