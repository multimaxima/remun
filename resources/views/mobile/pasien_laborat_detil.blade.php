@extends('mobile.layouts.content')

@section('bawah')
  @if($c_ruang->terima_pasien == 1)                
  <li>
    <a href="#" data-bs-toggle="modal" data-bs-target="#pasien_luar">
      <i class="fa fa-users"></i> Pasien Luar
    </a>
  </li>
  @else
  <li><a href="{{ route('jasa_remun') }}"><i class="fa fa-heart"></i>Remunerasi</a></li>
  @endif
  <li>
    <a href="#" data-bs-toggle="modal" data-bs-target="#tambah_layanan">
      <i class="fa fa-bookmark"></i> Layanan
    </a>
  </li>
@endsection

@section('content')
<div class="row isi">
  <div class="container" style="margin-top: 9vh;">
    <div class="card">
      <label class="card-header" style="font-size: 3vw; font-weight: bold;">{{ strtoupper($pass->nama) }}</label>
      <div class="card-body">
        <table width="100%" style="font-size: 3vw; line-height: 2vh;">
          <tr>
            <td width="15%" valign="top">Alamat</td>
            <td width="3%" valign="top">:</td>
            <td valign="top">{{ strtoupper($pass->alamat) }}</td>
          </tr>            
          <tr>
            <td valign="top">Register</td>
            <td valign="top">:</td>
            <td valign="top">{{ strtoupper($pass->register) }}</td>
          </tr>
          <tr>
            <td valign="top">Umur</td>
            <td valign="top">:</td>
            <td valign="top">{{ strtoupper($pass->umur_thn) }} Thn.
              @if($pass->umur_bln)
                {{ strtoupper($pass->umur_bln) }} Bln.
              @endif
            </td>
          </tr>            
          <tr>
            <td valign="top">No. MR</td>
            <td valign="top">:</td>
            <td valign="top">{{ strtoupper($pass->no_mr) }}</td>
          </tr>
          <tr>
            <td valign="top">Jenis</td>
            <td valign="top">:</td>
            <td valign="top">PASIEN {{ strtoupper($pass->jenis_pasien) }}</td>
          </tr>            
          <tr>
            <td valign="top">DPJP</td>
            <td valign="top">:</td>
            <td valign="top">{{ $pass->dpjp }}</td>
          </tr>
          <tr>
            <td valign="top">Ruang</td>
            <td valign="top">:</td>
            <td valign="top">{{ strtoupper($pass->ruang) }}</td>
          </tr>                     
        </table>
      </div>
    </div>
  </div>
</div>

<div class="row isi" style="margin-top: 1vh;">
  <div class="container user-data-card">
    <a href="{{ route('pasien_apotik') }}" class="btn btn-danger" style="width: 15vw; padding: 1vh 0;">
      <i class="fa fa-angle-left" style="font-size: 5vw;"></i><br>
      <span style="font-size: 2vw;">KEMBALI</span>
    </a>
    <button type="button" class="btn btn-secondary" title="Tambah Layanan Pasien" data-bs-toggle="modal" data-bs-target="#tambah_layanan" style="width: 15vw; padding: 1vh 0;">
      <i class="fa fa-bookmark" style="font-size: 5vw;"></i><br>
      <span style="font-size: 2vw;">LAYANAN</span>
    </button>
  </div>
</div>

