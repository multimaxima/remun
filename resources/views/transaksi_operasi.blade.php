@extends('layouts.content')
@section('title','Data Transaksi')

@section('content')
<div class="navbar" style="margin-bottom:5px;">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12">
        <form class="form-inline" method="GET" action="{{ route('pasien_operasi_transaksi') }}" style="margin-top: 5px; margin-bottom: 0;">
        @csrf
          <label>Tanggal</label>
          <input type="date" name="awal" value="{{ $awal }}" style="width: 130px;">
          <label>s/d</label>
          <input type="date" name="akhir" value="{{ $akhir }}" style="width: 130px;">
          <select name="oper">
            <option value="" style="font-style: italic;">SEMUA OPERATOR</option>
            @foreach($operator as $opr)
              <option value="{{ $opr->id }}" {{ $oper == $opr->id? 'selected' : null }}>{{ $opr->nama }}</option>
            @endforeach
          </select>
          <button type="submit" class="btn btn-primary" style="margin-top: 0;">TAMPILKAN</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
    <thead style="text-transform: uppercase;">
      <tr>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Waktu</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Nama Pasien</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Ruang</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Dokter Operator</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Dokter Anastesi</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Dokter Pendamping</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Layanan</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">Tarif</th>
        <th colspan="9" style="text-align: center; padding: 0 15px;">Jasa</th>
      </tr>
      <tr>
        <th style="text-align: center; padding: 0 15px;">Operator</th>
        <th style="text-align: center; padding: 0 15px;">Anastesi</th>
        <th style="text-align: center; padding: 0 15px;">Pendamping</th>
        <th style="text-align: center; padding: 0 15px;">Pen.Anastesi</th>
        <th style="text-align: center; padding: 0 15px;">Per.Asisten 1</th>
        <th style="text-align: center; padding: 0 15px;">Per.Asisten 2</th>
        <th style="text-align: center; padding: 0 15px;">Instrumen</th>
        <th style="text-align: center; padding: 0 15px;">Sirkuler</th>
        <th style="text-align: center; padding: 0 15px;">Administrasi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($layanan as $lay)
      <tr>
        <td class="min">{{ strtoupper($lay->waktu) }}</td>
        <td class="min">{{ strtoupper($lay->nama) }}</td>
        <td class="min">{{ strtoupper($lay->ruang) }}</td>
        <td class="min">{{ $lay->operator }}</td>
        <td class="min">{{ $lay->anastesi }}</td>
        <td class="min">{{ $lay->pendamping }}</td>
        <td class="min">{{ strtoupper($lay->jasa) }}</td>
        <td style="text-align: right;">{{ number_format($lay->tarif,0) }}</td>
        <td style="text-align: right;">{{ number_format($lay->jasa_operator,0) }}</td>
        <td style="text-align: right;">{{ number_format($lay->jasa_anastesi,0) }}</td>
        <td style="text-align: right;">{{ number_format($lay->jasa_pendamping,0) }}</td>
        <td style="text-align: right;">{{ number_format($lay->pen_anastesi,0) }}</td>
        <td style="text-align: right;">{{ number_format($lay->per_asisten_1,0) }}</td>
        <td style="text-align: right;">{{ number_format($lay->per_asisten_2,0) }}</td>
        <td style="text-align: right;">{{ number_format($lay->instrumen,0) }}</td>
        <td style="text-align: right;">{{ number_format($lay->sirkuler,0) }}</td>
        <td style="text-align: right;">{{ number_format($lay->administrasi,0) }}</td>
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
        "order": [[ 0, "asc" ]],
      });
    });
  </script>
@endsection