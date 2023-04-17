<table width="100%" style="font-size: 12px; line-height: 15px;">
  <tr>
    <td width="20" style="vertical-align: top;">Remunerasi</td>
    <td width="50">: {{ strtoupper($detil->tgl_awal) }} - {{ strtoupper($detil->tgl_akhir) }}</td>
    <td width="20"></td>
    <td width="30">TPP</td>
    <td width="5">Rp.</td>
    <td align="right" width="20">{{ number_format($detil->tpp,2) }}</td>
    <td width="5"></td>
    <td align="right" width="20"></td>
  </tr>          
  <tr>
    <td style="vertical-align: top;">Nama</td>
    <td style="font-weight: bold;">: {{ $detil->nama }}</td>
    <td></td>
    <td>Indek</td>
    <td>Rp.</td>
    <td align="right">{{ number_format($detil->r_indek,2) }}</td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Bagian</td>
    <td>: {{ strtoupper($detil->bagian) }}</td>
    <td></td>
    <td>JP Direksi</td>                
    <td>Rp.</td>
    <td align="right">{{ number_format($detil->r_direksi,2) }}</td>
    <td></td>
    <td></td>
  </tr>          
  <tr>
    <td>Ruang</td>
    <td>: {{ strtoupper($detil->ruang) }}</td>
    <td></td>
    <td>JP Staf Direksi</td>                
    <td>Rp.</td>
    <td align="right">{{ number_format($detil->r_staf_direksi,2) }}</td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Pajak</td>
    <td>: {{ number_format($detil->pajak,2) }} %</td>
    <td></td>
    <td>Administrasi</td>                
    <td>Rp.</td>
    <td align="right">{{ number_format($detil->r_administrasi,2) }}</td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Score</td>
    <td>: {{ number_format($detil->skore,2) }}</td>
    <td></td>
    <td>JP Medis/Perawat Setara</td>                
    <td>Rp.</td>
    <td align="right">{{ number_format($detil->r_medis,2) }}</td>
    <td></td>
    <td></td>
  </tr>              
  <tr>
    <td>Masa Kerja</td>
    <td>: {{ $detil->masa_kerja }}</td>
    <td></td>
    <td colspan="3" style="padding: 2px 5px; background-color: #ebebeb;">Total</td>                
    <td style="background-color: #ebebeb;">Rp.</td>
    <td style="padding: 2px 5px; background-color: #ebebeb;" align="right">{{ number_format($detil->r_jasa_pelayanan,2) }}</td>
  </tr>              
  <tr>
    <td>Gaji Pokok</td>
    <td>: Rp. {{ number_format($detil->gapok,0) }}</td>
    <td></td>
    <td colspan="3" style="padding: 2px 5px; background-color: #ebebeb;">Pajak ({{ number_format($detil->pajak,2) }} %)</td>
    <td style="background-color: #ebebeb;">Rp.</td>
    <td style="padding: 2px 5px; background-color: #ebebeb;" align="right">{{ number_format($detil->nominal_pajak,2) }}</td>
  </tr>
  <tr>
    <td>Koreksi Gaji</td>
    <td>: Rp. {{ number_format($detil->koreksi,0) }}</td>
    <td></td>
    <td colspan="3" style="font-weight: bold; font-size: 13px;padding: 5px 5px; background-color: #cecccc;">JASA DITERIMA</td>
    <td style="font-weight: bold; font-size: 13px; background-color: #cecccc;">Rp.</td>
    <td style="font-weight: bold; font-size: 13px;padding: 5px 5px; background-color: #cecccc;" align="right">{{ number_format($detil->sisa,2) }}</td>
  </tr>
</table>  
  

