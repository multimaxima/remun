@extends('layouts.content')
@section('title','Database')

@section('content')
<div class="content" style="max-height: 80vh;">
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 13px;">
    <thead>
      <th></th>
      <th>Nama Tabel</th>
    </thead>
    <tbody>      
      @foreach($tables as $tabel)      
      <tr>
        <td class="min">
          <div class="btn-group">
            <a href="#" class="btn btn-info btn-mini">
              <i class="icon-check"></i>
            </a>
          </div>
        </td>
        <td>{{ $tabel }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
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
        stateSave: true,
      });
    });
  </script>
@endsection