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
    @foreach($jasa as $jas)
    <div class="card" style="margin-top: 1vh;">
      <div class="card-body" style="font-size: 3vw;">         
        <table width="100%" class="table table-sm table-bordered">
          <tr>
            <td width="20%">Jenis</td>
            <td>{{ $jas->jenis }}</td>
          </tr>
          <tr>
            <td>Jasa</td>
            <td>{{ $jas->jasa }}</td>
          </tr>
          <tr>
            <td>Operasi</td>
            <td>
              @if($jas->operasi == 1)
                YA
              @endif
            </td>
          </tr>
        </table>
        <div class="btn-group">
          <button class="btn btn-info btn-sm edit" title="Edit" data-toggle="modal" data-id="{{ $jas->id }}">
            EDIT
          </button>
          <a href="{{ route('jasa_layanan_hapus',Crypt::encrypt($jas->id)) }}" class="btn btn-info btn-sm" title="Hapus" onclick="return confirm('Hapus {{ $jas->jasa }} ?')">
            HAPUS
          </a>
        </div>
      </div>
    </div>
    @endforeach

    <div style="margin-top: 1vh; font-size: 3vw;">
          <div class="pull-left">
            {{ number_format($jasa->firstItem(),0) }} - {{ number_format($jasa->lastItem(),0) }} dari {{ number_format($jasa->total(),0) }} data
          </div>                               
          <div class="pagination pagination-sm pull-right">
            {!! $jasa->appends(request()->input())->render("pagination::bootstrap-4"); !!}
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
        <form class="form-horizontal fprev" id="baru_data" method="POST" action="{{ route('jasa_layanan_baru') }}">
        @csrf

          <div class="mb-1">
            <div class="title mb-1">Jenis</div>
            <input type="text" class="form-control" name="jenis" required autofocus>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Jasa</div>
            <input type="text" class="form-control" name="jasa" required>
          </div>          

          <div class="mb-1">
            <div class="title mb-1">Operasi</div>
            <select name="operasi" class="form-control">
              <option value="0">TIDAK</option>
              <option value="1">YA</option>
            </select>
          </div>          
        </form>
      </div>
      <div class="modal-footer">         
        <div class="btn-group">       
          <button type="submit" form="baru_data" class="btn btn-secondary btn-sm bprev">SIMPAN</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_data_edit" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Edit Data</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal fprev" id="form_edit_data" method="POST" action="{{ route('jasa_layanan_edit') }}">
        @csrf
          <input type="hidden" name="id" id="edit_id">

          <div class="mb-1">
            <div class="title mb-1">Jenis</div>
            <input type="text" class="form-control" name="jenis" id="edit_jenis" required autofocus>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Jasa</div>
            <input type="text" class="form-control" name="jasa" id="edit_jasa" required>
          </div>     

          <div class="mb-1">
            <div class="title mb-1">Operasi</div>
            <select name="operasi" id="edit_operasi" class="form-control">
              <option value="0">TIDAK</option>
              <option value="1">YA</option>
            </select>
          </div>               
        </form>
      </div>
      <div class="modal-footer">                
        <div class="btn-group">
          <button type="submit" form="form_edit_data" class="btn btn-secondary btn-sm bprev">SIMPAN</button>
        </div>
      </div>
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
  </script>
@endsection