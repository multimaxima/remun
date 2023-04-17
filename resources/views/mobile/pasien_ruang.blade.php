@extends('mobile.layouts.content')

@section('bawah')  
  @if($c_ruang->terima_pasien == 1)
  <li>
    <a href="#" data-bs-toggle="modal" data-bs-target="#pasien_baru" title="Pasien Masuk">
      <i class="fa fa-users"></i> Pasien Baru
    </a>
  </li>
  @else
  <li><a href="{{ route('jasa_remun') }}"><i class="fa fa-heart"></i>Remunerasi</a></li>
  @endif
  <li><a href="{{ route('pasien_ruang_data') }}"><i class="fa fa-book"></i>Data Layanan</a></li>
@endsection

@section('content')
<div class="row isi">
  <div class="container user-data-card" style="margin-top: 9vh;">
    <form method="GET" action="{{ route('pasien_ruang') }}">
    @csrf

      <select class="form-control" name="pasienku" onchange="this.form.submit();">
        <option value="0" {{ $pasienku == '0'? 'selected' : null }}>PASIEN {{ strtoupper($c_ruang->ruang) }}</option>
        <option value="1" {{ $pasienku == '1'? 'selected' : null }}>SEMUA RUANG</option>
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
              <td>{{ $pas->dpjp }}</td>
            </tr>
            @if($pas->id_ruang !== Auth::user()->id_ruang)
            <tr>
              <td valign="top">Ruang</td>
              <td valign="top">:</td>
              <td>
                {{ strtoupper($pas->ruang) }}
              </td>
            </tr>
            @endif
          </table>
        </div>
      </div>  
      @endforeach   
    </div>
  </div>
</div>

<div class="modal fade" id="pasien_baru" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <label class="modal-title" style="font-size: 3.5vw; font-weight: bold;">PASIEN BARU</label>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal container-fluid fprev" id="pasien_baru_form" method="POST" action="{{ route('pasien_baru') }}">
        @csrf
          @if($c_ruang->jalan == 1)
            <input type="hidden" name="id_pasien_jenis_rawat" value="1">
          @endif

          @if($c_ruang->inap == 1)
            <input type="hidden" name="id_pasien_jenis_rawat" value="2">
          @endif

          <div class="mb-1">
            <div class="title mb-1">Nama Pasien</div>
            <input type="text" class="form-control" name="nama" required autofocus autocomplete="off">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Alamat</div>
            <input type="text" class="form-control" name="alamat">
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
              <option value="1">LAKI-LAKI</option>
              <option value="2">PEREMPUAN</option>
            </select>
          </div>            

          <div class="mb-1">
            <div class="title mb-1">Nomor MR</div>
            <input type="text" class="form-control" name="no_mr" required autocomplete="off">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Jenis Pasien</div>
            <select class="form-control" name="id_pasien_jenis" required>
              <option value=""></option>
              @foreach($jenis as $jns)
                <option value="{{ $jns->id }}">{{ strtoupper($jns->jenis) }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">DPJP</div>
            <select class="form-control" size="5" name="id_dpjp" required style="height: 15vh;">
              @foreach($dpjp as $dok)
                <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
              @endforeach
            </select>
          </div>

          <div class="span12" style="margin-top: 5px;">
            <div class="alert alert-danger bg-danger text-white" role="alert" style="padding: 10px; font-size: 2.5vw;">
              * <b><i>Pastikan</i></b> bahwa data pasien yang Anda entri sudah benar !!!
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">    
        <div class="btn-group btn-group-sm pull-right">
          <button type="submit" form="pasien_baru_form" class="btn btn-secondary bprev">SIMPAN</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">BATAL</button>
        </div>
      </div>
    </div>
  </div>
</div>

<form method="GET" action="{{ route('pasien_ruang_mobile') }}" id="form_detil">
@csrf
  <input type="hidden" name="id_pasien" id="id_pasien">
  <input type="hidden" name="cari" value="{{ $cari }}">
  <input type="hidden" name="pasienku" value="{{ $pasienku }}">
</form>    
@endsection

@section('script')
  <script type="text/javascript">
    function myFunction() {
      document.getElementById("myDropdown").classList.toggle("show");
    }

    window.onclick = function(event) {
      if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
      }
    }
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