@extends('layouts.content')
@section('title','Rincian Jasa Remunerasi')

@section('style')
  <style type="text/css">
    .DTFC_LeftBodyLiner { overflow-x: hidden; }
  </style>
@endsection

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12">
        <div class="btn-group">
          <a href="{{ route('remunerasi_rincian_export',Crypt::encrypt($detil->id)) }}" class="btn btn-primary" title="Export">
            EXPORT
          </a>      
          <a href="{{ route('remunerasi_detil_cetak',Crypt::encrypt($detil->id)) }}" class="btn btn-primary" target="_blank" title="Cetak">
            CETAK
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="span6">
    <table width="100%" style="font-size: 12px; line-height: 15px;">
      <tr>
        <td rowspan="10" width="100" style="padding: 0px 10px 0 0;" valign="top">
          @if($detil->foto)
            <img src="/{{ $detil->foto }}" width="100%">
          @else
            <img src="/images/noimage.jpg" width="100%">
          @endif
        </td>
        <td width="80" style="vertical-align: top;">Remunerasi</td>
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
        <td align="right" width="60">{{ number_format($detil->tpp,2) }}</td>
        <td width="30"></td>
        <td align="right" width="80"></td>
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
      @if($detil->titipan > 0)
      <tr>
        <td>Titipan</td>                
        <td>Rp.</td>
        <td align="right">{{ number_format($detil->titipan,2) }}</td>
        <td></td>
        <td></td>
      </tr>
      @endif
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
        <td colspan="3" style="font-weight: bold; font-size: 12px; color: white; padding: 5px 5px;">JASA DITERIMA</td>
        <td style="font-weight: bold; font-size: 12px; color: white;">Rp.</td>
        <td style="font-weight: bold; font-size: 12px; color: white; padding: 5px 5px;" align="right">{{ number_format($detil->sisa,2) }}</td>
      </tr>
    </table>
  </div>

  <!--@if(Auth::user()->id_akses == 1)  
  <div class="span4">
    <table width="100%" class="table table-bordered" style="font-size: 12px;">
        <thead style="background-color: #f2f2f2;">
          <tr>
            <th colspan="4" style="text-align: center; padding: 0 5px;">HISTORI JASA</th>
          </tr>
          <tr>
            <th style="text-align: center; padding: 0 5px;">Keterangan</th>
            <th style="text-align: center; padding: 0 5px;">Pengurangan</th>
            <th style="text-align: center; padding: 0 5px;">Penambahan</th>
            <th style="text-align: center; padding: 0 5px;">Sisa</th>
          </tr>          
        </thead>
        <tbody>
          <tr>
            <td style="padding: 0 5px;">Jasa Asal</td>
            <td style="padding: 0 5px;"></td>
            <td style="padding: 0 5px;"></td>
            <td style="text-align: right; padding: 0 5px;">{{ number_format($detil->r_medis - $total_tandon->keluar + $total_tandon->masuk,2) }}</td>
          </tr>
          @foreach($tandon as $tan)
          <tr>
            <td style="padding: 0 5px;">{{ $tan->keterangan }}</td>
            <td style="text-align: right; padding: 0 5px;">
              @if($tan->masuk > 0)
                {{ number_format($tan->masuk,2) }}
              @endif
            </td>
            <td style="text-align: right; padding: 0 5px;">
              @if($tan->keluar > 0)
                {{ number_format($tan->keluar,2) }}
              @endif
            </td>
            <td style="text-align: right; padding: 0 5px;"></td>
          </tr>
          @endforeach          
        </tbody>
        <tfoot>
          <th style="padding: 0 5px;">Total Jasa</th>
          <th style="padding: 0 5px;"></th>
          <th style="padding: 0 5px;"></th>
          <th style="text-align: right; padding: 0 5px;">{{ number_format($detil->r_medis,2) }}</th>
        </tfoot>
      </table>
  </div>
  @endif-->
</div>