<div class="row isi" style="padding-bottom: 10vh;">
  <div class="container">
    @foreach($layanan as $lay)
    <div class="card card-body user-data-card" style="margin-top: 1vh;">
      <table width="100%" style="font-size: 2.5vw;">
        <tr>
          <td width="20%">Waktu</td>
          <td width="3%">:</td>
          <td>{{ $lay->waktu }}</td>
          <td rowspan="5" valign="top" width="1%">
            @if($lay->id_ruang_sub == Auth::user()->id_ruang)
            <a href="{{ route('pasien_layanan_hapus',Crypt::encrypt($lay->id)) }}" class="btn btn-danger btn-sm" onclick="return confirm('Hapus layanan ?')">
              <i class="fa fa-trash"></i>
            </a>
            @endif
          </td>
        </tr>
        <tr>
          <td>Petugas</td>
          <td>:</td>
          <td>{{ $lay->petugas }}</td>
        </tr>
        <tr>
          <td>Medis</td>
          <td>:</td>
          <td>{{ $lay->dpjp }}</td>
        </tr>
        <tr>
          <td>Jasa</td>
          <td>:</td>
          <td>{{ $lay->jasa }}</td>
        </tr>
        <tr>
          <td>Tarif</td>
          <td>:</td>
          <td style="font-weight: bold;">Rp. {{ number_format($lay->tarif,0) }}</td>
        </tr>
      </table>
    </div>
    @endforeach
  </div>
</div>

<div class="modal fade" id="tambah_layanan" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <label class="modal-title" style="font-size: 3.5vw; font-weight: bold;">Layanan Apotik</label>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">  
        <form class="form-horizontal fprev" id="pasien_layanan_form" method="POST" action="{{ route('pasien_layanan_laborat') }}">
        @csrf
          <input type="hidden" name="id_pasien_ruang" value="{{ $pass->id_pasien_ruang }}">
          <input type="hidden" name="id_pasien" value="{{ $pass->id }}">
          <input type="hidden" name="id_pasien_jenis" id="id_pasien_jenis" value="{{ $pass->id_pasien_jenis }}">
          <input type="hidden" name="id_pasien_jenis_rawat" id="id_pasien_jenis_rawat" value="{{ $pass->id_pasien_jenis_rawat }}">
          <input type="hidden" name="id_ruang" value="{{ $pass->id_ruang }}">

          <div class="mb-1">
            <div class="title mb-1">Jasa Layanan</div>
            <select class="form-control" name="id_jasa" id="id_jasa" required autofocus>
              <option value=""></option>
              @foreach($jasa as $jas)
                <option value="{{ $jas->id_jasa }}">{{ strtoupper($jas->jasa) }}</option>
              @endforeach
            </select>
          </div>

          @if(Auth::user()->id_ruang <> 52)
            <div class="mb-1">
              <div class="title mb-1">DPJP</div>
              <select class="form-control" name="id_dpjp" id="id_dpjp" required>
                <option value=""></option>
                @foreach($dpjp as $dok)
                  <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
                @endforeach
              </select>
            </div>
          @endif

          <div id="tanggung">
            <div class="mb-1">
              <div class="title mb-1">Penanggung Jawab</div>
              <select class="form-control" name="id_tanggung" id="id_tanggung">
                <option value=""></option>
                @foreach($dpjp as $dok)
                  <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Tarif</div>
            <div class="input-group">
              <span class="input-group-text" id="basic-addon1">Rp.</span>
              <input type="text" name="tarif" class="form-control nominal" autocomplete="off">
            </div>
          </div>        
        </form>
      </div>
      <div class="modal-footer">        
        <button type="submit" class="btn btn-secondary btn-sm bprev" form="pasien_layanan_form">SIMPAN</button>
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">BATAL</button>
      </div>
    </div>
  </div>  
</div>

