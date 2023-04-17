@extends('layouts.content')
@section('title','Rekening Layanan')

@section('content')
<div class="navbar">
  <div class="navbar-inner">
    <ul class="nav">
      <li><a href="{{ route('bank') }}">Bank</a></li>
      <li><a href="{{ route('ruang') }}">Ruang</a></li>
      <li class="active"><a href="#">Rekening Tarif</a></li>
      <li><a href="{{ route('bagian_tenaga') }}">Jenis Tenaga</a></li>
      <li><a href="{{ route('bagian') }}">Bagian</a></li>
      <li><a href="{{ route('jasa_layanan') }}">Jasa</a></li>
      <li><a href="{{ route('kategori_layanan') }}">Kategori Layanan</a></li>
      <li><a href="{{ route('jenis_pasien') }}">Jenis Pasien</a></li>
      <li><a href="{{ route('absensi') }}">Absensi</a></li>
    </ul>
    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#data_baru">TAMBAH</button>
  </div>
</div>

<div class="content">
  <form method="GET" action="{{ route('rekening_layanan') }}" class="form-inline" style="margin-bottom: 5px;">
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
      <th></th>
      <th>Nama Rekening</th>
      <th width="45">Level</th>
      <th width="50">Individu</th>
      <th width="50">Kelompok</th>
    </thead>
    <tbody>
      @foreach($rekening as $rek)
      <tr>
        <td class="min">
          <div class="btn-group">
            <button class="btn btn-info btn-mini edit" title="Edit" data-toggle="modal" data-id="{{ $rek->id }}">
              <i class="icon-edit"></i>
            </button>

            <a href="{{ route('rekening_layanan_hapus',Crypt::encrypt($rek->id)) }}" class="btn btn-info btn-mini" title="Hapus" onclick="return confirm('Hapus rekening {{ $rek->nama }} ?')">
              <i class="icon-trash"></i>
            </a>
          </div>
        </td>
        <td>{{ $rek->nama }}</td>
        <td style="text-align: center;">
          {{ $rek->level }}
        </td>
        <td style="text-align: center;">
          @if($rek->individu == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;">
          @if($rek->kelompok == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div>
    <div class="pull-left" style="font-size: 12px;">
      Menampilkan {{ number_format($rekening->firstItem(),0) }} - {{ number_format($rekening->lastItem(),0) }} dari {{ number_format($rekening->total(),0) }} data
    </div>                               
    <div class="pagination pagination-small pull-right">
      {!! $rekening->appends(request()->input())->render("pagination::bootstrap-4"); !!}
    </div>
  </div>
</div>

<div class="modal hide fade" id="data_baru">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">TAMBAH REKENING</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="baru_data" method="POST" action="{{ route('rekening_layanan_baru') }}">
    @csrf

      <div class="control-group">
        <label class="control-label span3">Nama Rekening</label>
        <div class="controls span7">
          <input type="text" class="form-control" name="nama" required autofocus>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Level</label>
        <div class="controls span4">
          <select class="form-control" name="level" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Individu</label>
        <div class="controls span4">
          <select class="form-control" name="individu">
            <option value="0">TIDAK</option>
            <option value="1">YA</option>
          </select>
        </div>
      </div>          

      <div class="control-group">
        <label class="control-label span3">Kelompok</label>
        <div class="controls span4">
          <select class="form-control" name="kelompok">
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
    <h4 class="modal-title">EDIT REKENING</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_data" method="POST" action="{{ route('rekening_layanan_edit') }}">
    @csrf
      <input type="hidden" name="id" id="id_edit">

      <div class="control-group">
        <label class="control-label span3">Nama Rekening</label>
        <div class="controls span7">
          <input type="text" class="form-control" name="nama" id="edit_nama" required autofocus>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Level</label>
        <div class="controls span4">
          <select class="form-control" name="level" id="edit_level" required>
            <option value="1" {{ $rek->level == '1'? 'selected' : null }}>1</option>
            <option value="2" {{ $rek->level == '2'? 'selected' : null }}>2</option>
            <option value="3" {{ $rek->level == '3'? 'selected' : null }}>3</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Individu</label>
        <div class="controls span4">
          <select class="form-control" name="individu" id="edit_individu">
            <option value="0" {{ $rek->individu == '0'? 'selected' : null }}>TIDAK</option>
            <option value="1" {{ $rek->individu == '1'? 'selected' : null }}>YA</option>
          </select>
        </div>
      </div>          

      <div class="control-group">
        <label class="control-label span3">Kelompok</label>
        <div class="controls span4">
          <select class="form-control" name="kelompok" id="edit_kelompok">
            <option value="0" {{ $rek->kelompok == '0'? 'selected' : null }}>TIDAK</option>
            <option value="1" {{ $rek->kelompok == '1'? 'selected' : null }}>YA</option>
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
          url : "{{route('rekening_layanan_edit_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#id_edit').val(data.id);
            $('#edit_nama').val(data.nama);
            $('#edit_level').val(data.level);
            $('#edit_individu').val(data.individu);
            $('#edit_kelompok').val(data.kelompok);
            $('#modal_data_edit').modal('show');
          }
        });
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