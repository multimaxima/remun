@extends('layouts.content')
@section('title','Pembayaran Pasien')

@section('content')
<div class="navbar" style="margin-bottom:5px;">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12">
        <form class="form-inline" method="GET" action="{{ route('pasien_upp_data') }}"  style="margin-top: 5px; margin-bottom: 0;">
        @csrf
          <label>Tanggal</label>
          <input type="date" name="awal" value="{{ $awal }}" style="width: 130px;">
          <label>s/d</label>
          <input type="date" name="akhir" value="{{ $akhir }}" style="width: 130px;">
          
          <div class="btn-group" style="margin-top: 0;">
            <button type="submit" class="btn btn-primary">TAMPILKAN</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <table width="100%" class="table table-hover table-striped" id="tabel">
    <thead>
      <th></th>
      <th>REGISTER</th>
      <th>MR</th>
      <th>JENIS</th>
      <th style="min-width: 150px;">NAMA PASIEN</th>
      <th>ALAMAT</th>
      <th width="70">UMUR</th>
      <th>RUANG</th>
      <th>PETUGAS</th>
      <th width="100">WAKTU</th>
      <th>TAGIHAN</th>
    </thead>
    <tbody>
      @foreach($pasien as $pas)
      <tr>
        <td class="min">
          <a href="{{ route('pasien_upp_data_rincian',Crypt::encrypt($pas->id)) }}" class="btn btn-primary btn-mini" title="Rincian Pembayaran" target="_blank">
            <i class="fa fa-edit"></i>
          </a>
        </td>
        <td class="min">{{ $pas->register }}</td>
        <td>{{ $pas->no_mr }}</td>
        <td>{{ strtoupper($pas->jenis_pasien) }}</td>
        <td>{{ strtoupper($pas->nama) }}</td>
        <td>{{ strtoupper($pas->alamat) }}</td>
        <td>
          {{ $pas->umur_thn }} Thn.
          @if($pas->umur_bln)
            {{ $pas->umur_bln }} Bln.
          @endif
        </td>
        <td>{{ $pas->ruang }}</td>
        <td>{{ $pas->petugas }}</td>
        <td>{{ $pas->waktu_upp }}</td>
        <td style="text-align: right;">{{ number_format($pas->tagihan,0) }}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <th colspan="10" style="text-align: center;">
        J U M L A H
      </th>
      <th style="text-align: right;">{{ number_format($total->tagihan,0) }}</th>
    </tfoot>
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