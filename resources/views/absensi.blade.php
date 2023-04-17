@extends('layouts.content')
@section('title','Absensi')

@section('content')
<div class="navbar">
  <div class="navbar-inner">
    <ul class="nav">
      <li><a href="{{ route('bank') }}">Bank</a></li>
      <li><a href="{{ route('ruang') }}">Ruang</a></li>
      <li><a href="{{ route('rekening_layanan') }}">Rekening Tarif</a></li>
      <li><a href="{{ route('bagian_tenaga') }}">Jenis Tenaga</a></li>
      <li><a href="{{ route('bagian') }}">Bagian</a></li>
      <li><a href="{{ route('jasa_layanan') }}">Jasa</a></li>
      <li><a href="{{ route('kategori_layanan') }}">Kategori Layanan</a></li>
      <li><a href="{{ route('jenis_pasien') }}">Jenis Pasien</a></li>
      <li class="active"><a href="#">Absensi</a></li>
    </ul>
    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modal_baru">TAMBAH</button>
  </div>
</div>

<div class="content">
  <form method="GET" action="{{ route('absensi') }}" class="form-inline" style="margin-bottom: 5px;">
  @csrf
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

  @include('layouts.pesan')
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered">
    <thead>
      <tr>
        <th rowspan="2"></th>
        <th rowspan="2" class="min" style="padding: 0 10px;">Jenis Absensi</th>
        <th colspan="4" class="min" style="padding: 0 10px;">Perhitungan Indeks</th>
      </tr>      
      <tr>
        <th class="min" style="padding: 0 10px;">Pos Remun</th>
        <th class="min" style="padding: 0 10px;">Staf Direksi</th>
        <th class="min" style="padding: 0 10px;">Administrasi</th>
        <th class="min" style="padding: 0 10px;">JP Medis</th>
      </tr>
    </thead>
    <tbody>
      @foreach($absensi as $abs)                
      <tr>
        <td class="min">
          <div class="btn-group">
            <button class="btn btn-info btn-mini edit" title="Edit" data-toggle="modal" data-id="{{ $abs->id }}">
              <i class="icon-edit"></i>
            </button>
          </div>
        </td>
        <td>{{ $abs->absen }}</td>        
        <td class="min" style="text-align: center;">
          @if($abs->indeks == 1)
            <i class="fa fa-check"></i>
          @endif
        </td>
        <td class="min" style="text-align: center;">
          @if($abs->staf == 1)
            <i class="fa fa-check"></i>
          @endif
        </td>
        <td class="min" style="text-align: center;">
          @if($abs->administrasi == 1)
            <i class="fa fa-check"></i>
          @endif
        </td>
        <td class="min" style="text-align: center;">
          @if($abs->jasa == 1)
            <i class="fa fa-check"></i>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div>
    <div class="pull-left" style="font-size: 12px;">
      Menampilkan {{ number_format($absensi->firstItem(),0) }} - {{ number_format($absensi->lastItem(),0) }} dari {{ number_format($absensi->total(),0) }} data
    </div>                               
    <div class="pagination pagination-small pull-right">
      {!! $absensi->appends(request()->input())->render("pagination::bootstrap-4"); !!}
    </div>
  </div>
</div>

<div class="modal hide fade" id="modal_baru">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Tambah Jenis Absensi</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_baru" method="POST" action="{{ route('absensi_baru') }}">
    @csrf

      <div class="control-group">
        <label class="control-label span3">Jenis</label>
        <div class="controls span7">
          <input type="text" class="form-control" name="absen" required autofocus>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Pos Remun</label>
        <div class="controls span4">
          <select class="form-control" name="indeks" required>
            <option value="0">TIDAK DIHITUNG</option>
            <option value="1">DIHITUNG</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Staf Direksi</label>
        <div class="controls span4">
          <select class="form-control" name="staf" required>
            <option value="0">TIDAK DIHITUNG</option>
            <option value="1">DIHITUNG</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Administrasi</label>
        <div class="controls span4">
          <select class="form-control" name="administrasi" required>
            <option value="0">TIDAK DIHITUNG</option>
            <option value="1">DIHITUNG</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">JP Medis</label>
        <div class="controls span4">
          <select class="form-control" name="jasa" required>
            <option value="0">TIDAK DIHITUNG</option>
            <option value="1">DIHITUNG</option>
          </select>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_baru" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="modal_edit">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Jenis Absensi</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit" method="POST" action="{{ route('absensi_simpan') }}">
    @csrf
      <input type="hidden" name="id" id="edit_id_absensi">

      <div class="control-group">
        <label class="control-label span3">Jenis</label>
        <div class="controls span7">
          <input type="text" class="form-control" name="absen" id="edit_absen" required autofocus>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Pos Remun</label>
        <div class="controls span4">
          <select class="form-control" name="indeks" id="edit_indeks" required>
            <option value="0">TIDAK DIHITUNG</option>
            <option value="1">DIHITUNG</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Staf Direksi</label>
        <div class="controls span4">
          <select class="form-control" name="staf" id="edit_staf" required>
            <option value="0">TIDAK DIHITUNG</option>
            <option value="1">DIHITUNG</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Administrasi</label>
        <div class="controls span4">
          <select class="form-control" name="administrasi" id="edit_administrasi" required>
            <option value="0">TIDAK DIHITUNG</option>
            <option value="1">DIHITUNG</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">JP Medis</label>
        <div class="controls span4">
          <select class="form-control" name="jasa" id="edit_jasa" required>
            <option value="0">TIDAK DIHITUNG</option>
            <option value="1">DIHITUNG</option>
          </select>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('.edit').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{route('absensi_simpan_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#edit_id_absensi').val(data.id);
            $('#edit_absen').val(data.absen);
            $('#edit_indeks').val(data.indeks);
            $('#edit_staf').val(data.staf);
            $('#edit_administrasi').val(data.administrasi);
            $('#edit_jasa').val(data.jasa);
            $('#modal_edit').modal('show');
          }
        });
      });
    });

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