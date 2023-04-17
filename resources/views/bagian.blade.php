@extends('layouts.content')
@section('title','Bagian')

@section('content')
<div class="navbar">
  <div class="navbar-inner">
    <ul class="nav">
      <li><a href="{{ route('bank') }}">Bank</a></li>
      <li><a href="{{ route('ruang') }}">Ruang</a></li>
      <li><a href="{{ route('rekening_layanan') }}">Rekening Tarif</a></li>
      <li><a href="{{ route('bagian_tenaga') }}">Jenis Tenaga</a></li>
      <li class="active"><a href="#">Bagian</a></li>
      <li><a href="{{ route('jasa_layanan') }}">Jasa</a></li>
      <li><a href="{{ route('kategori_layanan') }}">Kategori Layanan</a></li>
      <li><a href="{{ route('jenis_pasien') }}">Jenis Pasien</a></li>
      <li><a href="{{ route('absensi') }}">Absensi</a></li>
    </ul>
    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modal_bagian_baru">TAMBAH</button>
  </div>
</div>

<div class="content">
  <form method="GET" action="{{ route('bagian') }}" class="form-inline" style="margin-bottom: 5px;">
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
        <th rowspan="2" style="padding: 0 10px;"></th>
        <th rowspan="2" style="padding: 0 10px;">Tenaga</th>
        <th rowspan="2" style="padding: 0 10px;">Bagian</th>
        <th colspan="3" style="padding: 0 10px;">Kelompok Indeks</th>
        <th rowspan="2" style="padding: 0 10px;">Rekening Jasa Medis</th>
      </tr>
      <tr>
        <th style="padding: 0 10px;">Indeks Kel. Perawat</th>
        <th style="padding: 0 10px;">Indeks Staf Direksi</th>
        <th style="padding: 0 10px;">Indeks Administrasi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($bagian as $bag)                
      <tr>
        <td class="min">
          <div class="btn-group">
            <button class="btn btn-info btn-mini edit_bagian" title="Edit" data-toggle="modal" data-id="{{ $bag->id }}">
              <i class="icon-edit"></i>
            </button>
            <a href="{{ route('bagian_hapus',Crypt::encrypt($bag->id)) }}" class="btn btn-info btn-mini" title="Hapus" onclick="return confirm('Hapus tenaga bagian ?')">
              <i class="icon-trash"></i>
            </a>
          </div>
        </td>
        <td>{{ $bag->tenaga }}</td>
        <td>{{ $bag->bagian }}</td>
        <td style="text-align: center;">
          @if($bag->insentif_perawat == 1)
            <i class="icon-ok"></i>
          @endif                    
        </td>
        <td style="text-align: center;">
          @if($bag->direksi == 1)
            <i class="icon-ok"></i>
          @endif                    
        </td>
        <td style="text-align: center;">
          @if($bag->administrasi == 1)
            <i class="icon-ok"></i>
          @endif                    
        </td>        
        <td>{{ $bag->nama }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div>
    <div class="pull-left" style="font-size: 12px;">
      Menampilkan {{ number_format($bagian->firstItem(),0) }} - {{ number_format($bagian->lastItem(),0) }} dari {{ number_format($bagian->total(),0) }} data
    </div>                               
    <div class="pagination pagination-small pull-right">
      {!! $bagian->appends(request()->input())->render("pagination::bootstrap-4"); !!}
    </div>
  </div>
</div>

<div class="modal hide fade" id="modal_bagian_baru">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Tambah Bagian</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_bagian_baru" method="POST" action="{{ route('bagian_baru') }}">
    @csrf

      <div class="control-group">
        <label class="control-label span4">Tenaga</label>
        <div class="controls span7">
          <select class="form-control" name="id_tenaga" required autofocus>
            <option value=""></option>
            @foreach($tenaga as $ten)
            <option value="{{ $ten->id }}">{{ $ten->tenaga }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span4">Bagian</label>
        <div class="controls span7">
          <input type="text" class="form-control" name="bagian" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span4">Indeks Kel. Perawat</label>
        <div class="controls span2">
          <select class="form-control" name="insentif_perawat" required>
            <option value="0">TIDAK</option>
            <option value="1">YA</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span4">Indeks Staf Direksi</label>
        <div class="controls span2">
          <select class="form-control" name="direksi" required>
            <option value="0">TIDAK</option>
            <option value="1">YA</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span4">Indeks Administrasi</label>
        <div class="controls span2">
          <select class="form-control" name="administrasi" required>
            <option value="0">TIDAK</option>
            <option value="1">YA</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span4">Rekening Jasa Medis</label>
        <div class="controls span7">
          <select class="form-control" name="id_rekening" required>
            <option value=""></option>
            @foreach($rekening as $rek)
            <option value="{{ $rek->id }}">{{ $rek->nama }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">   
    <div class="btn-group">             
      <button type="submit" form="form_bagian_baru" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="modal_bagian_edit">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Bagian</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_bagian" method="POST" action="{{ route('bagian_edit') }}">
    @csrf
      <input type="hidden" name="id" id="edit_id_bagian">

      <div class="control-group">
        <label class="control-label span4">Tenaga</label>
        <div class="controls span7">
          <select class="form-control" name="id_tenaga" id="edit_id_tenaga_bagian" required autofocus>
            <option value=""></option>
            @foreach($tenaga as $ten)
              <option value="{{ $ten->id }}" {{ $bag->id_tenaga == $ten->id? 'selected' : null }}>{{ $ten->tenaga }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span4">Bagian</label>
        <div class="controls span7">
          <input type="text" class="form-control" name="bagian" id="edit_bagian" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span4">Indeks Kel. Perawat</label>
        <div class="controls span2">
          <select class="form-control" name="insentif_perawat" id="edit_insentif_perawat" required>
            <option value="0" {{ $bag->insentif_perawat == '0'? 'selected' : null }}>TIDAK</option>
            <option value="1" {{ $bag->insentif_perawat == '1'? 'selected' : null }}>YA</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span4">Indeks Staf Direksi</label>
        <div class="controls span2">
          <select class="form-control" name="direksi" id="edit_direksi" required>
            <option value="0" {{ $bag->direksi == '0'? 'selected' : null }}>TIDAK</option>
            <option value="1" {{ $bag->direksi == '1'? 'selected' : null }}>YA</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span4">Indeks Administrasi</label>
        <div class="controls span2">
          <select class="form-control" name="administrasi" id="edit_administrasi" required>
            <option value="0" {{ $bag->administrasi == '0'? 'selected' : null }}>TIDAK</option>
            <option value="1" {{ $bag->administrasi == '1'? 'selected' : null }}>YA</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span4">Rekening Jasa Medis</label>
        <div class="controls span7">
          <select class="form-control" name="id_rekening" id="edit_id_rekening" required>
            <option value=""></option>
            @foreach($rekening as $rek)
              <option value="{{ $rek->id }}" {{ $bag->id_rekening == $rek->id? 'selected' : null }}>{{ $rek->nama }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_bagian" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('.edit_bagian').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{route('bagian_edit_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#edit_id_bagian').val(data.id);
            $('#edit_id_tenaga_bagian').val(data.id_tenaga);
            $('#edit_bagian').val(data.bagian);
            $('#edit_insentif_perawat').val(data.insentif_perawat);
            $('#edit_direksi').val(data.direksi);
            $('#edit_administrasi').val(data.administrasi);
            $('#edit_id_rekening').val(data.id_rekening);
            $('#modal_bagian_edit').modal('show');
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