@if($detil->id_tenaga == 1)
<div class="content">  
  <table id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
    <thead style="text-transform: uppercase;">
      <tr>
        <th class="min" rowspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">Tanggal</th>
        <th class="min" rowspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">Nama Pasien</th>
        <th rowspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">Jenis</th>
        <th rowspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">Ruang</th>
        <th rowspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">Layanan</th>
        <th colspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle; background-color: #fdeded;">Tarif</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle;">DPJP</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle; background-color: #fdeded;">Pengganti</th>
        <th colspan="5" style="padding: 0 15px; text-align: center; vertical-align: middle;">Operator</th>
        <th colspan="5" style="padding: 0 15px; text-align: center; vertical-align: middle; background-color: #fdeded;">Anastesi</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle;">Pendamping</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle; background-color: #fdeded;">Konsul</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle;">Laborat</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle; background-color: #fdeded;">Pen. Jwb.</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle;">Radiologi</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle; background-color: #fdeded;">RR</th>
        <th colspan="4" style="padding: 0 15px; text-align: center; vertical-align: middle;">Total Medis</th>
      </tr>
      <tr>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REAL</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px;">REAL</th>
        <th style="text-align: center; padding: 0 15px;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px;">REMUN</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REAL</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REMUN</th>
        <th style="text-align: center; padding: 0 15px;">REAL</th>
        <th style="text-align: center; padding: 0 15px;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px;">DITERIMA</th>
        <th style="text-align: center; padding: 0 15px;">TAMBAHAN</th>
        <th style="text-align: center; padding: 0 15px;">REMUN</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REAL</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">DITERIMA</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">TAMBAHAN</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REMUN</th>
        <th style="text-align: center; padding: 0 15px;">REAL</th>
        <th style="text-align: center; padding: 0 15px;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px;">REMUN</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REAL</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REMUN</th>
        <th style="text-align: center; padding: 0 15px;">REAL</th>
        <th style="text-align: center; padding: 0 15px;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px;">REMUN</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REAL</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REMUN</th>
        <th style="text-align: center; padding: 0 15px;">REAL</th>
        <th style="text-align: center; padding: 0 15px;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px;">REMUN</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REAL</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REMUN</th>
        <th style="text-align: center; padding: 0 15px;">REAL</th>
        <th style="text-align: center; padding: 0 15px;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px;">REMUN</th>
        <th style="text-align: center; padding: 0 15px;">DITERIMA</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rincian as $rinc)
      @if($rinc->jasa_medis > 0)
      <tr>
        <td class="min">{{ strtoupper($rinc->tanggal) }}</td>
        <td class="min">{{ strtoupper($rinc->nama) }}</td>
        <td class="min">{{ strtoupper($rinc->jenis_pasien) }}</td>
        <td class="min">{{ strtoupper($rinc->ruang) }}</td>
        <td class="min">{{ strtoupper($rinc->jasa) }}</td>              
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->tarif_real,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->tarif_claim,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_dpjp,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_dpjp,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_dpjp,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->real_pengganti,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->claim_pengganti,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->jasa_pengganti,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_operator,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_operator,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_operator_diterima,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->min_operator,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_operator,2) }}</td>

        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->real_anastesi,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->claim_anastesi,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->jasa_anastesi_diterima,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->min_anastesi,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->jasa_anastesi,2) }}</td>

        <td class="min" style="text-align: right;">{{ number_format($rinc->real_pendamping,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_pendamping,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_pendamping,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->real_konsul,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->claim_konsul,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->jasa_konsul,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_laborat,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_laborat,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_laborat,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->real_tanggung,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->claim_tanggung,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->jasa_tanggung,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_radiologi,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_radiologi,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_radiologi,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->real_rr,2) }}</td>            
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->claim_rr,2) }}</td>            
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->jasa_rr,2) }}</td>            
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_real,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_claim,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_medis,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format(($rinc->jasa_medis/($total->dpjp + $total->pengganti + $total->operator + $total->anastesi + $total->pendamping + $total->konsul + $total->laborat + $total->tanggung + $total->radiologi + $total->rr)) * $detil->r_medis,2) }}</td>
      </tr>
      @endif
      @endforeach
    </tbody>
    <tfoot>
      <th colspan="5" style="text-align: center; padding: 0 5px 0 10px;">JUMLAH</th>      
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->tarif_real,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->tarif_claim,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->real_dpjp,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->claim_dpjp,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->dpjp,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->real_pengganti,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->claim_pengganti,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->pengganti,2) }}</th>

      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->real_operator,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->claim_operator,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->operator_diterima,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->min_operator,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->operator,2) }}</th>

      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->real_anastesi,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->claim_anastesi,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->anastesi_diterima,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->min_anastesi,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->anastesi,2) }}</th>

      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->real_pendamping,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->claim_pendamping,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->pendamping,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->real_konsul,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->claim_konsul,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->konsul,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->real_laborat,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->claim_laborat,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->laborat,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->real_tanggung,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->claim_tanggung,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->tanggung,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->real_radiologi,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->claim_radiologi,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->radiologi,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->real_rr,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->claim_rr,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->rr,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">
        {{ number_format($total->real_dpjp + $total->real_pengganti + $total->real_operator + $total->real_anastesi + $total->real_pendamping + $total->real_konsul + $total->real_laborat + $total->real_tanggung + $total->real_radiologi + $total->real_rr,2) }}
      </th>
      <th style="text-align: right; padding: 0 5px 0 10px;">
        {{ number_format($total->claim_dpjp + $total->claim_pengganti + $total->claim_operator + $total->claim_anastesi + $total->claim_pendamping + $total->claim_konsul + $total->claim_laborat + $total->claim_tanggung + $total->claim_radiologi + $total->claim_rr,2) }}
      </th>
      <th style="text-align: right; padding: 0 5px 0 10px;">
        {{ number_format($total->dpjp + $total->pengganti + $total->operator + $total->anastesi + $total->pendamping + $total->konsul + $total->laborat + $total->tanggung + $total->radiologi + $total->rr,2) }}
      </th>
      <th style="text-align: right; padding: 0 5px 0 10px;">
        {{ number_format($detil->r_medis,2) }}
      </th>
    </tfoot>    
  </table>
