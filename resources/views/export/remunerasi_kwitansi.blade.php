<table>
  <tr>
    <td colspan="15" style="font-weight: bold; font-size: 16px; text-align: center;">
      RINCIAN PERHITUNGAN REMUNERASI
      @if($master->id_bpjs)
        JKN
      @else
        UMUM
      @endif
    </td>
  </tr>
  <tr>
    <td colspan="15" style="font-weight: bold; font-size: 16px; text-align: center;">
      RUMAH SAKIT UMUM DAERAH GENTENG
    </td>
  </tr>
  <tr>
    <td colspan="15" style="font-weight: bold; font-size: 16px; text-align: center;">
      TANGGAL {{ strtoupper($master->awal) }} S/D {{ strtoupper($master->akhir) }}
    </td>
  </tr>
  <tr>
    <td colspan="15"></td>
  </tr>
  <tr>
    <td style="text-align: center; font-weight: bold; height: 30px;" width="5">NO.</td>
    <td style="text-align: center; font-weight: bold; height: 30px;" colspan="7">URAIAN</td>
    <td style="text-align: center; font-weight: bold; height: 30px;" width="10">%</td>
    <td style="text-align: center; font-weight: bold; height: 30px;" colspan="2">JUMLAH</td>
    <td style="text-align: center; font-weight: bold; height: 30px;" colspan="2">PAJAK</td>
    <td style="text-align: center; font-weight: bold; height: 30px;" colspan="2">JASA PELAYANAN<br>YANG DITERIMA</td>
  </tr>
      <tr>
        <td align="center" valign="top" style="font-weight: bold; padding: 5px 10px;">C.</td>
        <td style="font-weight: bold; padding: 5px 10px;" colspan="7">
          JASA PELAYANAN SETELAH DIKURANGI JASA<br>
          TIM/KEPANITIAAN, LUAR JAM KERJA,
        </td>
        <td align="right" style="padding: 5px 10px;">100.00 %</td>
        <td width="5">Rp.</td>
        <td width="20" align="right" style="font-weight: bold; padding: 5px 10px;">
          {{ number_format($remun->jp,2) }}
        </td>
        <td width="5">Rp.</td>
        <td width="20" align="right" style="font-weight: bold; padding: 5px 10px;">
          {{ number_format($remun->jp_pajak,2) }}
        </td>
        <td width="5">Rp.</td>
        <td width="20" align="right" style="font-weight: bold; padding: 5px 10px;">
          {{ number_format($remun->jp - $remun->jp_pajak,2) }}
        </td>
      </tr>      
      <tr>
        <td align="center"></td>
        <td style="font-weight: bold; padding: 5px 10px;" colspan="7">
          1. INSENTIF TIDAK LANGSUNG
        </td>
        <td align="right" style="font-weight: bold; padding: 5px 10px;">{{ $a_param->nonpenghasil }} %</td>
        <td width="5">Rp.</td>
        <td align="right" style="font-weight: bold; padding: 5px 10px;">
          {{ number_format($remun->nonpenghasil,2) }}
        </td>
        <td width="5">Rp.</td>
        <td align="right" style="font-weight: bold; padding: 5px 10px;">
          {{ number_format($remun->nonpenghasil_pajak,2) }}
        </td>
        <td width="5">Rp.</td>
        <td align="right" style="font-weight: bold; padding: 5px 10px;">
          {{ number_format($remun->nonpenghasil - $remun->nonpenghasil_pajak,2) }}
        </td>
      </tr>
      <tr>
        <td align="center"></td>
        <td align="center" width="5"></td>
        <td style="padding: 5px 10px;" colspan="5">
          Jasa Direktur
        </td>
        <td align="right" width="10" style="padding: 5px 10px;">{{ $a_param->direksi }} %</td>
        <td align="center"></td>
        <td width="5">Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->direksi,2) }}
        </td>
        <td width="5">Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->direksi_pajak,2) }}
        </td>
        <td width="5">Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->direksi - $remun->direksi_pajak,2) }}
        </td>
      </tr>
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td style="padding: 5px 10px;" colspan="5">
          Jasa Staf Direksi / Struktural
        </td>
        <td align="right" style="padding: 5px 10px;">{{ $a_param->staf }} %</td>
        <td align="center"></td>
        <td width="5">Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->staf_direksi,2) }}
        </td>
        <td width="5">Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->staf_pajak,2) }}
        </td>
        <td width="5">Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->staf_direksi - $remun->staf_pajak,2) }}
        </td>
      </tr>
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td style="padding: 5px 10px;" colspan="5">
          Jasa Penyesuaian
        </td>
        <td align="right" style="padding: 5px 10px;">{{ number_format($a_param->penyesuaian,2) }} %</td>
        <td align="center"></td>
        <td width="5">Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($master->r_penyesuaian,2) }}
        </td>
        <td width="5">Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->penyesuaian_pajak,2) }}
        </td>
        <td width="5">Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->penyesuaian - $remun->penyesuaian_pajak,2) }}
        </td>
      </tr>
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center" width="5"></td>
        <td style="padding: 5px 10px;" colspan="3">
          Operator
        </td>
        <td align="right" style="padding: 5px 10px;" width="10">{{ $a_param->peny_operator }} %</td>
        <td align="center"></td>
        <td align="center"></td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($master->r_penyesuaian_operator,2) }}
        </td>
        <td align="right" style="padding: 5px 10px;"></td>
        <td align="right" style="padding: 5px 10px;"></td>
      </tr>      
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td style="padding: 5px 10px;" colspan="3">
          Spesialis
        </td>
        <td align="right" style="padding: 5px 10px;">{{ $a_param->peny_spesialis }} %</td>
        <td align="center"></td>
        <td align="center"></td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($master->r_penyesuaian_spesialis,2) }}
        </td>
        <td align="right" style="padding: 5px 10px;"></td>
        <td align="right" style="padding: 5px 10px;"></td>
      </tr>      
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td style="padding: 5px 10px;" colspan="3">
          Umum/Gigi
        </td>
        <td align="right" style="padding: 5px 10px;">{{ $a_param->peny_umum }} %</td>
        <td align="center"></td>
        <td align="center"></td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($master->r_penyesuaian_umum,2) }}
        </td>
        <td align="right" style="padding: 5px 10px;"></td>
        <td align="right" style="padding: 5px 10px;"></td>
      </tr>      
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td style="padding: 5px 10px;" colspan="3">
          Keperawatan
        </td>
        <td align="right" style="padding: 5px 10px;">{{ $a_param->peny_keperawatan }} %</td>
        <td align="center"></td>
        <td align="center"></td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($master->r_penyesuaian_perawat,2) }}
        </td>
        <td align="right" style="padding: 5px 10px;"></td>
        <td align="right" style="padding: 5px 10px;"></td>
      </tr>
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td style="padding: 5px 10px;" colspan="3">
          Administrasi
        </td>
        <td align="right" style="padding: 5px 10px;">{{ $a_param->peny_administrasi }} %</td>
        <td align="center"></td>
        <td align="center"></td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($master->r_penyesuaian_administrasi,2) }}
        </td>
        <td align="right" style="padding: 5px 10px;"></td>
        <td align="right" style="padding: 5px 10px;"></td>
      </tr>      
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td style="padding: 5px 10px;" colspan="3">
          Staf Direksi
        </td>
        <td align="right" style="padding: 5px 10px;">{{ $a_param->peny_staf }} %</td>
        <td align="center"></td>
        <td align="center"></td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($master->r_penyesuaian_staf,2) }}
        </td>
        <td align="right" style="padding: 5px 10px;"></td>
        <td align="right" style="padding: 5px 10px;"></td>
      </tr>      
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td style="padding: 5px 10px;" colspan="5">
          Pos Remunerasi
        </td>
        <td align="right" style="padding: 5px 10px;">{{ number_format($a_param->pos_remun,2) }} %</td>
        <td align="center"></td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->pos_remun,2) }}
        </td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->pos_remun_pajak,2) }}
        </td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->pos_remun - $remun->pos_remun_pajak,2) }}
        </td>
      </tr>      
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td colspan="6" style="padding: 5px 10px;">TPP</td>        
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->tpp,2) }}
        </td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->tpp_pajak,2) }}
        </td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->tpp - $remun->tpp_pajak,2) }}
        </td>
      </tr>      
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td style="padding: 5px 10px;" colspan="6">Indek</td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->pos_remun - $remun->tpp,2) }}
        </td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->pos_remun_pajak - $remun->tpp_pajak,2) }}
        </td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->pos_remun - $remun->tpp - $remun->pos_remun_pajak + $remun->tpp_pajak,2) }}
        </td>
      </tr>      
      <tr>
        <td align="center"></td>
        <td style="font-weight: bold; padding: 5px 10px;" colspan="7">
          2. INSENTIF LANGSUNG
        </td>
        <td align="right" style="font-weight: bold; padding: 5px 10px;">{{ $a_param->penghasil }} %</td>
        <td>Rp.</td>
        <td align="right" style="font-weight: bold; padding: 5px 10px;">
          {{ number_format($remun->penghasil,2) }}
        </td>
        <td>Rp.</td>
        <td align="right" style="font-weight: bold; padding: 5px 10px;">
          {{ number_format($remun->penghasil_pajak,2) }}
        </td>
        <td>Rp.</td>
        <td align="right" style="font-weight: bold; padding: 5px 10px;">
          {{ number_format($remun->penghasil - $remun->penghasil_pajak,2) }}
        </td>
      </tr>
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td style="padding: 5px 10px;" colspan="4">
          Administrasi
        </td>
        <td align="right" style="padding: 5px 10px;">{{ $a_param->admin }} %</td>
        <td align="center"></td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->administrasi,2) }}
        </td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->administrasi_pajak,2) }}
        </td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->administrasi - $remun->administrasi_pajak,2) }}
        </td>
      </tr>
      <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td style="padding: 5px 10px;" colspan="4">
          Kembali Langsung
        </td>
        <td align="right" style="padding: 5px 10px;">{{ $a_param->medis_perawat }} %</td>
        <td align="center"></td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->medis,2) }}
        </td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->medis_pajak,2) }}
        </td>
        <td>Rp.</td>
        <td align="right" style="padding: 5px 10px;">
          {{ number_format($remun->medis - $remun->medis_pajak,2) }}
        </td>
      </tr>
    <tr>
      <td colspan="15"></td>
    </tr>
    <tr>
      <td></td>
      <td colspan="9"></td>
      <td colspan="5">{{ $a_param->kota }}, {{ $master->tanggal }}</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="9">MENGETAHUI,</td>
      <td colspan="5">KETUA TIM REMUNERASI</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="9">
        @if($a_param->direktur_plt == 1)
          Plt.
        @endif
        DIREKTUR RSUD GENTENG
      </td>
      <td colspan="5">RSUD GENTENG</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="9" style="height: 100px;">{{ $param->direktur }}</td>
      <td colspan="5" style="height: 100px;">{{ $param->ketua }}</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="9">Pembina Tk. I</td>
      <td colspan="5">{{ $param->nip_ketua }}</td>
    </tr>
    <tr>
      <td></td>
      <td colspan="9">NIP. {{ $param->nip_direktur }}</td>
      <td colspan="5"></td>
    </tr>
  </table>