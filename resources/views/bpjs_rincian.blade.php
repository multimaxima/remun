@extends('layouts.content')
@section('title','Rincian Claim')

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12">
        <div class="btn-group">
          <button class="btn btn-primary" onclick="goBack();">KEMBALI</button>
          <a href="{{ route('bpjs_rincian_cetak',Crypt::encrypt($detil->id)) }}" class="btn btn-primary" target="_blank">
            CETAK
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <table width="40%" style="font-size: 14px; margin-bottom: 20px;">        
    <tr>
      <td width="100">Nama DPJP</td>
      <td width="10">:</td>
      <td style="font-weight: bold;">{{ $user->nama }}</td>          
    </tr>
    <tr>
      <td>Jenis Claim</td>
      <td>:</td>
      <td>{{ strtoupper($bpjs->jenis) }}</td>
    </tr>    
    <tr>
      <td>Periode</td>
      <td>:</td>
      <td>{{ strtoupper($bpjs->t_awal) }} - {{ strtoupper($bpjs->t_akhir) }}</td>          
    </tr>
    <tr>
      <td>Tagihan</td>
      <td>:</td>
      <td>
        Rp. {{ number_format($detil->nominal_inap + $detil->nominal_jalan,0) }}
      </td>
    </tr>    
    <tr>
      <td>Claim</td>
      <td>:</td>
      <td>
        Rp. {{ number_format($detil->claim_inap + $detil->claim_jalan,0) }}
      </td>
    </tr>    
  </table>

  <table width="100%" id="tabel" class="table table-bordered">
    <thead>
      <tr>
        <th style="text-align: center;">PASIEN</th>
        <th style="text-align: center;">MR</th>
        <th style="text-align: center;">JENIS</th>
        <th style="text-align: center;">RUANG PERAWATAN</th>
        <th style="text-align: center;">RUANG TINDAKAN</th>
        <th style="text-align: center;">LAYANAN</th>
        <th style="text-align: center;">TARIF</th>
        <th style="text-align: center;">DPJP</th>
        <th style="text-align: center;">PENGGANTI</th>
        <th style="text-align: center;">OPERATOR</th>
        <th style="text-align: center;">ANASTESI</th>
        <th style="text-align: center;">PENDAMPING</th>
        <th style="text-align: center;">KONSUL</th>
        <th style="text-align: center;">LABORAT</th>
        <th style="text-align: center;">PEN. JAWAB</th>
        <th style="text-align: center;">RADIOLOGI</th>
        <th style="text-align: center;">RR</th>
        <th style="text-align: center;">TOTAL MEDIS</th>
      </tr>  
    </thead>
    <tbody>
      @foreach($rincian as $rinc)
      <tr>
        <td hidden>{{ $rinc->id_pasien }}</td>
        <td class="min" style="vertical-align: middle;">{{ strtoupper($rinc->pasien) }}</td>
        <td class="min" style="vertical-align: middle;">{{ strtoupper($rinc->no_mr) }}</td>
        <td class="min" style="vertical-align: middle;">{{ strtoupper($rinc->jenis) }} {{ strtoupper($rinc->jenis_rawat) }}</td>
        <td class="min" style="vertical-align: middle;">{{ strtoupper($rinc->ruang) }}</td>
        <td class="min">{{ strtoupper($rinc->ruang_tindakan) }}</td>
        <td class="min">{{ $rinc->jasa }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->tarif,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->dpjp,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->pengganti,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->operator,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->anastesi,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->pendamping,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->konsul,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->laborat,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->tanggung,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->radiologi,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->rr,0) }}</td>
        <td class="min" style="text-align: right;">
          {{ number_format($rinc->dpjp + $rinc->pengganti + $rinc->operator + $rinc->anastesi + $rinc->pendamping + $rinc->konsul + $rinc->laborat + $rinc->tanggung + $rinc->radiologi + $rinc->rr,0) }}
        </td>
      </tr>
      @endforeach
    </tbody>     
    <tfoot>
      <tr>
        <th colspan="6" style="text-align: center;">TOTAL</th>        
        <th style="text-align: right;">{{ number_format($jasa->tarif,0) }}</th>
        <th style="text-align: right;">{{ number_format($jasa->dpjp,0) }}</th>
        <th style="text-align: right;">{{ number_format($jasa->pengganti,0) }}</th>
        <th style="text-align: right;">{{ number_format($jasa->operator,0) }}</th>
        <th style="text-align: right;">{{ number_format($jasa->anastesi,0) }}</th>
        <th style="text-align: right;">{{ number_format($jasa->pendamping,0) }}</th>
        <th style="text-align: right;">{{ number_format($jasa->konsul,0) }}</th>
        <th style="text-align: right;">{{ number_format($jasa->laborat,0) }}</th>
        <th style="text-align: right;">{{ number_format($jasa->tanggung,0) }}</th>
        <th style="text-align: right;">{{ number_format($jasa->radiologi,0) }}</th>
        <th style="text-align: right;">{{ number_format($jasa->rr,0) }}</th>
        <th style="text-align: right;">{{ number_format($jasa->medis,0) }}</th>
      </tr>  
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
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        "order": [[ 1, "asc" ]],
      });
    });

    $('#tabel').margetable({
      type: 2,
      colindex: [0, 1, 2, 3, 4]
    });
  </script>  
@endsection