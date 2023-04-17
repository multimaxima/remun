@extends('layouts.content')
@section('title','Data Statistik Pasien')

@section('content')
<div class="navbar" style="margin-bottom:5px;">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12">
        <form class="form-inline" method="GET" action="{{ route('pasien_statistik') }}" style="margin-top: 5px; margin-bottom: 0;">
        @csrf
          <label>Tanggal</label>
          <input type="date" name="awal" value="{{ $awal }}" style="width: 130px;">

          <label>s/d</label>
          <input type="date" name="akhir" value="{{ $akhir }}" style="width: 130px;">

          <label style="margin-left: 10px;">Ruang</label>
          <select name="id_ruang" id="id_ruang" required>
            <option value="">===  SEMUA RUANG ===</option>
            @foreach($ruang as $ruang)
              <option value="{{ $ruang->id }}" {{ $id_ruang == $ruang->id? 'selected' : null }}>{{ strtoupper($ruang->ruang) }}</option>
            @endforeach
          </select>

          <button type="submit" class="btn btn-primary" style="margin-top: 0;">
            TAMPILKAN
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
    <li class="nav-item active">
      <a class="nav-link" data-toggle="tab" href="#rekap" role="tab">REKAPITULASI</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#pasien1" role="tab">RAWAT JALAN UMUM</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#pasien2" role="tab">RAWAT JALAN JKN</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#pasien3" role="tab">RAWAT INAP UMUM</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#pasien4" role="tab">RAWAT INAP JKN</a>
    </li>
  </ul>

  <div class="tab-content container-fluid">
    <div class="tab-pane active p-3" id="rekap" role="tabpanel">
      @if($rng)
  <center>          
    <label style="font-size: 16px; font-weight: bold;">REKAPAN KEUANGAN RUANG {{ strtoupper($rng->ruang) }}</label>
    <label style="font-size: 16px; font-weight: bold;">TANGGAL {{ strtoupper($tgl_awal) }} S/D {{ strtoupper($tgl_akhir) }}</label>          
  </center>        
  <table width="100%" class="table table-bordered" style="font-size: 12px;">
    <thead>
      <tr>
        <th rowspan="2" width="50" style="text-align: center; vertical-align: middle;">NO.</th>
        <th rowspan="2" width="250" style="text-align: center; vertical-align: middle;">PERAWATAN</th>
        <th rowspan="2" width="150" style="text-align: center; vertical-align: middle;">PEMBIAYAAN</th>
        <th colspan="3" style="text-align: center;">PASIEN</th>
        <th colspan="3" style="text-align: center;">TARIF</th>
      </tr>
      <tr>
        <th style="text-align: center;">JUMLAH</th>
        <th style="text-align: center;">%</th>
        <th style="text-align: center;">% KOMULATIF</th>
        <th style="text-align: center;">NOMINAL</th>
        <th style="text-align: center;">%</th>
        <th style="text-align: center;">% KOMULATIF</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td rowspan="3" style="vertical-align: middle; text-align: center;">1.</td>
        <td rowspan="3" style="vertical-align: middle;">PASIEN RAWAT JALAN</td>
        <td>BPJS</td>
        <td style="text-align: right;">{{ number_format($kalkulasi->rjj,0) }}</td>
        <td style="text-align: right;">{{ number_format(($kalkulasi->rjj / ($kalkulasi->rjj + $kalkulasi->rju)) * 100,2) }}</td>
        <td style="text-align: right;">{{ number_format(($kalkulasi->rjj / ($kalkulasi->rjj + $kalkulasi->rju + $kalkulasi->riu + $kalkulasi->rij)) * 100,2) }}</td>
        <td style="text-align: right;">{{ number_format($tarif_kal->tarif_rjj,0) }}</td>
        <td style="text-align: right;">{{ number_format(($tarif_kal->tarif_rjj / ($tarif_kal->tarif_rjj + $tarif_kal->tarif_rju)) * 100,2) }}</td>
        <td style="text-align: right;">{{ number_format(($tarif_kal->tarif_rjj / ($tarif_kal->tarif_rjj + $tarif_kal->tarif_rju + $tarif_kal->tarif_rij + $tarif_kal->tarif_riu)) * 100,2) }}</td>
      </tr>
      <tr>
        <td>UMUM</td>
        <td style="text-align: right;">{{ number_format($kalkulasi->rju,0) }}</td>
        <td style="text-align: right;">{{ number_format(($kalkulasi->rju / ($kalkulasi->rjj + $kalkulasi->rju)) * 100,2) }}</td>
        <td style="text-align: right;">{{ number_format(($kalkulasi->rju / ($kalkulasi->rjj + $kalkulasi->rju + $kalkulasi->riu + $kalkulasi->rij)) * 100,2) }}</td>
        <td style="text-align: right;">{{ number_format($tarif_kal->tarif_rju,0) }}</td>
        <td style="text-align: right;">{{ number_format(($tarif_kal->tarif_rju / ($tarif_kal->tarif_rjj + $tarif_kal->tarif_rju)) * 100,2) }}</td>
        <td style="text-align: right;">{{ number_format(($tarif_kal->tarif_rju / ($tarif_kal->tarif_rjj + $tarif_kal->tarif_rju + $tarif_kal->tarif_rij + $tarif_kal->tarif_riu)) * 100,2) }}</td>
      </tr>
      <tr style="background-color: #d1d1d1; font-weight: bold;">
        <td>TOTAL</td>
        <td style="text-align: right;">{{ number_format($kalkulasi->rjj + $kalkulasi->rju,0) }}</td>
        <td style="text-align: right;">100.00</td>
        <td style="text-align: right;">{{ number_format((($kalkulasi->rjj + $kalkulasi->rju) / ($kalkulasi->rjj + $kalkulasi->rju + $kalkulasi->riu + $kalkulasi->rij)) * 100,2) }}</td>
        <td style="text-align: right;">{{ number_format($tarif_kal->tarif_rju + $tarif_kal->tarif_rjj,0) }}</td>
        <td style="text-align: right;">100.00</td>
        <td style="text-align: right;">{{ number_format((($tarif_kal->tarif_rju + $tarif_kal->tarif_rjj) / ($tarif_kal->tarif_rjj + $tarif_kal->tarif_rju + $tarif_kal->tarif_rij + $tarif_kal->tarif_riu)) * 100,2) }}</td>
      </tr>
      <tr>
        <td rowspan="3" style="vertical-align: middle; text-align: center;">2.</td>
        <td rowspan="3" style="vertical-align: middle;">PASIEN RAWAT INAP</td>
        <td>BPJS</td>
        <td style="text-align: right;">{{ number_format($kalkulasi->rij,0) }}</td>
        <td style="text-align: right;">{{ number_format(($kalkulasi->rij / ($kalkulasi->rij + $kalkulasi->riu)) * 100,2) }}</td>
        <td style="text-align: right;">{{ number_format(($kalkulasi->rij / ($kalkulasi->rjj + $kalkulasi->rju + $kalkulasi->riu + $kalkulasi->rij)) * 100,2) }}</td>
        <td style="text-align: right;">{{ number_format($tarif_kal->tarif_rij,0) }}</td>
        <td style="text-align: right;">{{ number_format(($tarif_kal->tarif_rij / ($tarif_kal->tarif_rij + $tarif_kal->tarif_riu)) * 100,2) }}</td>
        <td style="text-align: right;">{{ number_format(($tarif_kal->tarif_rij / ($tarif_kal->tarif_rjj + $tarif_kal->tarif_rju + $tarif_kal->tarif_rij + $tarif_kal->tarif_riu)) * 100,2) }}</td>
      </tr>
      <tr>
        <td>UMUM</td>
        <td style="text-align: right;">{{ number_format($kalkulasi->riu,0) }}</td>
        <td style="text-align: right;">{{ number_format(($kalkulasi->riu / ($kalkulasi->rij + $kalkulasi->riu)) * 100,2) }}</td>
        <td style="text-align: right;">{{ number_format(($kalkulasi->riu / ($kalkulasi->rjj + $kalkulasi->rju + $kalkulasi->riu + $kalkulasi->rij)) * 100,2) }}</td>
        <td style="text-align: right;">{{ number_format($tarif_kal->tarif_riu,0) }}</td>
        <td style="text-align: right;">{{ number_format(($tarif_kal->tarif_riu / ($tarif_kal->tarif_rij + $tarif_kal->tarif_riu)) * 100,2) }}</td>
        <td style="text-align: right;">{{ number_format(($tarif_kal->tarif_riu / ($tarif_kal->tarif_rjj + $tarif_kal->tarif_rju + $tarif_kal->tarif_rij + $tarif_kal->tarif_riu)) * 100,2) }}</td>
      </tr>
      <tr style="background-color: #d1d1d1; font-weight: bold;">
        <td>TOTAL</td>
        <td style="text-align: right;">{{ number_format($kalkulasi->rij + $kalkulasi->riu,0) }}</td>
        <td style="text-align: right;">100.00</td>
        <td style="text-align: right;">{{ number_format((($kalkulasi->rij + $kalkulasi->riu) / ($kalkulasi->rjj + $kalkulasi->rju + $kalkulasi->riu + $kalkulasi->rij)) * 100,2) }}</td>
        <td style="text-align: right;">{{ number_format($tarif_kal->tarif_riu + $tarif_kal->tarif_rij,0) }}</td>
        <td style="text-align: right;">100.00</td>
        <td style="text-align: right;">{{ number_format((($tarif_kal->tarif_riu + $tarif_kal->tarif_rij) / ($tarif_kal->tarif_rjj + $tarif_kal->tarif_rju + $tarif_kal->tarif_rij + $tarif_kal->tarif_riu)) * 100,2) }}</td>
      </tr>
    </tbody>
    <tfoot>
      <tr style="background-color: #c1bebe; font-weight: bold; font-size: 14px;">
      <th colspan="2" style="text-align: center;">TOTAL</th>
      <th></th>
      <th style="text-align: right;">{{ number_format($kalkulasi->rjj + $kalkulasi->rju + $kalkulasi->rij + $kalkulasi->riu,0) }}</th>
      <th></th>
      <th style="text-align: right;">100.00</th>
      <th style="text-align: right;">{{ number_format($tarif_kal->tarif_rjj + $tarif_kal->tarif_rju + $tarif_kal->tarif_rij + $tarif_kal->tarif_riu,0) }}</th>
      <th></th>
      <th style="text-align: right;">100.00</th>
      </tr>
    </tfoot>
  </table>
