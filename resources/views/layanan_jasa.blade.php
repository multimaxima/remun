@extends('layouts.content')
@section('title','Jasa Layanan')

@section('content')
<div class="navbar">
  <div class="navbar-inner">
    <ul class="nav">
      <li><a href="{{ route('bank') }}">Bank</a></li>
      <li><a href="{{ route('ruang') }}">Ruang</a></li>
      <li><a href="{{ route('rekening_layanan') }}">Rekening Tarif</a></li>
      <li><a href="{{ route('bagian_tenaga') }}">Jenis Tenaga</a></li>
      <li><a href="{{ route('bagian') }}">Bagian</a></li>
      <li class="active"><a href="#">Jasa</a></li>
      <li><a href="{{ route('kategori_layanan') }}">Kategori Layanan</a></li>
      <li><a href="{{ route('jenis_pasien') }}">Jenis Pasien</a></li>
      <li><a href="{{ route('absensi') }}">Absensi</a></li>
    </ul>
    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#data_baru">TAMBAH</button>
  </div>
</div>

<div class="content">
  <form method="GET" action="{{ route('jasa_layanan') }}" class="form-inline" style="margin-bottom: 5px;">
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
  <table id="tabel" width="100%" class="table table-hover table-striped table-bordered">
    <thead>
      <th></th>
      <th>Jenis</th>
      <th>Jasa</th>
      <th>Operasi</th>
    </thead>
    <tbody>
      @foreach($jasa as $jas)                
      <tr>
        <td class="min">
          <div class="btn-group">
            <button class="btn btn-info btn-mini edit" title="Edit" data-toggle="modal" data-id="{{ $jas->id }}">
              <i class="icon-edit"></i>
            </button>
            <a href="{{ route('jasa_layanan_hapus',Crypt::encrypt($jas->id)) }}" class="btn btn-info btn-mini" title="Hapus" onclick="return confirm('Hapus {{ $jas->jasa }} ?')">
              <i class="icon-trash"></i>
            </a>
          </div>
        </td>
        <td>{{ $jas->jenis }}</td>
        <td>{{ $jas->jasa }}</td>
        <td class="min" style="text-align: center;">
          @if($jas->operasi == 1)
            YA
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div>
    <div class="pull-left" style="font-size: 12px;">
      Menampilkan {{ number_format($jasa->firstItem(),0) }} - {{ number_format($jasa->lastItem(),0) }} dari {{ number_format($jasa->total(),0) }} data
    </div>                               
    <div class="pagination pagination-small pull-right">
      {!! $jasa->appends(request()->input())->render("pagination::bootstrap-4"); !!}
    </div>
  </div>
</div>

<div class="modal hide fade" id="data_baru">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Tambah Jasa</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="baru_data" method="POST" action="{{ route('jasa_layanan_baru') }}">
    @csrf

      <div class="control-group">
        <label class="control-label span3">Jenis</label>
        <div class="controls span7">
          <input type="text" class="form-control" name="jenis" required autofocus>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Jasa</label>
        <div class="controls span7">
          <input type="text" class="form-control" name="jasa" required>
        </div>
      </div>          

      <div class="control-group">
        <label class="control-label span3">Operasi</label>
        <div class="controls span7">
          <select name="operasi">
            <option value="0">TIDAK</option>
            <option value="1">YA</option>
          </select>
        </div>
      </div>          
    </form>
  </div>
  <div class="modal-footer">         
    <div class="btn-group">       
      <button type="submit" form="baru_data" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="modal_data_edit">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Jasa</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_data" method="POST" action="{{ route('jasa_layanan_edit') }}">
    @csrf
      <input type="hidden" name="id" id="edit_id">

      <div class="control-group">
        <label class="control-label span3">Jenis</label>
        <div class="controls span7">
          <input type="text" class="form-control" name="jenis" id="edit_jenis" required autofocus>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Jasa</label>
        <div class="controls span7">
          <input type="text" class="form-control" name="jasa" id="edit_jasa" required>
        </div>
      </div>     

      <div class="control-group">
        <label class="control-label span3">Operasi</label>
        <div class="controls span7">
          <select name="operasi" id="edit_operasi">
            <option value="0">TIDAK</option>
            <option value="1">YA</option>
          </select>
        </div>
      </div>               
    </form>
  </div>
  <div class="modal-footer">                
    <div class="btn-group">
      <button type="submit" form="form_edit_data" class="btn bprev">SIMPAN</button>
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
          url : "{{route('jasa_layanan_edit_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#edit_id').val(data.id);
            $('#edit_jenis').val(data.jenis);
            $('#edit_jasa').val(data.jasa);
            $('#edit_operasi').val(data.operasi);
            $('#modal_data_edit').modal('show');
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