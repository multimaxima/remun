<table>  
  <tr>
    <td colspan="42" style="font-weight: bold;">
      KALKULASI JASA DPJP TANGGAL {{ strtoupper($awal) }} S/D {{ strtoupper($akhir) }}
    </td>
  </tr>
  <tr>
    <td style="font-weight: bold; text-align: center;" rowspan="2" width="30">DPJP</td>
    <td style="font-weight: bold; text-align: center;" rowspan="2" width="30">RUANG PERAWATAN</td>
    <td style="font-weight: bold; text-align: center;" rowspan="2" width="30">RUANG TINDAKAN</td>
    <td style="font-weight: bold; text-align: center;" rowspan="2" width="15">TARIF</td>
    <td style="font-weight: bold; text-align: center;" rowspan="2" width="30">DOKTER</td>
    <td style="font-weight: bold; text-align: center;" colspan="2">UGD</td>
    <td style="font-weight: bold; text-align: center;" colspan="2">RUANG</td>
    <td style="font-weight: bold; text-align: center;" colspan="2">LABORAT</td>
    <td style="font-weight: bold; text-align: center;" colspan="2">OPERASI</td>
    <td style="font-weight: bold; text-align: center;" colspan="2">RADIOLOGI</td>
    <td style="font-weight: bold; text-align: center;" colspan="2">RR</td>
    <td style="font-weight: bold; text-align: center;" colspan="2">GIZI</td>
    <td style="font-weight: bold; text-align: center;" colspan="2">APOTIK</td>
    <td style="font-weight: bold; text-align: center;" colspan="10">JASA MEDIS</td>
    <td style="font-weight: bold; text-align: center;" colspan="11">JASA PENUNJANG</td>
  </tr>
  <tr>
    <td style="font-weight: bold; text-align: center;" width="15">JS</td>
    <td style="font-weight: bold; text-align: center;" width="15">JP</td>
    <td style="font-weight: bold; text-align: center;" width="15">JS</td>
    <td style="font-weight: bold; text-align: center;" width="15">JP</td>
    <td style="font-weight: bold; text-align: center;" width="15">JS</td>
    <td style="font-weight: bold; text-align: center;" width="15">JP</td>
    <td style="font-weight: bold; text-align: center;" width="15">JS</td>
    <td style="font-weight: bold; text-align: center;" width="15">JP</td>
    <td style="font-weight: bold; text-align: center;" width="15">JS</td>
    <td style="font-weight: bold; text-align: center;" width="15">JP</td>
    <td style="font-weight: bold; text-align: center;" width="15">JS</td>
    <td style="font-weight: bold; text-align: center;" width="15">JP</td>
    <td style="font-weight: bold; text-align: center;" width="15">JS</td>
    <td style="font-weight: bold; text-align: center;" width="15">JP</td>
    <td style="font-weight: bold; text-align: center;" width="15">JS</td>
    <td style="font-weight: bold; text-align: center;" width="15">JP</td>
    <td style="font-weight: bold; text-align: center;" width="15">DPJP</td>
    <td style="font-weight: bold; text-align: center;" width="15">PENGGANTI</td>
    <td style="font-weight: bold; text-align: center;" width="15">OPERATOR</td>
    <td style="font-weight: bold; text-align: center;" width="15">ANASTESI</td>
    <td style="font-weight: bold; text-align: center;" width="15">PENDAMPING</td>
    <td style="font-weight: bold; text-align: center;" width="15">KONSUL</td>
    <td style="font-weight: bold; text-align: center;" width="15">LABORAT</td>
    <td style="font-weight: bold; text-align: center;" width="15">PENGANGGUNG JWB.</td>
    <td style="font-weight: bold; text-align: center;" width="15">RADIOLOGI</td>
    <td style="font-weight: bold; text-align: center;" width="15">RR</td>
    <td style="font-weight: bold; text-align: center;" width="15">JP PERAWAT</td>
    <td style="font-weight: bold; text-align: center;" width="15">PENATA ANASTESI</td>
    <td style="font-weight: bold; text-align: center;" width="15">PER. ASIST. 1</td>
    <td style="font-weight: bold; text-align: center;" width="15">PER. ASIST. 2</td>
    <td style="font-weight: bold; text-align: center;" width="15">INSTRUMEN</td>
    <td style="font-weight: bold; text-align: center;" width="15">SIRKULER</td>
    <td style="font-weight: bold; text-align: center;" width="15">PER. PEND. 1</td>
    <td style="font-weight: bold; text-align: center;" width="15">PER. PEND. 2</td>
    <td style="font-weight: bold; text-align: center;" width="15">APOTEKER</td>
    <td style="font-weight: bold; text-align: center;" width="15">ASS. APOTEKER</td>
    <td style="font-weight: bold; text-align: center;" width="15">ADM. FARMASI</td>
  </tr>
  @foreach($pasien as $pas)
  <tr>
    <td>{{ strtoupper($pas->dpjp) }}</td>
    <td>{{ strtoupper($pas->ruang) }}</td>
    <td>{{ strtoupper($pas->ruang_sub) }}</td>
    <td align="right">{{ number_format($pas->tarif,0) }}</td>
    <td>{{ $pas->dokter }}</td>
    <td align="right">{{ number_format($pas->ugd_js,0) }}</td>
    <td align="right">{{ number_format($pas->ugd_jp,0) }}</td>
    <td align="right">{{ number_format($pas->ruang_js,0) }}</td>
    <td align="right">{{ number_format($pas->ruang_jp,0) }}</td>
    <td align="right">{{ number_format($pas->laborat_js,0) }}</td>
    <td align="right">{{ number_format($pas->laborat_jp,0) }}</td>
    <td align="right">{{ number_format($pas->operasi_js,0) }}</td>
    <td align="right">{{ number_format($pas->operasi_jp,0) }}</td>
    <td align="right">{{ number_format($pas->radiologi_js,0) }}</td>
    <td align="right">{{ number_format($pas->radiologi_jp,0) }}</td>
    <td align="right">{{ number_format($pas->rr_js,0) }}</td>
    <td align="right">{{ number_format($pas->rr_jp,0) }}</td>
    <td align="right">{{ number_format($pas->gizi_js,0) }}</td>
    <td align="right">{{ number_format($pas->gizi_jp,0) }}</td>
    <td align="right">{{ number_format($pas->apotik_js,0) }}</td>
    <td align="right">{{ number_format($pas->apotik_jp,0) }}</td>
    <td align="right">{{ number_format($pas->jasa_dpjp,0) }}</td>
    <td align="right">{{ number_format($pas->jasa_pengganti,0) }}</td>
    <td align="right">{{ number_format($pas->jasa_operator,0) }}</td>
    <td align="right">{{ number_format($pas->jasa_anastesi,0) }}</td>
    <td align="right">{{ number_format($pas->jasa_pendamping,0) }}</td>
    <td align="right">{{ number_format($pas->jasa_konsul,0) }}</td>
    <td align="right">{{ number_format($pas->jasa_laborat,0) }}</td>
    <td align="right">{{ number_format($pas->jasa_tanggung,0) }}</td>
    <td align="right">{{ number_format($pas->jasa_radiologi,0) }}</td>
    <td align="right">{{ number_format($pas->jasa_rr,0) }}</td>
    <td align="right">{{ number_format($pas->jp_perawat,0) }}</td>
    <td align="right">{{ number_format($pas->pen_anastesi,0) }}</td>
    <td align="right">{{ number_format($pas->per_asisten_1,0) }}</td>
    <td align="right">{{ number_format($pas->per_asisten_2,0) }}</td>
    <td align="right">{{ number_format($pas->instrumen,0) }}</td>
    <td align="right">{{ number_format($pas->sirkuler,0) }}</td>
    <td align="right">{{ number_format($pas->per_pendamping_1,0) }}</td>
    <td align="right">{{ number_format($pas->per_pendamping_2,0) }}</td>
    <td align="right">{{ number_format($pas->apoteker,0) }}</td>
    <td align="right">{{ number_format($pas->ass_apoteker,0) }}</td>
    <td align="right">{{ number_format($pas->admin_farmasi,0) }}</td>
  </tr>
  @endforeach
</table>
