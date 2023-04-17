<table>
  <tr>
    <td colspan="19" style="font-weight: bold; font-size: 16px; text-align: center;">
      PENERIMAAN JASA PELAYANAN
    </td>
  </tr>
  <tr>
    <td colspan="19" style="font-weight: bold; font-size: 16px; text-align: center;">
      RUMAH SAKIT UMUM DAERAH GENTENG
    </td>
  </tr>
  <tr>
    <td colspan="19" style="font-weight: bold; font-size: 16px; text-align: center;">
      TANGGAL {{ strtoupper($remun->awal) }} S/D {{ strtoupper($remun->akhir) }}
    </td>
  </tr>
  <tr>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="5">NO.</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="40">NAMA</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="10">SKORE</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="10">GOL</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="10">STATUS</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="20">RUANG</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="15">TPP</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="15">INDEK</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="15">JASA<br>PENYESUAIAN</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="15">PENGEMB.<br>LANGSUNG<br> DIREKSI</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="15">PENGEMB.<br>LANGSUNG<br>STAF DIREKSI</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="15">KELOMPOK<br>ADMINIST.</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="15">JASA<br>LANGSUNG MEDIS<br>/PER. SETARA</td>        
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="15">JASA TOTAL</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="15">PAJAK (%)</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="15">NOMINAL<br>PAJAK</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="15">JASA<br>DITERIMA</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="20">NPWP</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="20">BANK</td>
    <td style="font-weight: bold; height: 30px; padding: 10px; text-align: center;" width="20">NO. REK</td>
  </tr>  
  <?php $no = 0;?>
  @foreach($detil as $det)
  <?php $no++ ;?>
  <tr>
    <td align="center">{{ $no }}.</td>
    <td>{{ $det->nama }}</td>
    <td align="right">{{ number_format($det->score,2) }}</td>
    <td>{{ $det->golongan }}</td>
    <td>{{ $det->status }}</td>
    <td>{{ $det->ruang }}</td>
    <td align="right">{{ number_format($det->tpp,2) }}</td>
    <td align="right">{{ number_format($det->r_indek,2) }}</td>
    <td align="right">{{ number_format($det->r_penyesuaian,2) }}</td>
    <td align="right">{{ number_format($det->r_direksi,2) }}</td>
    <td align="right">{{ number_format($det->r_staf_direksi,2) }}</td>
    <td align="right">{{ number_format($det->r_administrasi,2) }}</td>
    <td align="right">{{ number_format($det->r_medis,2) }}</td>        
    <td align="right">{{ number_format($det->jasa,2) }}</td>
    <td align="right">{{ number_format($det->pajak,2) }} %</td>
    <td align="right">{{ number_format($det->nom_pajak,2) }}</td>
    <td align="right">{{ number_format($det->total,2) }}</td>
    <td>{{ $det->npwp }}</td>
    <td>{{ $det->bank }}</td>
    <td>{{ $det->rekening }}</td>
  </tr>
  @endforeach      
  <tr>  
    <td colspan="6" style="font-weight: bold; text-align: center;">TOTAL</td>
    <td style="text-align: right;">{{ number_format($total->tpp,2) }}</td>
    <td style="text-align: right;">{{ number_format($total->r_indek,2) }}</td>
    <td style="text-align: right;">{{ number_format($total->r_penyesuaian,2) }}</td>
    <td style="text-align: right;">{{ number_format($total->r_direksi,2) }}</td>
    <td style="text-align: right;">{{ number_format($total->r_staf_direksi,2) }}</td>
    <td style="text-align: right;">{{ number_format($total->r_administrasi,2) }}</td>
    <td style="text-align: right;">{{ number_format($total->r_medis,2) }}</td>
    <td style="text-align: right;">{{ number_format($total->jasa,2) }}</td>
    <td></td>
    <td style="text-align: right;">{{ number_format($total->nom_pajak,2) }}</td>
    <td style="text-align: right;">{{ number_format($total->total,2) }}</td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="19" style="height: 20px;"></td>
  </tr>
  <tr>
    <td colspan="6">MENGETAHUI</td>
    <td colspan="5"></td>
    <td colspan="5"></td>
    <td colspan="3">{{ $a_param->kota }}, {{ $remun->tanggal }}</td>
  </tr>
  <tr>
    <td colspan="6">
      @if($a_param->direktur_plt == 1)
        Plt.
      @endif
      DIREKTUR RSUD GENTENG
    </td>
    <td colspan="5">BENDAHARA PENGELUARAN PEMBANTU</td>
    <td colspan="5">PEJABAT PELAKSANA TEKNIS KEGIATAN</td>
    <td colspan="3">KETUA TIM REMUNERASI</td>
  </tr>
  <tr>
    <td colspan="6" style="height: 100px;">{{ $param->direktur }}</td>
    <td colspan="5" style="height: 100px;">{{ $param->bendahara }}</td>
    <td colspan="5" style="height: 100px;">{{ $param->pelaksana }}</td>
    <td colspan="3" style="height: 100px;">{{ $param->ketua }}</td>
  </tr>
  <tr>
    <td colspan="6">Pembina Tk. I</td>
    <td colspan="5">NIP. {{ $param->nip_bendahara }}</td>
    <td colspan="5">NIP. {{ $param->nip_pelaksana }}</td>
    <td colspan="3">NIP. {{ $param->nip_ketua }}</td>
  </tr>
  <tr>
    <td colspan="6">NIP. {{ $param->nip_direktur }}</td>
    <td colspan="5"></td>
    <td colspan="5"></td>
    <td colspan="3"></td>
  </tr>
</table>