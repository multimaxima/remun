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
        <table width="100%" id="tabel" class="table table-sm table-hover table-striped table-bordered">
          <thead>
            <tr>
              <th rowspan="2"></th>
              <th rowspan="2" style="text-align: center;" valign="middle">Jenis Absensi</th>
              <th colspan="4" style="text-align: center;" valign="middle">Perhitungan Indeks</th>
            </tr>      
            <tr>
              <th style="text-align: center;" valign="middle">Pos Remun</th>
              <th style="text-align: center;" valign="middle">Staf Direksi</th>
              <th style="text-align: center;" valign="middle">Admin</th>
              <th style="text-align: center;" valign="middle">JP Medis</th>
            </tr>
          </thead>
          <tbody>
            @foreach($absensi as $abs)                
            <tr>
              <td class="min">
                <div class="btn-group btn-group-xs">
                  <button class="btn btn-info btn-xs edit" title="Edit" data-toggle="modal" data-id="{{ $abs->id }}">
                    <i class="fa fa-edit"></i>
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
      </div>
    </div>
    <div style="margin-top: 1vh; font-size: 3vw;">
      <div class="pull-left">
        {{ number_format($absensi->firstItem(),0) }} - {{ number_format($absensi->lastItem(),0) }} dari {{ number_format($absensi->total(),0) }} data
      </div>                               
      <div class="pagination pagination-small pull-right">
        {!! $absensi->appends(request()->input())->render("pagination::bootstrap-4"); !!}
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="data_baru" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Tambah Jenis Absensi</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal fprev" id="form_baru" method="POST" action="{{ route('absensi_baru') }}">
        @csrf

          <div class="mb-1">
            <div class="title mb-1">Jenis</div>
            <input type="text" class="form-control" name="absen" required autofocus>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Pos Remun</div>
            <select class="form-control" name="indeks" required>
              <option value="0">TIDAK DIHITUNG</option>
              <option value="1">DIHITUNG</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Staf Direksi</div>
            <select class="form-control" name="staf" required>
              <option value="0">TIDAK DIHITUNG</option>
              <option value="1">DIHITUNG</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Administrasi</div>
            <select class="form-control" name="administrasi" required>
              <option value="0">TIDAK DIHITUNG</option>
              <option value="1">DIHITUNG</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">JP Medis</div>
            <select class="form-control" name="jasa" required>
              <option value="0">TIDAK DIHITUNG</option>
              <option value="1">DIHITUNG</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="submit" form="form_baru" class="btn btn-secondary btn-sm bprev">SIMPAN</button>
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">TUTUP</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_edit" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Edit Jenis Absensi</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal fprev" id="form_edit" method="POST" action="{{ route('absensi_simpan') }}">
        @csrf
          <input type="hidden" name="id" id="edit_id_absensi">

          <div class="mb-1">
            <div class="title mb-1">Jenis</div>
            <input type="text" class="form-control" name="absen" id="edit_absen" required autofocus>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Pos Remun</div>
            <select class="form-control" name="indeks" id="edit_indeks" required>
              <option value="0">TIDAK DIHITUNG</option>
              <option value="1">DIHITUNG</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Staf Direksi</div>
            <select class="form-control" name="staf" id="edit_staf" required>
              <option value="0">TIDAK DIHITUNG</option>
              <option value="1">DIHITUNG</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Administrasi</div>
            <select class="form-control" name="administrasi" id="edit_administrasi" required>
              <option value="0">TIDAK DIHITUNG</option>
              <option value="1">DIHITUNG</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">JP Medis</div>
            <select class="form-control" name="jasa" id="edit_jasa" required>
              <option value="0">TIDAK DIHITUNG</option>
              <option value="1">DIHITUNG</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="submit" form="form_edit" class="btn btn-secondary btn-sm bprev">SIMPAN</button>
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">TUTUP</button>
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