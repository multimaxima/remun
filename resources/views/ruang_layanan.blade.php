@extends('layouts.content')
@section('title','Layanan Ruang '.ucwords(strtolower($ruang->ruang)))

@section('judul')
  <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#data_baru">
    <i class="fa fa-plus"></i> TAMBAH DATA
  </a>
  <a href="{{ route('ruang') }}" class="btn btn-danger float-right" style="margin-right: 3px;">
    <i class="fa fa-chevron-left"></i> KEMBALI
  </a>
  <h4 class="page-title"> <i class="dripicons-pulse"></i> @yield('title')</h4>
@endsection

@section('content')
<div class="wrapper">
  <div class="col-8 offset-2" style="padding: 0 50px;">
    <div class="card m-b-30 konten">
      <div class="card-body">
        <table width="100%" id="tabel" class="table table-hover table-striped">
          <thead>
            <th></th>
            <th>Layanan</th>
          </thead>
          <tbody>
            @foreach($d_layanan as $layan)
<div class="modal fade" id="data_edit{{ $layan->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Data Layanan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal fprev" id="edit_data{{ $layan->id }}" method="POST" action="{{ route('ruang_layanan_edit') }}">
        @csrf
          <input type="hidden" name="id" value="{{ Crypt::encrypt($layan->id) }}">

          <div class="form-group row">
            <div class="col-12">
              <select class="form-control" size="10" name="id_jasa">
                @foreach($layanan as $lay)
                <option value="{{ $lay->id }}" {{ $layan->id_jasa == $lay->id? 'selected' : null }}>{{ $lay->jasa }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">                
        <button type="submit" form="edit_data{{ $layan->id }}" class="btn btn-primary bprev">
          <i class="fa fa-save"></i> SIMPAN
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fa fa-times"></i> TUTUP
        </button>
      </div>
    </div>
  </div>
</div>                
            <tr>
              <td class="min">
                <a href="#" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#data_edit{{ $layan->id }}" title="Edit">
                  <i class="fa fa-edit"></i>
                </a>

                <a href="{{ route('ruang_layanan_hapus',Crypt::encrypt($layan->id)) }}" class="btn btn-danger btn-xs" title="Hapus" onclick="return confirm('Hapus jasa ?')">
                  <i class="fa fa-times"></i>
                </a>
              </td>
              <td>{{ $layan->jasa }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>      
    </div>
  </div>
</div>

<div class="modal fade" id="data_baru" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Layanan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal fprev" id="baru_data" method="POST" action="{{ route('ruang_layanan_baru') }}">
        @csrf
          <input type="hidden" name="id_ruang" value="{{ $ruang->id }}">

          <div class="form-group row">
            <div class="col-12">
              <select class="form-control" size="10" name="id_jasa">
                @foreach($layanan as $lay)
                <option value="{{ $lay->id }}">{{ $lay->jasa }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">                
        <button type="submit" form="baru_data" class="btn btn-primary bprev">
          <i class="fa fa-save"></i> SIMPAN
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fa fa-times"></i> TUTUP
        </button>
      </div>
    </div>
  </div>
</div>     
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('#tabel').DataTable( {
        "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": 0
        }],
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        "order": [[ 1, "asc" ]],
      });
    });
  </script>
@endsection