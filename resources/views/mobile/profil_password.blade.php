@extends('mobile.layouts.content')

@section('bawah')
  <li><a href="{{ route('jasa_remun') }}"><i class="fa fa-heart"></i>Remunerasi</a></li>
  <li><a href="{{ route('informasi_software') }}"><i class="fa fa-info"></i>Informasi</a></li>
@endsection

@section('content')
<div class="page-content-wrapper">
  <div class="container-fluid">
   <div class="profile-wrapper-area py-3">
    <form class="form-horizontal fprev" id="data" method="POST" action="{{ route('profil_password') }}">
    @csrf

    <div class="card user-data-card">
      <div class="card-body">
        <div class="mb-3">
          <div class="title mb-2">Password Saat Ini</div>
          <input type="password" name="current_password" class="form-control" required autofocus placeholder="Minimal 8 karakter" minlength="8">
        </div>

        <div class="mb-3">
          <div class="title mb-2">Password Baru</div>
          <input type="password" name="new_password" class="form-control" required placeholder="Minimal 8 karakter" minlength="8">
        </div>

        <div class="mb-3">
          <div class="title mb-2">Konfirmasi Password Baru</div>
          <input type="password" name="new_password_confirm" class="form-control" required placeholder="Minimal 8 karakter" minlength="8">
        </div>   

        <div class="btn-group w-100">
          <button class="btn btn-success bprev" type="submit">SIMPAN</button>            
          <a href="{{ route('profil') }}" class="btn btn-danger">KEMBALI</a>
        </div>
      </div>
    </div>
  </form>
</div>
</div>
</div>

<input type="hidden" name="no_prop_asal" id="no_prop_asal" value="{{ Auth::user()->no_prop }}">
<input type="hidden" name="no_kab_asal" id="no_kab_asal" value="{{ Auth::user()->no_kab }}">
<input type="hidden" name="no_kec_asal" id="no_kec_asal" value="{{ Auth::user()->no_kec }}">
<input type="hidden" name="no_kel_asal" id="no_kel_asal" value="{{ Auth::user()->no_kel }}">
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