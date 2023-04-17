@extends('layouts.content')
@section('title','Pembayaran Pasien')

@section('content')
<div class="content">
  <table width="100%" class="table table-hover table-striped" id="tabel" style="font-size: 13px;">
    <thead>
      <th></th>
      <th>REGISTER</th>
      <th>NO. MR</th>
      <th width="130">JENIS PASIEN</th>
      <th>NAMA PASIEN</th>
      <th>ALAMAT</th>
      <th width="70">UMUR</th>
      <th>RUANG</th>
      <th>TAGIHAN</th>
    </thead>
    <tbody>
      @foreach($pasien as $pas)
      <tr>
        <td class="min">
          <a href="{{ route('pasien_upp_verifikasi',Crypt::encrypt($pas->id)) }}" class="btn btn-warning btn-mini" title="Verifikasi Pembayaran" onclick="return confirm('Apakah tagihan pasien {{ $pas->nama }} sudah benar ?')">
            <i class="icon-ok"></i>
          </a>

          <a href="{{ route('pasien_upp_revisi',Crypt::encrypt($pas->id)) }}" class="btn btn-primary btn-mini" title="Rincian Pembayaran">
            <i class="fa fa-edit"></i>
          </a>
        </td>
        <td class="min">{{ $pas->register }}</td>
        <td>{{ $pas->no_mr }}</td>
        <td>{{ strtoupper($pas->jenis_pasien) }}</td>
        <td>{{ strtoupper($pas->nama) }}</td>
        <td>{{ strtoupper($pas->alamat) }}</td>
        <td style="text-align: center;">
          {{ $pas->umur_thn }} Thn.
          @if($pas->umur_bln)
            {{ $pas->umur_bln }} Bln.
          @endif
        </td>
        <td>{{ $pas->ruang }}</td>
        <td style="text-align: right;">{{ number_format($pas->tagihan,0) }}</td>
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
        "stateSave": true,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        "order": [[ 1, "asc" ]],        
      });
    });
  </script>
@endsection