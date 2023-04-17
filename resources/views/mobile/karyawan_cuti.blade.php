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

<div style="padding-bottom: 10vh;">
@foreach($cuti as $cuti)
<div class="row isi">
  <div class="container">
    <div class="card" style="margin-top: 1vh;">
      <div class="card-body" style="font-size: 3vw;">   
        <label style="font-size: 4vw; font-weight: bold;">{{ $cuti->nama }}</label>
        <table width="100%" class="table table-striped table-sm table-bordered">
          <tr>
            <td width="30%">Mulai</td>
            <td>{{ strtoupper($cuti->tgl_awal) }}</td>
          </tr>
          <tr>
            <td>Sampai</td>
            <td>{{ strtoupper($cuti->tgl_akhir) }}</td>
          </tr>
          <tr>
            <td>Keterangan</td>
            <td>{{ strtoupper($cuti->keterangan) }}</td>
          </tr>
        </table>
        <div class="btn-group">
          <a href="#" class="btn btn-info btn-sm edit" title="Edit Data" data-toggle="modal" data-id="{{ $cuti->id }}">
            EDIT
          </a>
          <a href="{{ route('karyawan_cuti_hapus',Crypt::encrypt($cuti->id)) }}" class="btn btn-info btn-sm" title="Hapus Data" onclick="return confirm('Hapus data cuti {{ $cuti->nama }} ?')">
            HAPUS
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endforeach
</div>

<div class="modal fade" id="data_baru" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Tambah Data</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal container-fluid fprev" id="baru_data" method="POST" action="{{ route('karyawan_cuti_baru') }}">
        @csrf

          <div class="mb-1">
            <div class="title mb-1">Nama Karyawan</div>
            <select class="form-control" name="id_users" autofocus required style="width: 104%;">
              <option value=""></option>
              @foreach($karyawan as $kary)
                <option value="{{ $kary->id }}">{{ $kary->nama }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Cuti Dari</div>
            <input type="date" class="form-control" name="awal" required>
          </div>
          
          <div class="mb-1">
            <div class="title mb-1">Sampai</div>
            <input type="date" class="form-control" name="akhir" required>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Keterangan</div>
            <input type="text" class="form-control" name="keterangan" required>
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

<div class="modal fade" id="data_edit" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Edit Data</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal container-fluid fprev" id="edit_data" method="POST" action="{{ route('karyawan_cuti_edit') }}">
        @csrf
          <input type="hidden" name="id" id="edit_id">

          <div class="mb-1">
            <div class="title mb-1">Nama Karyawan</div>
            <select class="form-control" name="id_users" id="edit_id_users" autofocus required style="width: 104%;">
              <option value=""></option>
              @foreach($karyawan as $kary)
                <option value="{{ $kary->id }}" {{ $cuti->id_users == $kary->id? 'selected' : null }}>{{ $kary->nama }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Cuti Dari</div>
            <input type="date" class="form-control" name="awal" id="edit_awal" required>
          </div>
          
          <div class="mb-1">
            <div class="title mb-1">Sampai</div>
            <input type="date" class="form-control" name="akhir" id="edit_akhir" required>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Keterangan</div>
            <input type="text" class="form-control" name="keterangan" id="edit_keterangan" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">                
        <div class="btn-group">
          <button type="submit" form="edit_data" class="btn btn-secondary btn-sm bprev">SIMPAN</button>
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
          url : "{{route('karyawan_cuti_edit_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#edit_id').val(data.id);
            $('#edit_id_users').val(data.id_users);
            $('#edit_awal').val(data.awal);
            $('#edit_akhir').val(data.akhir);
            $('#edit_keterangan').val(data.keterangan);
            $('#data_edit').modal('show');
          }
        });
      });
    });
  </script>
@endsection