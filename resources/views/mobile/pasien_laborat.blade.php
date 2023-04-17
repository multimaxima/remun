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
  <li><a href="{{ route('pasien_laborat_transaksi') }}"><i class="fa fa-book"></i>Data Layanan</a></li>
@endsection

@section('content')
<div class="row isi">
  <div class="container user-data-card" style="margin-top: 9vh;">
    <form class="form-horizontal" method="GET" action="{{ route('pasien_laborat') }}">
    @csrf

      <select class="form-control" name="id_ruang" onchange="this.form.submit();">
        <option value="" style="font-style: italic;">== SEMUA RUANG ==</option>
        @foreach($ruang as $rng)
          <option value="{{ $rng->id }}" {{ $rng->id == $id_ruang? 'selected' : null }}>{{ $rng->ruang }}</option>
        @endforeach
      </select>    

      <div class="input-group mb-1">                
        <input type="text" name="cari" class="form-control" value="{{ $cari }}" placeholder="Cari pasien">
        <button type="submit" class="input-group-text" id="basic-addon1">
          <i class="fa fa-search"></i>
        </button>
      </div>
    </form>      
  </div>
</div>

<div style="padding-bottom: 10vh;">
  <div class="row isi">
    <div class="container">
      @foreach($pasien as $pas)  
      <div class="card" style="margin-top: 1vh;" data-id="{{ $pas->id_pasien }}">
        <label class="card-header" style="font-size: 3vw; font-weight: bold;">
          {{ strtoupper($pas->nama) }} 
            @if($pas->umur_bln)
            ({{ $pas->umur_thn }} Thn. {{ $pas->umur_bln }} Bln.)
          @else
            ({{ $pas->umur_thn }} Thn.)
          @endif
        </label>
        <div class="card-body">
          <table width="100%" style="font-size: 2.5vw; text-align: left; line-height: 15px;">                
            <tr>
              <td width="60" valign="top">Reg / MR</td>
              <td width="10" valign="top">:</td>
              <td>{{ strtoupper($pas->register) }} / {{ strtoupper($pas->no_mr) }}</td>
            </tr>                    
            <tr>
              <td valign="top">Jenis</td>
              <td valign="top">:</td>
              <td>
                PASIEN {{ strtoupper($pas->jenis_pasien) }}
              </td>
            </tr>            
            <tr>
                <td valign="top">DPJP</td>
                <td valign="top">:</td>
                <td>
                  {{ $pas->dpjp }}
                </td>
              </tr>
            <tr>
              <td valign="top">Ruang</td>
              <td valign="top">:</td>
              <td>
                {{ strtoupper($pas->ruang) }}
              </td>
            </tr>
          </table>
        </div>
      </div>  
      @endforeach   
    </div>
  </div>
</div>

<div class="modal fade" id="pasien_luar" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <label class="modal-title" style="font-size: 3.5vw; font-weight: bold;">Layanan Pasien Luar</label>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal fprev" method="POST" action="{{ route('pasien_luar_laborat') }}">
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
              <input type="text" class="form-control nominal" name="tarif" required autocomplete="off">
            </div>
          </div>
        </form>            
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-secondary btn-sm bprev">SIMPAN</button>
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">BATAL</button>
      </div>
    </div>
  </div>
</div>

<form method="GET" action="{{ route('pasien_laborat_mobile') }}" id="form_detil">
@csrf
  <input type="hidden" name="id_pasien" id="id_pasien">
  <input type="hidden" name="cari" value="{{ $cari }}">
  <input type="hidden" name="id_ruang" value="{{ $id_ruang }}">
</form>    
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

  <script type="text/javascript">
    $(document).ready(function() {
      $('.card').on("click",function() {
        var id = $(this).attr('data-id');

        $('#id_pasien').val(id);
        $('#form_detil').submit();
      });
    });
  </script>
@endsection