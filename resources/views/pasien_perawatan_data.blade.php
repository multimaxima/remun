@extends('layouts.content')
@section('title','Data Pasien Dalam Perawatan')

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12">
        <form class="form-inline" method="GET" action="{{ route('pasien_perawatan_data') }}" style="margin-top: 5px; margin-bottom: 0;">
        @csrf

          <input type="hidden" name="tampil" value="{{ $tampil }}">
          <input type="hidden" name="cari" value="{{ $cari }}">
          
          <select name="id_pasien_jenis" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">Jenis Pasien</option>
            @foreach($jenis as $jns)
              <option value="{{ $jns->id }}" {{ $id_pasien_jenis == $jns->id? 'selected' : null }}>{{ $jns->jenis }}</option>
            @endforeach
          </select>

          <select name="id_pasien_jenis_rawat" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">Jenis Perawatan</option>
            <option value="1" {{ $id_pasien_jenis_rawat == '1'? 'selected' : null }}>Rawat Jalan</option>
            <option value="2" {{ $id_pasien_jenis_rawat == '2'? 'selected' : null }}>Rawat Inap</option>
          </select>

          <select name="id_ruang" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">Pilih Ruang</option>
            @foreach($ruang as $rng)
              <option value="{{ $rng->id }}" {{ $id_ruang == $rng->id? 'selected' : null }}>{{ $rng->ruang }}</option>
            @endforeach
          </select>

          <select name="id_dpjp" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">Pilih DPJP</option>
            @foreach($dpjp as $dokter)
              <option value="{{ $dokter->id }}" {{ $id_dpjp == $dokter->id? 'selected' : null }}>{{ $dokter->nama }}</option>
            @endforeach
          </select>                   
        </form>        
      </div>
    </div>
  </div>
</div>

<div class="content">
  <form method="GET" action="{{ route('pasien_perawatan_data') }}" class="form-inline" style="margin-bottom: 5px;">
  @csrf
    <input type="hidden" name="id_pasien_jenis" value="{{ $id_pasien_jenis }}">
    <input type="hidden" name="id_pasien_jenis_rawat" value="{{ $id_pasien_jenis_rawat }}">
    <input type="hidden" name="id_ruang" value="{{ $id_ruang }}">
    <input type="hidden" name="id_dpjp" value="{{ $id_dpjp }}">

    Menampilkan
    <select onchange="this.form.submit();" name="tampil">
      <option value="10" {{ $tampil == '10'? 'selected' : null }}>10</option>
      <option value="25" {{ $tampil == '25'? 'selected' : null }}>25</option>
      <option value="50" {{ $tampil == '50'? 'selected' : null }}>50</option>
      <option value="100" {{ $tampil == '100'? 'selected' : null }}>100</option>
      <option value="9999999999999" {{ $tampil == '9999999999999'? 'selected' : null }}>Semua</option>
    </select> data

    <input type="text" name="cari" class="pull-right" placeholder="Cari..." value="{{ $cari }}">
  </form>

  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered">
    <thead>
      <th></th>
      <th>Nama Pasien</th>
      <th>Register</th>
      <th>MR</th>
      <th>Alamat</th>
      <th>Masuk</th>
      <th>Jenis</th>
      <th>Umur</th>
      <th>L/P</th>
      <th>Ruang</th>
      <th>DPJP</th>
    </thead>
    <tbody>
      @foreach($pasien as $pas)
      <tr>
        <td class="min">
          <a href="{{ route('pasien_perawatan_data_detil',Crypt::encrypt($pas->id_pasien)) }}" class="btn btn-info btn-mini" title="Rincian Layanan" target="_blank">
            <i class="icon-list"></i>
          </a>
        </td>
        <td class="min">{{ strtoupper($pas->nama) }}</td>
        <td class="min">{{ $pas->register }}</td>
        <td class="min">{{ $pas->no_mr }}</td>
        <td class="min">{{ strtoupper($pas->alamat) }}</td>
        <td class="min">{{ strtoupper($pas->masuk) }}</td>
        <td class="min">{{ strtoupper($pas->jenis) }}</td>
        <td class="min" style="text-align: center;">
          {{ $pas->umur_thn }} Thn. 
          @if($pas->umur_bln)
            {{ $pas->umur_bln }} Bln.
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($pas->id_kelamin == 1)
            L
          @endif

          @if($pas->id_kelamin == 2)
            P
          @endif
        </td>
        <td class="min">{{ strtoupper($pas->ruang) }}</td>
        <td class="min">{{ $pas->dpjp }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div>
    <div class="pull-left" style="font-size: 12px;">
      Menampilkan {{ number_format($pasien->firstItem(),0) }} - {{ number_format($pasien->lastItem(),0) }} dari {{ number_format($pasien->total(),0) }} data
    </div>                               
    <div class="pagination pagination-small pull-right">
      {!! $pasien->appends(request()->input())->render("pagination::bootstrap-4"); !!}
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.21*box.clientHeight);

      $('#tabel').DataTable( {     
        "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": 0
        }],        
        "order": [[ 1, "asc" ]],
        scrollY:        tinggi,
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        searching:      false,
        sort:           true,
        info:           false,
      });
    });
  </script>
@endsection