<div class="modal fade" id="pasien_luar" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <label class="modal-title" style="font-size: 3.5vw; font-weight: bold;">Layanan Apotik</label>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal fprev" method="POST" id="form_pasien_luar" action="{{ route('pasien_luar_laborat') }}">
        @csrf

          <div class="mb-1">
            <div class="title mb-1">Nama</div>
            <input type="text" name="nama" class="form-control" required autofocus autocomplete="off">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Alamat</div>
            <input type="text" name="alamat" class="form-control" autocomplete="off">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Umur</div>
            <div class="row">
              <div class="col-6">
                <div class="input-group">
                  <input type="number" name="umur_thn" step="1" class="form-control" required autocomplete="off">
                  <span class="input-group-text" id="basic-addon1">Thn.</span>
                </div>
              </div>

              <div class="col-6">
                <div class="input-group">
                  <input type="number" name="umur_bln" step="1" class="form-control" autocomplete="off">
                  <span class="input-group-text" id="basic-addon1">Bln.</span>
                </div>
              </div>
            </div>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Kelamin</div>
            <select class="form-control" name="id_kelamin" required>
              <option value=""></option>
              <option value="1">LAKI-LAKI</option>
              <option value="2">PEREMPUAN</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Jasa Layanan</div>
            <select class="form-control" name="id_jasa" id="id_jasa_baru" required>
              <option value=""></option>
              @foreach($jasa as $jas)
                <option value="{{ $jas->id_jasa }}">{{ strtoupper($jas->jasa) }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">DPJP</div>
            <select class="form-control" name="id_dpjp" id="id_dpjp" required>
              <option value=""></option>
              @foreach($dpjp as $dok)
                <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
              @endforeach
            </select>
          </div>

          <div id="tanggung_baru">
            <div class="mb-1">
              <div class="title mb-1">Penanggung Jawab</div>
              <select class="form-control" name="id_tanggung" id="id_tanggung_baru">
                <option value=""></option>
                @foreach($dpjp as $dok)
                  <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>          

          <div class="mb-1">
            <div class="title mb-1">Tarif</div>
            <div class="input-group">
              <span class="input-group-text" id="basic-addon1">Rp.</span>
              <input type="text" name="tarif" class="form-control nominal" autocomplete="off">              
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-secondary btn-sm bprev" form="form_pasien_luar">SIMPAN</button>
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">BATAL</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    window.onload=function() {
      document.getElementById('tanggung').style.display = 'none';
      document.getElementById('tanggung').style.visibility = 'hidden';
      document.getElementById('id_tanggung').required = false;

      document.getElementById('tanggung_baru').style.display = 'none';
      document.getElementById('tanggung_baru').style.visibility = 'hidden';
      document.getElementById('id_tanggung_baru').required = false;
    };

    $('#id_jasa').on('change',function(){
      $id_jasa                = $(this).val();
      $id_pasien_jenis        = document.getElementById('id_pasien_jenis').value;
      $id_pasien_jenis_rawat  = document.getElementById('id_pasien_jenis_rawat').value;

      $.ajax({
        type : 'get',
        url : '{{ route("cek_tanggung") }}',
        data: {'id_jasa': $id_jasa, 'id_pasien_jenis': $id_pasien_jenis, 'id_pasien_jenis_rawat': $id_pasien_jenis_rawat},
        success: function(data){
          if(data == 1){
            document.getElementById('tanggung').style.display = 'block';
            document.getElementById('tanggung').style.visibility = 'visible';
            document.getElementById('id_tanggung').required = true;
          } else {
            document.getElementById('tanggung').style.display = 'none';
            document.getElementById('tanggung').style.visibility = 'hidden';
            document.getElementById('id_tanggung').required = false;
          }
        }
      });
    });

    $('#id_jasa_baru').on('change',function(){
      $id_jasa = $(this).val();

      $.ajax({
        type : 'get',
        url : '{{ route("cek_tanggung") }}',
        data: {'id_jasa': $id_jasa,' id_pasien_jenis': 1, 'id_pasien_jenis_rawat': 1},
        success: function(data){
          if(data == 1){
            document.getElementById('tanggung_baru').style.display = 'block';
            document.getElementById('tanggung_baru').style.visibility = 'visible';
            document.getElementById('id_tanggung_baru').required = true;
          } else {
            document.getElementById('tanggung_baru').style.display = 'none';
            document.getElementById('tanggung_baru').style.visibility = 'hidden';
            document.getElementById('id_tanggung_baru').required = false;
          }
        }
      });
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#tabel').DataTable( {
        "paginate": false,
        "searching": false,
        "info": false,
        "sort":false,
      });
    });
  </script>  
@endsection