</div>
@else
@if($detil->r_medis > 0)
<div class="content">  
  <table id="tabel1" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
    <thead style="text-transform: uppercase;">
      <tr>
        <th class="min" rowspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle; min-width: 150px;">RUANG</th>
        <th class="min" colspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">PERAWAT</th>
        <th class="min" colspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">PEN. ANASTESI</th>
        <th class="min" colspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">PER.ASS. 1</th>
        <th class="min" colspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">PER.ASS. 2</th>
        <th class="min" colspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">INSTRUMEN</th>
        <th class="min" colspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">SIRKULER</th>
        <th class="min" colspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">APOTEKER</th>
        <th class="min" colspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">ASS.APOTEKER</th>
        <th class="min" colspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">ADMIN FARMASI</th>
        <th class="min" colspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">PEMULASARAN</th>
        <th class="min" colspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">FISIOTERAPIS</th>
      </tr>
      <tr>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">REAL</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">DITERIMA</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">REAL</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">DITERIMA</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">REAL</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">DITERIMA</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">REAL</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">DITERIMA</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">REAL</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">DITERIMA</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">REAL</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">DITERIMA</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">REAL</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">DITERIMA</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">REAL</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">DITERIMA</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">REAL</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">DITERIMA</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">REAL</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">DITERIMA</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">REAL</th>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle; min-width: 50px;">DITERIMA</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rincian as $rinc)
      <tr>
        <td class="min">{{ $rinc->ruang }}</td>
        <td style="text-align: right;">{{ number_format($rinc->medis_perawat,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->jasa_perawat,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->medis_pen_anastesi,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->jasa_pen_anastesi,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->medis_per_asisten_1,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->jasa_per_asisten_1,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->medis_per_asisten_2,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->jasa_per_asisten_2,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->medis_instrumen,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->jasa_instrumen,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->medis_sirkuler,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->jasa_sirkuler,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->medis_apoteker,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->jasa_apoteker,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->medis_ass_apoteker,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->jasa_ass_apoteker,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->medis_admin_farmasi,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->jasa_admin_farmasi,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->medis_pemulasaran,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->jasa_pemulasaran,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->medis_fisio,2) }}</td>
        <td style="text-align: right;">{{ number_format($rinc->jasa_fisio,2) }}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <th style="text-align: center; padding: 0 5px 0 10px;">JUMLAH</th>      
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->medis_perawat,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->jasa_perawat,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->medis_pen_anastesi,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->jasa_pen_anastesi,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->medis_per_asisten_1,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->jasa_per_asisten_1,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->medis_per_asisten_2,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->jasa_per_asisten_2,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->medis_instrumen,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->jasa_instrumen,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->medis_sirkuler,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->jasa_sirkuler,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->medis_apoteker,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->jasa_apoteker,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->medis_ass_apoteker,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->jasa_ass_apoteker,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->medis_admin_farmasi,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->jasa_admin_farmasi,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->medis_pemulasaran,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->jasa_pemulasaran,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->medis_fisio,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->jasa_fisio,2) }}</th>
    </tfoot>
  </table>
</div>
@endif
@endif
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      if(window.screen.height < 800){
        $('#tabel').DataTable( {
          scrollY:        "25vh",
          scrollX:        true,
          scrollCollapse: true,
          paging:         false,
          searching:      false,
          stateSave:      true,
          sort:           false,
          info:           false,
          fixedColumns:   {
            leftColumns: 5
          },
        });
      } else {
        $('#tabel').DataTable( {             
          scrollY:        "40vh",
          scrollX:        true,
          scrollCollapse: true,
          paging:         false,
          searching:      false,
          stateSave:      true,
          sort:           false,
          info:           false,
          fixedColumns:   {
            leftColumns: 5
          },
        });
      }      

      if(window.screen.height < 900){
        $('#tabel1').DataTable( {
          scrollY:        "25vh",
          scrollX:        true,
          scrollCollapse: true,
          paging:         false,
          searching:      false,
          stateSave:      true,
          sort:           false,
          info:           false,
          fixedColumns:   {
            leftColumns: 1
          },
        });
      } else {
        $('#tabel1').DataTable( {             
          scrollY:        "40vh",
          scrollX:        true,
          scrollCollapse: true,
          paging:         false,
          searching:      false,
          stateSave:      true,
          sort:           false,
          info:           false,
          fixedColumns:   {
            leftColumns: 1
          },
        });
      }      
    });
  </script>
@endsection