@endif
    </div>

    <div class="tab-pane p-3" id="pasien1" role="tabpanel">
      <table width="100%" id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
        <thead>
          <th>NAMA PASIEN</th>
          <th>REGISTER</th>
          <th>MR</th>
          <th>DPJP</th>
          <th>TINDAKAN</th>
          <th>TAGIHAN</th>
        </thead>
        <tbody>
          @foreach($rju as $rju)
          <tr>
            <td class="min">{{ strtoupper($rju->nama) }}</td>
            <td class="min" style="text-align: center;">{{ $rju->register }}</td>
            <td class="min" style="text-align: center;">{{ $rju->no_mr }}</td>
            <td class="min">{{ $rju->dpjp }}</td>
            <td>{{ strtoupper($rju->jasa) }}</td>
            <td class="min" style="text-align: right;">{{ number_format($rju->tarif,0) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="tab-pane p-3" id="pasien2" role="tabpanel">
      <table width="100%" id="tabel1" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
        <thead>
          <th>NAMA PASIEN</th>
          <th>REGISTER</th>
          <th>MR</th>
          <th>DPJP</th>
          <th>TINDAKAN</th>
          <th>TAGIHAN</th>
        </thead>
        <tbody>
          @foreach($rjj as $rjj)
          <tr>
            <td class="min">{{ strtoupper($rjj->nama) }}</td>
            <td class="min" style="text-align: center;">{{ $rjj->register }}</td>
            <td class="min" style="text-align: center;">{{ $rjj->no_mr }}</td>
            <td class="min">{{ $rjj->dpjp }}</td>
            <td>{{ strtoupper($rjj->jasa) }}</td>
            <td class="min" style="text-align: right;">{{ number_format($rjj->tarif,0) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="tab-pane p-3" id="pasien3" role="tabpanel">
      <table width="100%" id="tabel2" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
        <thead>
          <th>NAMA PASIEN</th>
          <th>REGISTER</th>
          <th>MR</th>
          <th>DPJP</th>
          <th>TINDAKAN</th>
          <th>TAGIHAN</th>
        </thead>
        <tbody>
          @foreach($riu as $riu)
          <tr>
            <td class="min">{{ strtoupper($riu->nama) }}</td>
            <td class="min" style="text-align: center;">{{ $riu->register }}</td>
            <td class="min" style="text-align: center;">{{ $riu->no_mr }}</td>
            <td class="min">{{ $riu->dpjp }}</td>
            <td>{{ strtoupper($riu->jasa) }}</td>
            <td class="min" style="text-align: right;">{{ number_format($riu->tarif,0) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="tab-pane p-3" id="pasien4" role="tabpanel">
      <table width="100%" id="tabel3" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
        <thead>
          <th>NAMA PASIEN</th>
          <th>REGISTER</th>
          <th>MR</th>
          <th>DPJP</th>
          <th>TINDAKAN</th>
          <th>TAGIHAN</th>
        </thead>
        <tbody>
          @foreach($rij as $rij)
          <tr>
            <td class="min">{{ strtoupper($rij->nama) }}</td>
            <td class="min" style="text-align: center;">{{ $rij->register }}</td>
            <td class="min" style="text-align: center;">{{ $rij->no_mr }}</td>
            <td class="min">{{ $rij->dpjp }}</td>
            <td>{{ strtoupper($rij->jasa) }}</td>
            <td class="min" style="text-align: right;">{{ number_format($rij->tarif,0) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
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
        "sort": false,
      });

      $('#tabel1').DataTable( {
        "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": 0
        }],
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        "sort": false,
      });

      $('#tabel2').DataTable( {
        "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": 0
        }],
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        "sort": false,
      });

      $('#tabel3').DataTable( {
        "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": 0
        }],
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        "sort": false,
      });
    });
  </script>
@endsection