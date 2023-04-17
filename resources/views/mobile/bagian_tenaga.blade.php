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
            <th>Tenaga</th>
          </thead>
          <tbody>
            @foreach($tenaga as $ten)
            <tr>
              <td class="min">
                <div class="btn-group btn-group-xs">
                  <button class="btn btn-info btn-xs edit_tenaga" title="Edit" data-bs-toggle="modal" data-id="{{ $ten->id }}">
                    <i class="fa fa-edit"></i>
                  </button>
                  <a href="{{ route('bagian_tenaga_hapus',Crypt::encrypt($ten->id)) }}" class="btn btn-info btn-xs" title="Hapus" onclick="return confirm('Hapus tenaga ?')">
                    <i class="fa fa-trash"></i>
                  </a>
                </div>
              </td>
              <td>{{ $ten->tenaga }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
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
        <form class="form-horizontal fprev" id="form_tenaga_baru" method="POST" action="{{ route('bagian_tenaga_baru') }}">
        @csrf

          <div class="mb-1">
            <div class="title mb-1">Tenaga</div>
            <input type="text" class="form-control" name="tenaga" required autofocus>
          </div>
        </form>
      </div>
      <div class="modal-footer">   
        <div class="btn-group">             
          <button type="submit" form="form_tenaga_baru" class="btn btn-secondary btn-sm bprev">SIMPAN</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_edit_tenaga" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Edit Data</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal fprev" id="form_edit_tenaga" method="POST" action="{{ route('bagian_tenaga_edit') }}">
        @csrf
          <input type="hidden" name="id" id="edit_id_tenaga">

          <div class="mb-1">
            <div class="title mb-1">Tenaga</div>
            <input type="text" class="form-control" name="tenaga" id="edit_tenaga" required autofocus>
          </div>
        </form>
      </div>
      <div class="modal-footer"> 
        <div class="btn-group">               
          <button type="submit" form="form_edit_tenaga" class="btn btn-secondary btn-sm bprev">SIMPAN</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('.edit_tenaga').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{route('bagian_tenaga_edit_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#edit_id_tenaga').val(data.id);
            $('#edit_tenaga').val(data.tenaga);
            $('#modal_edit_tenaga').modal('show');
          }
        });
      });
    });
  </script>
@endsection