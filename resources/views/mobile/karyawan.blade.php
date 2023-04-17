@extends('mobile.layouts.content')

@section('bawah')
  <li><a href="#" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne"><i class="fa fa-filter"></i>Filter</a></li>
  <li><a href="#" onclick="document.getElementById('form_baru').submit();"><i class="fa fa-plus"></i>Tambah</a></li>
@endsection

@section('content')
<div class="row isi">
  <div class="container" style="margin-top: 8vh;">    
  </div>
</div>

<div class="collapse" id="collapseOne" aria-labelledby="headingOne" style="margin-top: 1vh;">
  <div class="row isi">
    <div class="container">    
      <div class="card">
        <div class="card-body user-data-card" style="font-size: 3vw;"> 
          <form class="form-inline" method="GET" action="{{ route('karyawan') }}">
          @csrf
            <input type="hidden" name="cari" value="{{ $cari }}">
            <input type="hidden" name="tampil" value="{{ $tampil }}">

            <div class="mb-1">
              <div class="title mb-1">Ruang</div>
              <select name="id_ruang" id="id_ruang" onchange="this.form.submit();" class="form-control">
                <option value="">=== SEMUA RUANG ===</option>
                @foreach($ruang as $rng)
                  <option value="{{ $rng->id }}" {{ $id_ruang == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-1">
              <div class="title mb-1">Bagian</div>
              <select name="id_bagian" id="id_bagian" onchange="this.form.submit();" class="form-control">
                <option value="">=== SEMUA BAGIAN ===</option>
                @foreach($bagian as $bag)
                  <option value="{{ $bag->id }}" {{ $id_bagian == $bag->id? 'selected' : null }}>{{ strtoupper($bag->bagian) }}</option>
                @endforeach
              </select>
            </div>
          </form>        
        </div>
      </div>
    </div>
  </div>
</div>

<form hidden id="cetak" method="GET" action="{{ route('karyawan_cetak') }}" target="_blank">
@csrf
  <input type="text" name="id_ruang" id="c_id_ruang" value="">
</form>  

@include('mobile.layouts.pesan')

<div class="row isi" style="padding-bottom: 10vh;">
  <div class="container">    
    @foreach($karyawan as $kary)
    <div class="card" style="margin-top: 1vh;">
      <div class="card-body" style="font-size: 3vw;">  
        <label style="font-size: 3.5vw; font-weight: bold;">{{ $kary->nama }}</label>
        <table width="100%">
          <tr>
            <td width="30%">Bagian</td>
            <td width="3%">:</td>
            <td>{{ strtoupper($kary->bagian) }}</td>
          </tr>
          <tr>
            <td>Ruang</td>
            <td>:</td>
            <td>{{ strtoupper($kary->ruang) }}</td>
          </tr>
          <tr>
            <td>No. Rekening</td>
            <td>:</td>
            <td>{{ strtoupper($kary->rekening) }}</td>
          </tr>
          <tr>
            <td>Skore</td>
            <td>:</td>
            <td>{{ strtoupper($kary->skore) }}</td>
          </tr>
        </table>
        <div class="btn-group btn-group-sm">
            <button class="btn btn-secondary btn-sm edit" data-id="{{ $kary->id }}" title="Edit Data Karyawan">
              EDIT
            </button>            

            @if($a_param->dasar_remun == 2)
            <button class="btn btn-secondary btn-sm edit_history" title="Histori Data Karyawan" data-id="{{ $kary->id }}">
              HISTORY
            </button>            
            @endif

            <a href="{{ route('karyawan_reset',Crypt::encrypt($kary->id)) }}" class="btn btn-secondary btn-sm" title="Reset Password {{ $kary->nama }}" onclick="return confirm('Reset password {{ $kary->nama }} ?')">
              RESET PASSWORD
            </a>

            <a href="{{ route('karyawan_hapus',Crypt::encrypt($kary->id)) }}" class="btn btn-secondary btn-sm" title="Hapus Karyawan" onclick="return confirm('Hapus karyawan {{ $kary->nama }} ?')">
              HAPUS
            </a>
          </div>
      </div>
    </div>
    @endforeach

    <div style="margin-top: 1vh;">
      <div class="pull-left" style="font-size: 12px;">
        {{ number_format($karyawan->firstItem(),0) }} - {{ number_format($karyawan->lastItem(),0) }} dari {{ number_format($karyawan->total(),0) }} data
      </div>                               
      <div class="pagination pagination-sm pull-right">
        {!! $karyawan->appends(request()->input())->render("pagination::simple-bootstrap-4"); !!}
      </div>
    </div>
  </div>
</div>

<form hidden method="GET" action="{{ route('karyawan_edit') }}" id="form_edit">
@csrf
  <input type="text" name="id" id="edit_id">
  <input type="text" name="tampil" value="{{ $tampil }}">
  <input type="text" name="cari" value="{{ $cari }}">
  <input type="text" name="id_ruang" value="{{ $id_ruang }}">
  <input type="text" name="id_bagian" value="{{ $id_bagian }}">
</form>   

<form hidden method="GET" action="{{ route('karyawan_baru') }}" id="form_baru">
@csrf
  <input type="text" name="tampil" value="{{ $tampil }}">
  <input type="text" name="cari" value="{{ $cari }}">
  <input type="text" name="id_ruang" value="{{ $id_ruang }}">
  <input type="text" name="id_bagian" value="{{ $id_bagian }}">
</form>   

<form hidden method="GET" action="{{ route('karyawan_histori') }}" id="form_histori" target="_blank">
@csrf
  <input type="text" name="id" id="id_edit_history">
</form>
@endsection

@section('script')
  <script type="text/javascript">
    window.onload=function() {
      $id_ruang = document.getElementById('id_ruang').value;

      document.getElementById('c_id_ruang').value = $id_ruang;
    }

    $(document).ready(function() {
      $('.edit').on("click",function() {
        var id = $(this).attr('data-id');
        $('#edit_id').val(id);
        $('#form_edit').submit();
      });

      $('.edit_history').on("click",function() {
        var id = $(this).attr('data-id');
        $('#id_edit_history').val(id);
        $('#form_histori').submit();
      });
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.21*box.clientHeight);

      $('#tabel').DataTable( {     
        scrollY:        tinggi,
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        searching:      false,
        sort:           false,
        info:           false,
      });
    });
  </script>
@endsection