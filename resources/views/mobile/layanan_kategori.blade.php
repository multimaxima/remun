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
    <div class="card" style="margin-top: 1vh;">
      <div class="card-body" style="font-size: 3vw;">  
        <table width="100%" class="table table-sm table-striped table-bordered">
          <thead>
            <th></th>
            <th>Kategori</th>
          </thead>
          <tbody>
            @foreach($kategori as $kat)              
            <tr>
              <td class="min">
                <div class="btn-group btn-group-xs">
                  <button class="btn btn-info btn-xs edit" title="Edit" data-toggle="modal" data-id="{{ $kat->id }}">
                    <i class="fa fa-edit"></i>
                  </button>
                  <a href="{{ route('kategori_layanan_hapus',Crypt::encrypt($kat->id)) }}" class="btn btn-info btn-xs" title="Hapus" onclick="return confirm('Hapus kategori ?')">
                    <i class="fa fa-trash"></i>
                  </a>
                </div>
              </td>
              <td>{{ $kat->kategori }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div style="margin-top: 1vh; font-size: 3vw;">
          <div class="pull-left">
            {{ number_format($kategori->firstItem(),0) }} - {{ number_format($kategori->lastItem(),0) }} dari {{ number_format($kategori->total(),0) }} data
          </div>                               
          <div class="pagination pagination-sm pull-right">
            {!! $kategori->appends(request()->input())->render("pagination::bootstrap-4"); !!}
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
        <form class="form-horizontal fprev" id="form_baru_data" method="POST" action="{{ route('kategori_layanan_baru') }}">
        @csrf

          <div class="mb-1">
            <div class="title mb-1">Kategori</div>
            <textarea class="form-control" name="kategori" required autofocus style="height: 100px;"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">         
        <div class="btn-group">       
          <button type="submit" form="form_baru_data" class="btn btn-secondary btn-sm bprev">SIMPAN</button>
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
        <form class="form-horizontal fprev" id="form_edit_data" method="POST" action="{{ route('kategori_layanan_edit') }}">
        @csrf
          <input type="hidden" name="id" id="edit_id">

          <div class="mb-1">
            <div class="title mb-1">Kategori</div>
            <textarea class="form-control" name="kategori" id="edit_kategori" required autofocus style="height: 100px;"></textarea>
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
          url : "{{route('kategori_layanan_edit_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#edit_id').val(data.id);
            $('#edit_kategori').val(data.kategori);
            $('#modal_data_edit').modal('show');
          }
        });
      });
    });
  </script>
@endsection