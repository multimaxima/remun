@extends('layouts.content')
@section('title','Pengumuman')

@section('content')
<div class="content">
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
    <thead>
      <th></th>
      <th style="text-align: center;">No.</th>
      <th style="text-align: center;">Pengumuman</th>
      <th style="text-align: center;">Oleh</th>
      <th style="text-align: center;">Tanggal</th>
    </thead>
    <tbody>
      <?php $no = 0;?>
      @foreach($umum as $umm)
      <?php $no++ ;?>
      <tr>
        <td class="min">
          <div class="btn-group">
            <a href="{{ route('pengumuman_detil',Crypt::encrypt($umm->id)) }}" title="Buka" class="btn btn-info btn-mini">
              <i class="icon-edit"></i>
            </a>
          </div>
        </td>
        <td class="min" style="text-align: right;">{{ $no }}.</td>
        <td>{{ $umm->judul }}</td>
        <td class="min">{{ $umm->nama }}</td>
        <td class="min" style="text-align: center;">{{ $umm->created_at }}</td>
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
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        sort: false,
      });
    });
  </script>
@endsection