@extends('layouts.content')
@section('title','Aktifasi Menu')

@section('judul')
  <h4 class="page-title"> <i class="dripicons-gear"></i> @yield('title')</h4>
@endsection

@section('content')
<div class="content">
  <table width="100%" class="table table-hover table-striped">
    <thead>
      <th>HAK AKSES</th>
      <th style="text-align: center;">EDIT MEDIS</th>
      <th style="text-align: center;">EDIT PERAWAT</th>
      <th style="text-align: center;">EDIT ADMIN</th>
      <th style="text-align: center;">EDIT STAF</th>
      <th style="text-align: center;">EDIT KOMULATIF</th>
      <th style="text-align: center;">EDIT PENYESUAIAN</th>
      <th></th>
    </thead>
    <tbody>
      @foreach($akses as $aks)            
      <tr>
        <form method="POST" action="{{ route('menu_simpan') }}">
        @csrf
        <input type="hidden" name="id" value="{{ $aks->id }}">
        <td>{{ $aks->akses }}</td>
        <td width="150" style="text-align: center;">                 
          <input type="hidden" name="rem_medis" value="0"> 
          <input type="checkbox" id="switch1{{ $aks->id }}" name="rem_medis" value="1" @if($aks->rem_medis == 1) checked @endif switch="none">
          <label for="switch1{{ $aks->id }}" data-on-label="On" data-off-label="Off"></label>
        </td>
        <td width="150" style="text-align: center;">
          <input type="hidden" name="rem_perawat" value="0"> 
          <input type="checkbox" id="switch2{{ $aks->id }}" name="rem_perawat" value="1" @if($aks->rem_perawat == 1) checked @endif switch="none">                
          <label for="switch2{{ $aks->id }}" data-on-label="On" data-off-label="Off"></label>
        </td>
        <td width="150" style="text-align: center;">
          <input type="hidden" name="rem_admin" value="0"> 
          <input type="checkbox" id="switch3{{ $aks->id }}" name="rem_admin" value="1" @if($aks->rem_admin == 1) checked @endif switch="none">
          <label for="switch3{{ $aks->id }}" data-on-label="On" data-off-label="Off"></label>
        </td>
        <td width="150" style="text-align: center;">
          <input type="hidden" name="rem_staf" value="0"> 
          <input type="checkbox" id="switch4{{ $aks->id }}" name="rem_staf" value="1" @if($aks->rem_staf == 1) checked @endif switch="none">                
          <label for="switch4{{ $aks->id }}" data-on-label="On" data-off-label="Off"></label>
        </td>
        <td width="150" style="text-align: center;">
          <input type="hidden" name="komulatif" value="0"> 
          <input type="checkbox" id="switch5{{ $aks->id }}" name="komulatif" value="1" @if($aks->komulatif == 1) checked @endif switch="none">                
          <label for="switch5{{ $aks->id }}" data-on-label="On" data-off-label="Off"></label>
        </td>
        <td width="150" style="text-align: center;">
          <input type="hidden" name="penyesuaian" value="0"> 
          <input type="checkbox" id="switch6{{ $aks->id }}" name="penyesuaian" value="1" @if($aks->penyesuaian == 1) checked @endif switch="none">                
          <label for="switch6{{ $aks->id }}" data-on-label="On" data-off-label="Off"></label>
        </td>
        <td class="min">
          <button class="btn btn-primary btn-sm" type="submit">SIMPAN</button>
        </td>
        </form>
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
      });
    });
  </script>
@endsection