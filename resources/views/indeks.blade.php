@extends('layouts.content')
@section('title','Indeks Masa Kerja')

@section('content')
<div class="navbar">
  <div class="navbar-inner">
    <ul class="nav">

    </ul>
  </div>
</div>

<div class="content">
  @include('layouts.pesan')
  <table id="tabel" width="100%" class="table table-hover table-striped table-bordered">
    <thead>
      <tr>
        <th rowspan="2" style="padding: 0 10px;"></th>
        <th colspan="2" style="padding: 0 10px;">Masa Kerja (Th.)</th>
        <th rowspan="2" style="padding: 0 10px;">Indeks</th>
      </tr>
      <tr>
        <th style="padding: 0 10px;">Dari</th>
        <th style="padding: 0 10px;">Sampai</th>
      </tr>
    </thead>
    <tbody>
      @foreach($indeks as $indek)
      <tr>
        <td class="min">
          <button class="btn btn-info btn-mini edit" title="Edit" data-toggle="modal" data-id="{{ $indek->id }}">
            <i class="icon-edit"></i>
          </button>
        </td>
        <td style="text-align: center;">{{ $indek->dari }}</td>
        <td style="text-align: center;">{{ $indek->sampai }}</td>
        <td style="text-align: center;">{{ $indek->indeks }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>      

<div class="modal hide fade" id="data_edit">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">EDIT DATA</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal container-fluid fprev" id="edit_data" method="POST" action="{{ route('rumusan_indeks_simpan') }}">
    @csrf
      <input type="hidden" name="id" id="edit_id">

      <div class="control-group">
        <label class="control-label span5">Dari</label>
        <div class="controls span4">
          <input type="number" step="any" class="form-control" name="dari" id="edit_dari" required autofocus>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span5">Sampai</label>
        <div class="controls span4">
          <input type="number" step="any" class="form-control" name="sampai" id="edit_sampai" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span5">Indeks</label>
        <div class="controls span4">
          <input type="number" step="any" class="form-control" name="indeks" id="edit_indeks">
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">   
    <div class="btn-group">             
      <button type="submit" form="edit_data" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
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
          url : "{{route('rumusan_indeks_simpan_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#edit_id').val(data.id);
            $('#edit_dari').val(data.dari);
            $('#edit_sampai').val(data.sampai);
            $('#edit_indeks').val(data.indeks);
            $('#data_edit').modal('show');
          }
        });
      });
    });

    $(document).ready(function() {
      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.11*box.clientHeight);

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