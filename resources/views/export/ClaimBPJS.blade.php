<table>
  <tr>
    <td colspan="7" style="font-weight: bold; font-size: 16px; text-align: center;">
      CLAIM BPJS TANGGAL {{ strtoupper($bpjs->awal) }} S/D {{ strtoupper($bpjs->akhir) }}
    </td>
  </tr>
  <tr>
    <th style="font-weight: bold; text-align: center;" valign="middle" width="50" rowspan="2">DOKTER DPJP</th>
    <th style="font-weight: bold; text-align: center;" colspan="2">RAWAT JALAN</th>
    <th style="font-weight: bold; text-align: center;" colspan="2">RAWAT INAP</th>
    <th style="font-weight: bold; text-align: center;" colspan="2">TOTAL MEDIS</th>
  </tr>
  <tr>
    <th style="font-weight: bold; text-align: center;" width="15">TAGIHAN</th>
    <th style="font-weight: bold; text-align: center;" width="15">CLAIM</th>
    <th style="font-weight: bold; text-align: center;" width="15">TAGIHAN</th>
    <th style="font-weight: bold; text-align: center;" width="15">CLAIM</th>
    <th style="font-weight: bold; text-align: center;" width="15">TAGIHAN</th>
    <th style="font-weight: bold; text-align: center;" width="15">CLAIM</th>
  </tr>  
  @foreach($detil as $detil)             
  <tr>
    <td>{{ $detil->nama }}</td>
    <td align="right">{{ number_format($detil->nominal_jalan,0) }}</td>
    <td align="right">{{ number_format($detil->claim_jalan,0) }}</td>
    <td align="right">{{ number_format($detil->nominal_inap,0) }}</td>
    <td align="right">{{ number_format($detil->claim_inap,0) }}</td>
    <td align="right">{{ number_format($detil->nominal_jalan + $detil->nominal_inap,0) }}</td>
    <td align="right">{{ number_format($detil->claim_jalan + $detil->claim_inap,0) }}</td>
  </tr>
  @endforeach
  <tr>
    <th style="font-weight: bold; text-align: center;">JUMLAH</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($tag->t_jalan,0) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($tag->c_jalan,0) }}</th>            
    <th style="font-weight: bold; text-align: right;">{{ number_format($tag->t_inap,0) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($tag->c_inap,0) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($tag->t_jalan + $tag->t_inap,0) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($tag->c_jalan + $tag->c_inap,0) }}</th>
  </tr>
</table>