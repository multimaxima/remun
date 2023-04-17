@extends('layouts.cetak')
@section('title','Rincian Remunerasi')

@section('content')
<div class="row-fluid">
  <div class="span6">
    <table width="100%" style="font-size: 12px; line-height: 15px;">
      <tr>
        <td rowspan="10" width="120" style="padding: 0px 10px 0 0;" valign="top">
          @if($detil->foto)
            <img src="/{{ $detil->foto }}" width="100%">
          @else
            <img src="/images/noimage.jpg" width="100%">
          @endif
        </td>
        <td width="100" style="vertical-align: top;">Remunerasi</td>
        <td width="10" style="vertical-align: top;">:</td>
        <td>{{ strtoupper($detil->tgl_awal) }} - {{ strtoupper($detil->tgl_akhir) }}</td>
      </tr>          
      <tr>
        <td style="vertical-align: top;">Nama</td>
        <td style="vertical-align: top;">:</td>
        <td style="font-weight: bold; font-size: 14px;">{{ $detil->nama }}</td>
      </tr>
      <tr>
        <td>Bagian</td>
        <td>:</td>
        <td>{{ strtoupper($detil->bagian) }}</td>
      </tr>          
      <tr>
        <td>Ruang</td>
        <td>:</td>
        <td>{{ strtoupper($detil->ruang) }}</td>
      </tr>
      <tr>
        <td>Pajak</td>
        <td>:</td>
        <td>{{ number_format($detil->pajak,2) }} %</td>
      </tr>
      <tr>
        <td>Score</td>
        <td>:</td>
        <td>{{ number_format($detil->skore,2) }}</td>
      </tr>              
      <tr>
        <td>Masa Kerja</td>
        <td>:</td>
        <td>{{ $detil->masa_kerja }}</td>
      </tr>              
      <tr>
        <td>Gaji Pokok</td>
        <td>:</td>
        <td>Rp. {{ number_format($detil->gapok,0) }}</td>
      </tr>
      <tr>
        <td>Koreksi Gaji</td>
        <td>:</td>
        <td>Rp. {{ number_format($detil->koreksi,0) }}</td>
      </tr>
    </table>
  </div>

  <div class="span6">
    <table width="100%" style="font-size: 12px; line-height: 15px;">
      <tr>
        <td>TPP</td>
        <td width="30">Rp.</td>
        <td align="right" width="120">{{ number_format($detil->tpp,2) }}</td>
        <td width="30"></td>
        <td align="right" width="120"></td>
      </tr>
      <tr>
        <td>Indek</td>
        <td>Rp.</td>
        <td align="right">{{ number_format($detil->r_indek,2) }}</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>JP Direksi</td>                
        <td>Rp.</td>
        <td align="right">{{ number_format($detil->r_direksi,2) }}</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>JP Staf Direksi</td>                
        <td>Rp.</td>
        <td align="right">{{ number_format($detil->r_staf_direksi,2) }}</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>Administrasi</td>                
        <td>Rp.</td>
        <td align="right">{{ number_format($detil->r_administrasi,2) }}</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>JP Medis/Perawat Setara</td>                
        <td>Rp.</td>
        <td align="right">{{ number_format($detil->r_medis,2) }}</td>
        <td></td>
        <td></td>
      </tr>
      <tr style="background-color: #d6d5d5;">
        <td colspan="3" style="padding: 2px 5px;">Total</td>                
        <td>Rp.</td>
        <td style="padding: 2px 5px;" align="right">{{ number_format($detil->r_jasa_pelayanan,2) }}</td>
      </tr>
      <tr style="background-color: #d6d5d5;">
        <td colspan="3" style="padding: 2px 5px;">Pajak ({{ number_format($detil->pajak,2) }} %)</td>
        <td>Rp.</td>
        <td style="padding: 2px 5px;" align="right">{{ number_format($detil->nominal_pajak,2) }}</td>
      </tr>
      <tr style="background-color: #9a9a9a;">
        <td colspan="3" style="font-weight: bold; font-size: 13px; color: white; padding: 5px 5px;">JASA DITERIMA</td>
        <td style="font-weight: bold; font-size: 13px; color: white;">Rp.</td>
        <td style="font-weight: bold; font-size: 13px; color: white; padding: 5px 5px;" align="right">{{ number_format($detil->sisa,2) }}</td>
      </tr>
    </table>
  </div>  
