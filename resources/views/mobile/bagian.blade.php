@extends('mobile.layouts.content')

@section('bawah')
  <li><a href="{{ route('jasa_remun') }}"><i class="fa fa-heart"></i>Remunerasi</a></li>
  <li><a href="{{ route('informasi_software') }}"><i class="fa fa-info"></i>Informasi</a></li>
@endsection

@section('content')
<div class="row isi">
  <div class="container" style="margin-top: 9vh;">
    <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#data_baru">
      TAMBAH
    </button>
  </div>
</div>

@include('mobile.layouts.pesan')

<div class="row isi" style="padding-bottom: 10vh;">
  <div class="container">
    @foreach($bagian as $bag)
    <div class="card" style="margin-top: 1vh;">
      <div class="card-body" style="font-size: 3vw;">         
        <table width="100%" class="table table-sm table-bordered">
          <tr>
            <td width="40%">Tenaga</td>
            <td>{{ $bag->tenaga }}</td>
          </tr>
          <tr>
            <td>Bagian</td>
            <td>{{ $bag->bagian }}</td>
          </tr>
          <tr>
            <td>Kelompok Perawat</td>
            <td>
              @if($bag->kel_perawat == 1)
                <i class="fa fa-check"></i>
              @endif                    
            </td>
          </tr>
          <tr>
            <td>Staf Direksi</td>
            <td>
              @if($bag->direksi == 1)
                <i class="fa fa-check"></i>
              @endif
            </td>
          </tr>
          <tr>
            <td>Administrasi</td>
            <td>
              @if($bag->administrasi == 1)
                <i class="fa fa-check"></i>
              @endif
            </td>
          </tr>
          <tr>
            <td>Rekening Jasa Medis</td>
            <td>{{ $bag->nama }}</td>
          </tr>
        </table>
        <div class="btn-group">
          <button class="btn btn-info btn-sm edit_bagian" title="Edit" data-toggle="modal" data-id="{{ $bag->id }}">
            EDIT
          </button>
          <a href="{{ route('bagian_hapus',Crypt::encrypt($bag->id)) }}" class="btn btn-info btn-sm" title="Hapus" onclick="return confirm('Hapus tenaga bagian ?')">
            HAPUS
          </a>
        </div>
      </div>
    </div>
    @endforeach

    <div style="margin-top: 1vh; font-size: 3vw;">
      <div class="pull-left">
        {{ number_format($bagian->firstItem(),0) }} - {{ number_format($bagian->lastItem(),0) }} dari {{ number_format($bagian->total(),0) }} data
      </div>                               
      <div class="pagination pagination-sm pull-right">
        {!! $bagian->appends(request()->input())->render("pagination::bootstrap-4"); !!}
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="data_baru" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Tambah Data</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal fprev" id="form_bagian_baru" method="POST" action="{{ route('bagian_baru') }}">
        @csrf

          <div class="mb-1">
            <div class="title mb-1">Tenaga</div>
            <select class="form-control" name="id_tenaga" required autofocus>
              <option value=""></option>
              @foreach($tenaga as $ten)
              <option value="{{ $ten->id }}">{{ $ten->tenaga }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Bagian</div>
            <input type="text" class="form-control" name="bagian" required>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Indeks Kel. Perawat</div>
            <select class="form-control" name="insentif_perawat" required>
              <option value="0">TIDAK</option>
              <option value="1">YA</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Indeks Staf Direksi</div>
            <select class="form-control" name="direksi" required>
              <option value="0">TIDAK</option>
              <option value="1">YA</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Indeks Administrasi</div>
            <select class="form-control" name="administrasi" required>
              <option value="0">TIDAK</option>
              <option value="1">YA</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Rekening Jasa Medis</div>
            <select class="form-control" name="id_rekening" required>
              <option value=""></option>
              @foreach($rekening as $rek)
              <option value="{{ $rek->id }}">{{ $rek->nama }}</option>
              @endforeach
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">   
        <div class="btn-group">             
          <button type="submit" form="form_bagian_baru" class="btn btn-secondary btn-sm bprev">SIMPAN</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_bagian_edit" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Edit Data</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal fprev" id="form_edit_bagian" method="POST" action="{{ route('bagian_edit') }}">
        @csrf
          <input type="hidden" name="id" id="edit_id_bagian">

          <div class="mb-1">
            <div class="title mb-1">Tenaga</div>
            <select class="form-control" name="id_tenaga" id="edit_id_tenaga_bagian" required autofocus>
              <option value=""></option>
              @foreach($tenaga as $ten)
                <option value="{{ $ten->id }}" {{ $bag->id_tenaga == $ten->id? 'selected' : null }}>{{ $ten->tenaga }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Bagian</div>
            <input type="text" class="form-control" name="bagian" id="edit_bagian" required>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Indeks Kel. Perawat</div>
            <select class="form-control" name="insentif_perawat" id="edit_insentif_perawat" required>
              <option value="0" {{ $bag->insentif_perawat == '0'? 'selected' : null }}>TIDAK</option>
              <option value="1" {{ $bag->insentif_perawat == '1'? 'selected' : null }}>YA</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Indeks Staf Direksi</div>
            <select class="form-control" name="direksi" id="edit_direksi" required>
              <option value="0" {{ $bag->direksi == '0'? 'selected' : null }}>TIDAK</option>
              <option value="1" {{ $bag->direksi == '1'? 'selected' : null }}>YA</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Indeks Administrasi</div>
            <select class="form-control" name="administrasi" id="edit_administrasi" required>
              <option value="0" {{ $bag->administrasi == '0'? 'selected' : null }}>TIDAK</option>
              <option value="1" {{ $bag->administrasi == '1'? 'selected' : null }}>YA</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Rekening Jasa Medis</div>
            <select class="form-control" name="id_rekening" id="edit_id_rekening" required>
              <option value=""></option>
              @foreach($rekening as $rek)
                <option value="{{ $rek->id }}" {{ $bag->id_rekening == $rek->id? 'selected' : null }}>{{ $rek->nama }}</option>
              @endforeach
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="submit" form="form_edit_bagian" class="btn btn-secondary btn-sm bprev">SIMPAN</button>
        </div>
      </div>
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
  </script>
@endsection