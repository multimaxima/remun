@extends('mobile.layouts.content')

@section('bawah')
  <li>
    <a href="#" data-bs-toggle="modal" data-bs-target="#pasien_layanan_non">
      <i class="fa fa-users"></i> Pasien Luar
    </a>
  </li>
  <li><a href="{{ route('pasien_apotik_transaksi') }}"><i class="fa fa-book"></i>Data Layanan</a></li>
@endsection

@section('content')
<div class="row isi">
  <div class="container user-data-card" style="margin-top: 9vh;">
    <form method="GET" action="{{ route('pasien_apotik') }}">
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

<div class="modal fade" id="pasien_layanan_non" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <label class="modal-title" style="font-size: 3.5vw; font-weight: bold;">Layanan Pasien Luar</label>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal fprev" id="pasien_layanan_non_form" method="POST" action="{{ route('pasien_apotik_non') }}">
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
            <select class="form-control" name="id_kelamin" required size="2" style="height: 6vh;">
              <option value="1">LAKI-LAKI</option>
              <option value="2">PEREMPUAN</option>
            </select>
          </div>            

          <div class="mb-1">
            <div class="title mb-1">Layanan</div>
            <select class="form-control" name="id_jasa" required size="2" style="height: 6vh;">
              @foreach($jasa as $jas)
              <option value="{{ $jas->id }}">{{ strtoupper($jas->jasa) }}</option>
              @endforeach
            </select>
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
        <div class="btn-group">              
          <button type="submit" form="pasien_layanan_non_form" class="btn btn-secondary btn-sm bprev">SIMPAN</button>
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">TUTUP</button>
        </div>
      </div>
    </div>
  </div>
</div>

<form method="GET" action="{{ route('pasien_apotik_mobile') }}" id="form_detil">
@csrf
  <input type="hidden" name="id_pasien" id="id_pasien">
  <input type="hidden" name="cari" value="{{ $cari }}">
  <input type="hidden" name="id_ruang" value="{{ $id_ruang }}">
</form>    
@endsection

@section('script')
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