</div>

@if($detil->id_tenaga == 1)
  <table class="table table-bordered" style="font-size: 12px; margin-top: 10px;">
    <thead style="text-transform: uppercase;">
      <tr>
        <th class="min" rowspan="2" style="padding: 0 5px; text-align: center; vertical-align: middle;">Tanggal</th>
        <th class="min" rowspan="2" style="padding: 0 5px; text-align: center; vertical-align: middle;">Nama Pasien</th>
        <th rowspan="2" style="padding: 0 5px; text-align: center; vertical-align: middle;">Jenis</th>
        <th rowspan="2" style="padding: 0 5px; text-align: center; vertical-align: middle;">Ruang</th>
        <th rowspan="2" style="padding: 0 5px; text-align: center; vertical-align: middle;">Layanan</th>
        <th colspan="2" style="padding: 0 5px; text-align: center; vertical-align: middle;">Tarif</th>
        <th colspan="3" style="padding: 0 5px; text-align: center; vertical-align: middle;">DPJP</th>
        <th colspan="3" style="padding: 0 5px; text-align: center; vertical-align: middle;">Pengganti</th>
        <th colspan="5" style="padding: 0 5px; text-align: center; vertical-align: middle;">Operator</th>
        <th colspan="3" style="padding: 0 5px; text-align: center; vertical-align: middle;">Anastesi</th>
        <th colspan="3" style="padding: 0 5px; text-align: center; vertical-align: middle;">Pendamping</th>
        <th colspan="3" style="padding: 0 5px; text-align: center; vertical-align: middle;">Konsul</th>
        <th colspan="3" style="padding: 0 5px; text-align: center; vertical-align: middle;">Laborat</th>
        <th colspan="3" style="padding: 0 5px; text-align: center; vertical-align: middle;">Pen. Jwb.</th>
        <th colspan="3" style="padding: 0 5px; text-align: center; vertical-align: middle;">Radiologi</th>
        <th colspan="3" style="padding: 0 5px; text-align: center; vertical-align: middle;">RR</th>
        <th colspan="3" style="padding: 0 5px; text-align: center; vertical-align: middle;">Total Medis</th>
      </tr>
      <tr>
        <th style="text-align: center; padding: 0 5px;">REAL</th>
        <th style="text-align: center; padding: 0 5px;">CLAIM</th>
        <th style="text-align: center; padding: 0 5px;">REAL</th>
        <th style="text-align: center; padding: 0 5px;">CLAIM</th>
        <th style="text-align: center; padding: 0 5px;">REMUN</th>
        <th style="text-align: center; padding: 0 5px;">REAL</th>
        <th style="text-align: center; padding: 0 5px;">CLAIM</th>
        <th style="text-align: center; padding: 0 5px;">REMUN</th>
        <th style="text-align: center; padding: 0 5px;">REAL</th>
        <th style="text-align: center; padding: 0 5px;">CLAIM</th>
        <th style="text-align: center; padding: 0 5px;">DITERIMA</th>
        <th style="text-align: center; padding: 0 5px;">TAMBAHAN</th>
        <th style="text-align: center; padding: 0 5px;">REMUN</th>
        <th style="text-align: center; padding: 0 5px;">REAL</th>
        <th style="text-align: center; padding: 0 5px;">CLAIM</th>
        <th style="text-align: center; padding: 0 5px;">REMUN</th>
        <th style="text-align: center; padding: 0 5px;">REAL</th>
        <th style="text-align: center; padding: 0 5px;">CLAIM</th>
        <th style="text-align: center; padding: 0 5px;">REMUN</th>
        <th style="text-align: center; padding: 0 5px;">REAL</th>
        <th style="text-align: center; padding: 0 5px;">CLAIM</th>
        <th style="text-align: center; padding: 0 5px;">REMUN</th>
        <th style="text-align: center; padding: 0 5px;">REAL</th>
        <th style="text-align: center; padding: 0 5px;">CLAIM</th>
        <th style="text-align: center; padding: 0 5px;">REMUN</th>
        <th style="text-align: center; padding: 0 5px;">REAL</th>
        <th style="text-align: center; padding: 0 5px;">CLAIM</th>
        <th style="text-align: center; padding: 0 5px;">REMUN</th>
        <th style="text-align: center; padding: 0 5px;">REAL</th>
        <th style="text-align: center; padding: 0 5px;">CLAIM</th>
        <th style="text-align: center; padding: 0 5px;">REMUN</th>
        <th style="text-align: center; padding: 0 5px;">REAL</th>
        <th style="text-align: center; padding: 0 5px;">CLAIM</th>
        <th style="text-align: center; padding: 0 5px;">REMUN</th>
        <th style="text-align: center; padding: 0 5px;">REAL</th>
        <th style="text-align: center; padding: 0 5px;">CLAIM</th>
        <th style="text-align: center; padding: 0 5px;">REMUN</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rincian as $rinc)
      @if($rinc->jasa_medis > 0)
      <tr>
        <td class="min" style="padding: 0 5px;">{{ strtoupper($rinc->tanggal) }}</td>
        <td class="min" style="padding: 0 5px;">{{ strtoupper($rinc->nama) }}</td>
        <td class="min" style="padding: 0 5px;">{{ strtoupper($rinc->jenis_pasien) }}</td>
        <td class="min" style="padding: 0 5px;">{{ strtoupper($rinc->ruang) }}</td>
        <td class="min" style="padding: 0 5px;">{{ strtoupper($rinc->jasa) }}</td>              
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->tarif_real,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->tarif_claim,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->real_dpjp,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->claim_dpjp,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->jasa_dpjp,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->real_pengganti,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->claim_pengganti,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->jasa_pengganti,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->real_operator,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->claim_operator,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->jasa_operator_diterima,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->min_operator,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->jasa_operator,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->real_anastesi,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->claim_anastesi,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->jasa_anastesi,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->real_pendamping,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->claim_pendamping,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->jasa_pendamping,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->real_konsul,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->claim_konsul,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->jasa_konsul,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->claim_laborat,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->real_laborat,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->jasa_laborat,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->real_tanggung,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->claim_tanggung,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->jasa_tanggung,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->real_radiologi,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->claim_radiologi,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->jasa_radiologi,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->real_rr,2) }}</td>            
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->claim_rr,2) }}</td>            
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->jasa_rr,2) }}</td>            
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->jasa_real,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->jasa_claim,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->jasa_medis,2) }}</td>
      </tr>
      @endif
      @endforeach
    </tbody>
    <tfoot>
      <th style="text-align: center; padding: 10px;" colspan="5">TOTAL</th>      
      <th style="text-align: right; padding: 10px;">{{ number_format($total->tarif_real,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->tarif_claim,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->real_dpjp,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->claim_dpjp,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->dpjp,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->real_pengganti,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->claim_pengganti,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->pengganti,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->real_operator,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->claim_operator,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->operator_diterima,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->min_operator,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->operator,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->real_anastesi,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->claim_anastesi,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->anastesi,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->real_pendamping,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->claim_pendamping,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->pendamping,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->real_konsul,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->claim_konsul,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->konsul,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->real_laborat,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->claim_laborat,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->laborat,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->real_tanggung,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->claim_tanggung,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->tanggung,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->real_radiologi,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->claim_radiologi,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->radiologi,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->real_rr,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->claim_rr,2) }}</th>
      <th style="text-align: right; padding: 10px;">{{ number_format($total->rr,2) }}</th>
      <th style="text-align: right; padding: 10px;">
        {{ number_format($total->real_dpjp + $total->real_pengganti + $total->real_operator + $total->real_anastesi + $total->real_pendamping + $total->real_konsul + $total->real_laborat + $total->real_tanggung + $total->real_radiologi + $total->real_rr,2) }}
      </th>
      <th style="text-align: right; padding: 10px;">
        {{ number_format($total->claim_dpjp + $total->claim_pengganti + $total->claim_operator + $total->claim_anastesi + $total->claim_pendamping + $total->claim_konsul + $total->claim_laborat + $total->claim_tanggung + $total->claim_radiologi + $total->claim_rr,2) }}
      </th>
      <th style="text-align: right; padding: 10px;">
        {{ number_format($total->dpjp + $total->pengganti + $total->operator + $total->anastesi + $total->pendamping + $total->konsul + $total->laborat + $total->tanggung + $total->radiologi + $total->rr,2) }}
      </th>
    </tfoot>    
  </table>
@endif
@endsection
