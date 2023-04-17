@extends('layouts.content')
@section('title','Rincian Claim Asuransi')

@section('content')
<div class="content">
  
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
        scrollY:        "400px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,        
        stateSave: true,
        sort: false,
        searching: false,
      });
    });    
  </script>
@endsection