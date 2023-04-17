@extends('mobile.layouts.content')

@section('bawah')
  <li><a href="{{ route('jasa_remun') }}"><i class="fa fa-heart"></i>Remunerasi</a></li>
  <li><a href="{{ route('informasi_software') }}"><i class="fa fa-info"></i>Informasi</a></li>
@endsection

@section('content')
<div class="row isi">
  <div class="container-fluid" style="margin-top: 9vh;">
    <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#data_baru">
      TAMBAH
    </button>
  </div>
</div>

@include('mobile.layouts.pesan')

<div class="row isi" style="padding-bottom: 10vh;">
  <div class="container-fluid">
    <div class="card" style="margin-top: 1vh;">
      <div class="card-body" style="font-size: 3vw;"> 
        <table width="100%" class="table table-striped table-sm table-bordered">
          <thead>
            <th></th>
            <th>Jenis Pasien</th>
          </thead>
          <tbody>
            @foreach($jenis as $jns)
            <tr>
              <td class="min">
                <div class="btn-group btn-group-xs">
                  <button class="btn btn-info btn-xs edit" title="Edit" data-toggle="modal" data-id="{{ $jns->id }}">
                    <i class="fa fa-edit"></i>
                  </button>
                  <a href="{{ route('jenis_pasien_hapus',Crypt::encrypt($jns->id)) }}" class="btn btn-info btn-xs" title="Hapus" onclick="return confirm('Hapus jenis pasien {{ $jns->jenis }} ?')">
                    <i class="fa fa-trash"></i>
                  </a>
                </div>
              </td>
              <td>{{ $jns->jenis }}</td>                            
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div style="margin-top: 1vh;">
    <div class="pull-left" style="font-size: 12px;">
      {{ number_format($jenis->firstItem(),0) }} - {{ number_format($jenis->lastItem(),0) }} dari {{ number_format($jenis->total(),0) }} data
    </div>                               
    <div class="pagination pagination-small pull-right">
      {!! $jenis->appends(request()->input())->render("pagination::bootstrap-4"); !!}
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
        <form class="form-horizontal fprev" id="baru_data" method="POST" action="{{ route('jenis_pasien_baru') }}">
        @csrf

          <div class="mb-1">
            <div class="title mb-1">Jenis Pasien</div>
            <input type="text" class="form-control" name="jenis" required autofocus>
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
        <form class="form-horizontal fprev" id="form_edit_data" method="POST" action="{{ route('jenis_pasien_edit') }}">
        @csrf
          <input type="hidden" name="id" id="edit_id">

          <div class="mb-1">
            <div class="title mb-1">Jenis Pasien</div>
            <input type="text" class="form-control" name="jenis" id="edit_jenis" required autofocus>
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
          url : "{{route('jenis_pasien_edit_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#edit_id').val(data.id);
            $('#edit_jenis').val(data.jenis);
            $('#modal_data_edit').modal('show');
          }
        });
      });
    });
  </script>
@endsection