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
    <a href="#" data-bs-toggle="modal" data-bs-target="#pasien_layanan">
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
    <a href="{{ route('pasien_operasi') }}" class="btn btn-danger" style="width: 15vw; padding: 1vh 0;">
      <i class="fa fa-angle-left" style="font-size: 5vw;"></i><br>
      <span style="font-size: 2vw;">KEMBALI</span>
    </a>
    <button type="button" class="btn btn-secondary" title="Tambah Layanan Pasien" data-bs-toggle="modal" data-bs-target="#pasien_layanan" style="width: 15vw; padding: 1vh 0;">
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
          <td>Jasa</td>
          <td>:</td>
          <td>{{ strtoupper($lay->jasa) }}</td>
        </tr>
        <tr>
          <td>Operastor</td>
          <td>:</td>
          <td>{{ $lay->operator }}</td>
        </tr>
        <tr>
          <td>Tarif</td>
          <td>:</td>
          <td>{{ number_format($lay->tarif,0) }}</td>
        </tr>
      </table>
    </div>
    @endforeach
  </div>
</div>

<div class="modal fade" id="pasien_layanan" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <label class="modal-title" style="font-size: 3.5vw; font-weight: bold;">Layanan Operasi</label>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal container-fluid fprev" id="pasien_layanan_form" method="POST" action="{{ route('pasien_layanan_operasi') }}">
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

          <div class="mb-1">
            <div class="title mb-1">Operator</div>
            <select class="form-control" name="id_operator" id="id_operator" required>
              <option value=""></option>
              @foreach($operator as $opr)
                <option value="{{ $opr->id }}">{{ $opr->nama }}</option>
              @endforeach
            </select>
          </div>

          <div id="anastesi">
            <div class="mb-1">
              <div class="title mb-1">Anastesi</div>
              <select class="form-control" name="id_anastesi" id="id_anastesi">
                <option value=""></option>
                @foreach($anastesi as $dok)
                  <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div id="pendamping">
            <div class="mb-1">
              <div class="title mb-1">Spesialis Pend.</div>
              <select class="form-control" name="id_pendamping" id="id_pendamping">
                <option value=""></option>
                @foreach($pendamping as $dok)
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
@endsection

@section('script')
  <script type="text/javascript">
    window.onload=function() {
      document.getElementById('pendamping').style.display = 'none';
      document.getElementById('pendamping').style.visibility = 'hidden';
      document.getElementById('id_pendamping').required = false;
      document.getElementById('anastesi').style.display = 'none';
      document.getElementById('anastesi').style.visibility = 'hidden';
      document.getElementById('id_anastesi').required = false;
    };

    $('#id_jasa').on('change',function(){
      $id_jasa                = $(this).val();
      $id_pasien_jenis        = document.getElementById('id_pasien_jenis').value;
      $id_pasien_jenis_rawat  = document.getElementById('id_pasien_jenis_rawat').value;

      $.ajax({
        type : 'get',
        url : '{{ route("cek_anastesi") }}',
        data: {'id_jasa': $id_jasa, 'id_pasien_jenis': $id_pasien_jenis, 'id_pasien_jenis_rawat': $id_pasien_jenis_rawat},
        success: function(data){
          if(data == 1){
            document.getElementById('anastesi').style.display = 'block';
            document.getElementById('anastesi').style.visibility = 'visible';
            document.getElementById('id_anastesi').required = true;
          } else {
            document.getElementById('anastesi').style.display = 'none';
            document.getElementById('anastesi').style.visibility = 'hidden';
            document.getElementById('id_anastesi').required = false;
          }
        }
      });

      $.ajax({
        type : 'get',
        url : '{{ route("cek_pendamping") }}',
        data: {'id_jasa': $id_jasa, 'id_pasien_jenis': $id_pasien_jenis, 'id_pasien_jenis_rawat': $id_pasien_jenis_rawat},
        success: function(data){
          if(data == 1){
            document.getElementById('pendamping').style.display = 'block';
            document.getElementById('pendamping').style.visibility = 'visible';
            document.getElementById('id_pendamping').required = true;
          } else {
            document.getElementById('pendamping').style.display = 'none';
            document.getElementById('pendamping').style.visibility = 'hidden';
            document.getElementById('id_pendamping').required = false;
          }
        }
      });
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#tabel').DataTable( {
        "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": 4
        }],
        "paginate": false,
        "searching": false,
        "info": false,
        "sort": false,
      });
    });
  </script>  
@endsection