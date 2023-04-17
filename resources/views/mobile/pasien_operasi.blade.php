@extends('mobile.layouts.content')

@section('bawah')
  <li>
    <a href="#" data-bs-toggle="modal" data-bs-target="#pasien_layanan_non">
      <i class="fa fa-users"></i> Pasien Luar
    </a>
  </li>
  <li><a href="{{ route('pasien_operasi_transaksi') }}"><i class="fa fa-book"></i>Data Layanan</a></li>
@endsection

@section('content')
<div class="row isi">
  <div class="container user-data-card" style="margin-top: 9vh;">
    <form method="GET" action="{{ route('pasien_operasi') }}">
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
              <td width="70" valign="top">Reg / MR</td>
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
                {{ $pas->ruang }}
              </td>
            </tr>
          </table>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>

<form method="GET" action="{{ route('pasien_operasi_mobile') }}" id="form_detil">
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