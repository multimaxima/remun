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
<div class="row isi">
  <div class="container-fluid">
    <div class="card" style="margin-top: 1vh;">
      <div class="card-body" style="font-size: 3vw;">        
        <table width="100%" class="table table-sm table-striped table-bordered">
          <thead>
            <th></th>
            <th>Nama Bank</th>
            <th>Cabang</th>
          </thead>
          <tbody>
            @foreach($bank as $bnk)
            <tr>
              <td class="min">
                <div class="btn-group btn-group-xs" role="group">
                  <button class="btn btn-info btn-xs edit" title="Edit" data-bs-toggle="modal" data-id="{{ $bnk->id }}">
                    <i class="fa fa-edit"></i>
                  </button>
                  <a href="{{ route('bank_hapus',Crypt::encrypt($bnk->id)) }}" class="btn btn-info btn-xs" title="Hapus" onclick="return confirm('Hapus bank {{ $bnk->bank }} ?')">
                    <i class="fa fa-trash"></i>
                  </a>
                </div>
              </td>
              <td>{{ strtoupper($bnk->bank) }}</td>
              <td>{{ strtoupper($bnk->cabang) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="data_baru" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Tambah Data</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal fprev" id="baru_data" method="POST" action="{{ route('bank_baru') }}">
        @csrf

          <div class="mb-1">
            <div class="title mb-1">Nama Bank</div>
            <input type="text" class="form-control" name="bank" required autofocus>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Cabang</div>
            <input type="text" class="form-control" name="cabang">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="submit" form="baru_data" class="btn btn-secondary btn-sm bprev">SIMPAN</button>
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">TUTUP</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_data_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Edit Data</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal fprev" id="form_edit_data" method="POST" action="{{ route('bank_edit') }}" style="font-size: 3vw;">
        @csrf
          <input type="hidden" name="id" id="edit_id">

          <div class="mb-1">
            <div class="title mb-1">Nama Bank</div>
            <input type="text" class="form-control" name="bank" id="edit_bank" required autofocus>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Cabang</div>
            <input type="text" class="form-control" name="cabang" id="edit_cabang">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="submit" form="form_edit_data" class="btn btn-secondary btn-sm bprev">SIMPAN</button>
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">TUTUP</button>
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
          url : "{{route('bank_edit_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#edit_id').val(data.id);
            $('#edit_bank').val(data.bank);
            $('#edit_cabang').val(data.cabang);
            $('#modal_data_edit').modal('show');
          }
        });
      });
    });
  </script>
@endsection