@if($detil->id_tenaga == 1)
<div class="content">  
  <table style="font-size: 12px;">
    <thead style="text-transform: uppercase;">
      <tr>
        <th class="min" rowspan="2" style="text-align: center; background-color: #ebebeb; font-weight: bold;">TANGGAL</th>
        <th class="min" rowspan="2" style="text-align: center; background-color: #ebebeb; font-weight: bold;">NAMA PASIEN</th>
        <th rowspan="2" style="text-align: center; background-color: #ebebeb; font-weight: bold;">JENIS</th>
        <th rowspan="2" style="text-align: center; background-color: #ebebeb; font-weight: bold;">RUANG</th>
        <th rowspan="2" colspan="3" style="text-align: center; background-color: #ebebeb; font-weight: bold;">LAYANAN</th>
        <th colspan="2" style="text-align: center; background-color: #ebebeb; font-weight: bold;">TARIF</th>
        <th colspan="3" style="text-align: center; background-color: #ebebeb; font-weight: bold;">DPJP</th>
        <th colspan="3" style="text-align: center; background-color: #ebebeb; font-weight: bold;">PENGGANTI</th>
        <th colspan="5" style="text-align: center; background-color: #ebebeb; font-weight: bold;">OPERATOR</th>
        <th colspan="3" style="text-align: center; background-color: #ebebeb; font-weight: bold;">ANASTESI</th>
        <th colspan="3" style="text-align: center; background-color: #ebebeb; font-weight: bold;">PENDAMPING</th>
        <th colspan="3" style="text-align: center; background-color: #ebebeb; font-weight: bold;">KONSUL</th>
        <th colspan="3" style="text-align: center; background-color: #ebebeb; font-weight: bold;">LABORAT</th>
        <th colspan="3" style="text-align: center; background-color: #ebebeb; font-weight: bold;">PEN. JAWAB</th>
        <th colspan="3" style="text-align: center; background-color: #ebebeb; font-weight: bold;">RADIOLOGI</th>
        <th colspan="3" style="text-align: center; background-color: #ebebeb; font-weight: bold;">RR</th>
        <th colspan="3" style="text-align: center; background-color: #ebebeb; font-weight: bold;">TOTAL MEDIS</th>
      </tr>
      <tr>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REAL</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">CLAIM</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REAL</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">CLAIM</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REMUN</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REAL</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">CLAIM</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REMUN</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REAL</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">CLAIM</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">DITERIMA</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">TAMBAHAN</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REMUN</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REAL</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">CLAIM</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REMUN</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REAL</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">CLAIM</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REMUN</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REAL</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">CLAIM</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REMUN</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REAL</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">CLAIM</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REMUN</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REAL</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">CLAIM</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REMUN</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REAL</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">CLAIM</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REMUN</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REAL</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">CLAIM</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REMUN</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REAL</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">CLAIM</th>
        <th width="15" style="text-align: center; background-color: #ebebeb; font-weight: bold;">REMUN</th>
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
        <td colspan="3" class="min">{{ strtoupper($rinc->jasa) }}</td>              
        <td class="min" style="text-align: right;">{{ number_format($rinc->tarif_real,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->tarif_claim,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_dpjp,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_dpjp,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_dpjp,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_pengganti,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_pengganti,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_pengganti,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_operator,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_operator,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_operator_diterima,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->min_operator,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_operator,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_anastesi,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_anastesi,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_anastesi,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_pendamping,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_pendamping,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_pendamping,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_konsul,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_konsul,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_konsul,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_laborat,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_laborat,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_laborat,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_tanggung,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_tanggung,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_tanggung,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_radiologi,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_radiologi,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_radiologi,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_rr,2) }}</td>            
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_rr,2) }}</td>            
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_rr,2) }}</td>            
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_real,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_claim,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_medis,2) }}</td>
      </tr>
      @endif
      @endforeach   
      <tr>
      <td colspan="6" style="text-align: center; font-weight: bold; background-color: #ebebeb">TOTAL</td>      
      <td colspan="2" style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->tarif_real,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->tarif_claim,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->real_dpjp,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->claim_dpjp,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->dpjp,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->real_pengganti,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->claim_pengganti,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->pengganti,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->real_operator,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->claim_operator,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->operator_diterima,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->min_operator,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->operator,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->real_anastesi,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->claim_anastesi,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->anastesi,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->real_pendamping,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->claim_pendamping,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->pendamping,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->real_konsul,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->claim_konsul,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->konsul,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->real_laborat,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->claim_laborat,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->laborat,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->real_tanggung,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->claim_tanggung,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->tanggung,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->real_radiologi,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->claim_radiologi,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->radiologi,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->real_rr,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->claim_rr,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">{{ number_format($total->rr,2) }}</td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">
        {{ number_format($total->real_dpjp + $total->real_pengganti + $total->real_operator + $total->real_anastesi + $total->real_pendamping + $total->real_konsul + $total->real_laborat + $total->real_tanggung + $total->real_radiologi + $total->real_rr,2) }}
      </td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">
        {{ number_format($total->claim_dpjp + $total->claim_pengganti + $total->claim_operator + $total->claim_anastesi + $total->claim_pendamping + $total->claim_konsul + $total->claim_laborat + $total->claim_tanggung + $total->claim_radiologi + $total->claim_rr,2) }}
      </td>
      <td style="text-align: right; font-weight: bold; background-color: #ebebeb">        
        {{ number_format($detil->r_medis,2) }}
      </td>
      </tr>
    </tbody>
  </table>
</div>
@endif