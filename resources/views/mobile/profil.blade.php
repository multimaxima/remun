@extends('mobile.layouts.content')

@section('bawah')
  <li><a href="{{ route('jasa_remun') }}"><i class="fa fa-heart"></i>Remunerasi</a></li>
  <li><a href="{{ route('informasi_software') }}"><i class="fa fa-info"></i>Informasi</a></li>
@endsection

@section('content')
<div class="page-content-wrapper">
  <div class="container-fluid">
	 <div class="profile-wrapper-area py-3">
      <form class="form-horizontal fprev" method="POST" action="{{ route('profil_simpan') }}" files="true" enctype="multipart/form-data">
      @csrf
        <div class="card user-info-card">
          <div class="card-body p-4 d-flex align-items-center">
            <div class="user-profile me-3" style="margin-bottom: 2vh;">
              @if(Auth::user()->foto)
                <img src="/{{ Auth::user()->foto }}">
              @else
                <img src="/images/noimage.jpg">
              @endif
              <div class="change-user-thumb">
                <input class="form-control-file" type="file" name="foto">
                <button><i class="fa fa-pencil"></i></button>
              </div>
            </div>
            <div class="user-info">
              <p class="mb-0 text-dark">{{ $c_akses->akses }}</p>
              <h5 class="mb-0">
                @if(Auth::user()->gelar_depan)
                  {{ Auth::user()->gelar_depan }}
                @endif

                @if(Auth::user()->gelar_belakang)
                  {{ Auth::user()->nama }}, {{ Auth::user()->gelar_belakang }}
                @else
                  {{ Auth::user()->nama }}
                @endif            
              </h5>
              <a href="{{ route('profil_password_form') }}" class="btn btn-danger btn-sm">GANTI PASSWORD</a>
            </div>
          </div>
        </div>

        <div class="card user-data-card">
          <div class="card-body">
            <div class="mb-3">
              <div class="title mb-2">Nama</div>
              <input type="text" name="nama" class="form-control" required autofocus value="{{ Auth::user()->nama }}">
            </div>            

            <div class="mb-3">
              <div class="title mb-2">Gelar Depan</div>
              <input type="text" name="gelar_depan" class="form-control" value="{{ Auth::user()->gelar_depan }}">
            </div>

            <div class="mb-3">
              <div class="title mb-2">Gelar Belakang</div>
              <input type="text" name="gelar_belakang" class="form-control" value="{{ Auth::user()->gelar_belakang }}">
            </div>

            <div class="mb-3">
              <div class="title mb-2">NIP</div>
              <input type="text" name="nip" class="form-control" value="{{ Auth::user()->nip }}">
            </div>

            <div class="mb-3">
              <div class="title mb-2">Alamat</div>
              <input type="text" name="alamat" class="form-control" value="{{ Auth::user()->alamat }}" required>
            </div>

            <div class="mb-3">
              <div class="title mb-2">Dusun</div>
              <input type="text" name="dusun" class="form-control" value="{{ Auth::user()->dusun }}">
            </div>

            <div class="mb-3">
              <div class="title mb-2">RT</div>
              <input type="text" name="rt" class="form-control" value="{{ Auth::user()->rt }}">
            </div>

            <div class="mb-3">
              <div class="title mb-2">RW</div>
              <input type="text" name="rw" class="form-control" value="{{ Auth::user()->rw }}">
            </div>

            <div class="mb-3">
              <div class="title mb-2">Propinsi</div>
              <select class="form-control" name="no_prop" id="no_prop" required></select>
            </div>

            <div class="mb-3">
              <div class="title mb-2">Kota</div>
              <select class="form-control" name="no_kab" id="no_kab" required></select>
            </div>

            <div class="mb-3">
              <div class="title mb-2">Kecamatan</div>
              <select class="form-control" name="no_kec" id="no_kec" required></select>
            </div>

            <div class="mb-3">
              <div class="title mb-2">Desa</div>
              <select class="form-control" name="no_kel" id="no_kel" required></select>
            </div>            

            <div class="mb-3">
              <div class="title mb-2">Tempat Lahir</div>
              <input type="text" name="temp_lahir" class="form-control" value="{{ Auth::user()->temp_lahir }}">
            </div>

            <div class="mb-3">
              <div class="title mb-2">Tanggal Lahir</div>
              <input type="date" name="tgl_lahir" class="form-control" value="{{ Auth::user()->tgl_lahir }}">
            </div>

            <div class="mb-3">
              <div class="title mb-2">Jenis Kelamin</div>
              <select class="form-control" name="id_kelamin" required>
                <option value="1" {{ Auth::user()->id_kelamin == '1'? 'selected' : null }}>LAKI-LAKI</option>
                <option value="2" {{ Auth::user()->id_kelamin == '2'? 'selected' : null }}>PEREMPUAN</option>
              </select>
            </div>

            <div class="mb-3">
              <div class="title mb-2">HP</div>
              <input type="text" name="hp" class="form-control" value="{{ Auth::user()->hp }}" required>
            </div>
          
            <div class="mb-3">
              <div class="title mb-2">Telepon</div>
              <input type="text" name="telp" class="form-control" value="{{ Auth::user()->telp }}">
            </div>

            <div class="mb-3">
              <div class="title mb-2">Email</div>
              <input type="text" name="email" class="form-control" value="{{ Auth::user()->email }}">
            </div>

            <div class="mb-3">
              <div class="title mb-2">NPWP</div>
              <input type="text" name="npwp" class="form-control" value="{{ Auth::user()->npwp }}">
            </div>

            <div class="mb-3">
              <div class="title mb-2">Bank</div>
              <select class="form-control" name="bank">
                <option value=""></option>
                @foreach($bank as $bank)
                  <option value="{{ $bank->bank }}" {{ Auth::user()->bank == $bank->bank? 'selected' : null }}>{{ $bank->bank }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-3">
              <div class="title mb-2">Nomor Rekening</div>
              <input type="text" name="rekening" class="form-control" value="{{ Auth::user()->rekening }}">
            </div>

            <div class="mb-3">
              <div class="title mb-2">Username</div>
              <input type="text" name="username" class="form-control" value="{{ Auth::user()->username }}" readonly>
            </div>
          
            <button class="btn btn-success w-100 bprev" type="submit">SIMPAN</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal hide fade" id="modal_password">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Tambah Bagian</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_bagian_baru" method="POST" action="{{ route('bagian_baru') }}">
    @csrf
    </form>
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