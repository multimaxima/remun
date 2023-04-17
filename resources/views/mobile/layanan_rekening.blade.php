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
  <div class="container-fluid">
    <div class="card" style="margin-top: 1vh;">
      <div class="card-body" style="font-size: 3vw;"> 
        <table width="100%" class="table table-sm table-striped table-bordered">
          <thead>
            <th></th>
            <th>Rekening</th>
            <th>Individu</th>
            <th>Kelompok</th>
          </thead>
          <tbody>
            @foreach($rekening as $rek)
            <tr>
              <td class="min">
                <div class="btn-group btn-group-xs">
                  <button class="btn btn-info btn-xs edit" title="Edit" data-bs-toggle="modal" data-id="{{ $rek->id }}">
                    <i class="fa fa-edit"></i>
                  </button>

                  <a href="{{ route('rekening_layanan_hapus',Crypt::encrypt($rek->id)) }}" class="btn btn-info btn-xs" title="Hapus" onclick="return confirm('Hapus rekening {{ $rek->nama }} ?')">
                    <i class="fa fa-trash"></i>
                  </a>
                </div>
              </td>
              <td>{{ $rek->nama }}</td>
              <td style="text-align: center;">
                @if($rek->individu == 1)
                  <i class="fa fa-check"></i>
                @endif
              </td>
              <td style="text-align: center;">
                @if($rek->kelompok == 1)
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
            {{ number_format($rekening->firstItem(),0) }} - {{ number_format($rekening->lastItem(),0) }} dari {{ number_format($rekening->total(),0) }} data
          </div>                               
          <div class="pagination pagination-sm pull-right">
            {!! $rekening->appends(request()->input())->render("pagination::bootstrap-4"); !!}
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
        <form class="form-horizontal fprev" id="baru_data" method="POST" action="{{ route('rekening_layanan_baru') }}">
        @csrf

          <div class="mb-1">
            <div class="title mb-1">Nama Rekening</div>
            <input type="text" class="form-control" name="nama" required autofocus>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Individu</div>
            <select class="form-control" name="individu">
              <option value="0">TIDAK</option>
              <option value="1">YA</option>
            </select>
          </div>          

          <div class="mb-1">
            <div class="title mb-1">Kelompok</div>
            <select class="form-control" name="kelompok">
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
        <form class="form-horizontal fprev" id="form_edit_data" method="POST" action="{{ route('rekening_layanan_edit') }}">
        @csrf
          <input type="hidden" name="id" id="id_edit">

          <div class="mb-1">
            <div class="title mb-1">Nama Rekening</div>
            <input type="text" class="form-control" name="nama" id="edit_nama" required autofocus>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Individu</div>
            <select class="form-control" name="individu" id="edit_individu">
              <option value="0" {{ $rek->individu == '0'? 'selected' : null }}>TIDAK</option>
              <option value="1" {{ $rek->individu == '1'? 'selected' : null }}>YA</option>
            </select>
          </div>          

          <div class="mb-1">
            <div class="title mb-1">Kelompok</div>
            <select class="form-control" name="kelompok" id="edit_kelompok">
              <option value="0" {{ $rek->kelompok == '0'? 'selected' : null }}>TIDAK</option>
              <option value="1" {{ $rek->kelompok == '1'? 'selected' : null }}>YA</option>
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
          url : "{{route('rekening_layanan_edit_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#id_edit').val(data.id);
            $('#edit_nama').val(data.nama);
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
      $('#tabel').DataTable( {        
        searching: false,
        paging: false,
        sort: false,
      });
    });
  </script>
@endsection