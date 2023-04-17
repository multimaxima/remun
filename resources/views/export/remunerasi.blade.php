<table>
  <tr>
    <td colspan="22" style="font-weight: bold; font-size: 20px; text-align: center;">

      PERHITUNGAN REMUNERASI PASIEN 
      @if($remun->id_bpjs)
        BPJS
      @else
        UMUM
      @endif
      TANGGAL {{ strtoupper($remun->tgl_awal) }} - {{ strtoupper($remun->tgl_akhir) }}
    </td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <th style="text-align: center; font-weight: bold;">JP</th>
    <th style="text-align: center; font-weight: bold;">PENGHASIL</th>
    <th style="text-align: center; font-weight: bold;">NON PENGHASIL</th>
    <th style="text-align: center; font-weight: bold;">POS REMUN</th>
    <th style="text-align: center; font-weight: bold;">TPP</th>
    <th style="text-align: center; font-weight: bold;">INDEK</th>
    <th style="text-align: center; font-weight: bold;">DIREKSI</th>
    <th style="text-align: center; font-weight: bold;">STAF DIREKSI</th>
    <th style="text-align: center; font-weight: bold;">ADMIN</th>
    <th style="text-align: center; font-weight: bold;">TOTAL INDEK</th>
    <th style="text-align: center; font-weight: bold;">MEDIS/PERAWAT</th>
  </tr>
  <tr>
    <td></td>
    <td style="text-align: right;">Perhitungan Jasa</td>                
    <td style="text-align: right;">{{ number_format($remun->jp,2) }}</td>
    <td style="text-align: right;">{{ number_format($remun->penghasil,2) }}</td>
    <td style="text-align: right;">{{ number_format($remun->nonpenghasil,2) }}</td>
    <td style="text-align: right;">{{ number_format($remun->pos_remun,2) }}</td>
    <td style="text-align: right; background-color: #b9b9b9;"></td>
    <td style="text-align: right; background-color: #b9b9b9;"></td>
    <td style="text-align: right;">{{ number_format($remun->direksi,2) }}</td>
    <td style="text-align: right;">{{ number_format($remun->staf,2) }}</td>
    <td style="text-align: right;">{{ number_format($remun->admin,2) }}</td>
    <td style="text-align: right;">{{ number_format($remun->indeks,2) }}</td>
    <td style="text-align: right;">{{ number_format($remun->medis_perawat,2) }}</td>
  </tr>
  <tr>
    <td></td>
    <td style="text-align: right;">Konversi Keuangan</td>
    <td style="text-align: right;">{{ number_format($remun->r_jp,2) }}</td>
    <td style="text-align: right;">{{ number_format($remun->r_penghasil,2) }}</td>
    <td style="text-align: right;">{{ number_format($remun->r_nonpenghasil,2) }}</td>
    <td style="text-align: right;">{{ number_format($remun->r_pos_remun,2) }}</td>
    <td style="text-align: right;">{{ number_format($remun->tpp,2) }}</td>
    <td style="text-align: right;">{{ number_format($remun->r_indek,2) }}</td>
    <td style="text-align: right;">{{ number_format($remun->r_direksi,2) }}</td>
    <td style="text-align: right;">{{ number_format($remun->r_staf,2) }}</td>
    <td style="text-align: right;">{{ number_format($remun->r_admin,2) }}</td>
    <td style="text-align: right;">{{ number_format($remun->r_indeks,2) }}</td>
    <td style="text-align: right;">{{ number_format($remun->r_medis_perawat,2) }}</td>
  </tr>
</table>
    
<table>
  <tr>
    <td rowspan="3" width="40" style="font-weight: bold; text-align: center;">NAMA KARYAWAN</td>
    <td rowspan="3" width="40" style="font-weight: bold; text-align: center;">RUANG</td>
    <td rowspan="3" width="40" style="font-weight: bold; text-align: center;">BAGIAN</td>
    <td rowspan="3" width="15" style="font-weight: bold; text-align: center;">STATUS</td>    
    <td rowspan="3" width="15" style="font-weight: bold; text-align: center;">SCORE</td>
    <td colspan="3" style="font-weight: bold; text-align: center;">POS REMUN</td>
    <td colspan="2" style="font-weight: bold; text-align: center;">DIREKSI</td>
    <td colspan="2" style="font-weight: bold; text-align: center;">STAF DIREKSI</td>
    <td colspan="2" style="font-weight: bold; text-align: center;">ADMINISTRASI</td>
    <td colspan="2" style="font-weight: bold; text-align: center;">TOTAL INDEK</td>
    <td colspan="6" style="font-weight: bold; text-align: center;">MEDIS/PERAWAT SETARA</td>
    <td colspan="2" style="font-weight: bold; text-align: center;">JASA PELAYANAN</td>
    <td rowspan="3" width="15" style="font-weight: bold; text-align: center;">PAJAK</td>
    <td rowspan="3" width="15" style="font-weight: bold; text-align: center;">NOMINAL PAJAK</td>
    <td rowspan="3" width="15" style="font-weight: bold; text-align: center;">JP DITERIMA</td>
  </tr>
  <tr>
    <td width="15" style="text-align: center; font-weight: bold;" rowspan="2">PERHITUNGAN</td>
    <td width="15" style="text-align: center; font-weight: bold;" colspan="2">CONVERSI</td>
    <td rowspan="2" width="15" style="font-weight: bold; text-align: center;">PERHITUNGAN</td>
    <td rowspan="2" width="15" style="font-weight: bold; text-align: center;">KONVERSI</td>
    <td rowspan="2" width="15" style="font-weight: bold; text-align: center;">PERHITUNGAN</td>
    <td rowspan="2" width="15" style="font-weight: bold; text-align: center;">KONVERSI</td>
    <td rowspan="2" width="15" style="font-weight: bold; text-align: center;">PERHITUNGAN</td>
    <td rowspan="2" width="15" style="font-weight: bold; text-align: center;">KONVERSI</td>
    <td rowspan="2" width="15" style="font-weight: bold; text-align: center;">PERHITUNGAN</td>
    <td rowspan="2" width="15" style="font-weight: bold; text-align: center;">KONVERSI</td>
    <td colspan="3" width="15" style="font-weight: bold; text-align: center;">PERHITUNGAN</td>
    <td colspan="3" width="15" style="font-weight: bold; text-align: center;">KONVERSI</td>
    <td rowspan="2" width="15" style="font-weight: bold; text-align: center;">PERHITUNGAN</td>
    <td rowspan="2" width="15" style="font-weight: bold; text-align: center;">KONVERSI</td>
  </tr>
  <tr>
    <td width="15" style="text-align: center; font-weight: bold;">TPP</td>
    <td width="15" style="text-align: center; font-weight: bold;">INDEK</td>
    <td width="15" style="text-align: center; font-weight: bold;">JASA</td>
    <td width="15" style="text-align: center; font-weight: bold;">TITIPAN</td>
    <td width="15" style="text-align: center; font-weight: bold;">JUMLAH</td>
    <td width="15" style="text-align: center; font-weight: bold;">JASA</td>
    <td width="15" style="text-align: center; font-weight: bold;">TITIPAN</td>
    <td width="15" style="text-align: center; font-weight: bold;">JUMLAH</td>
  </tr>
  @foreach($rincian as $rinc)
  <tr>
    <td>{{ $rinc->nama }}</td>                            
    <td>{{ strtoupper($rinc->ruang) }}</td>
    <td>{{ strtoupper($rinc->bagian) }}</td>
    <td>{{ $rinc->status }}</td>    
    <td align="right">{{ number_format($rinc->score,2) }}</td>
    <td align="right">{{ number_format($rinc->pos_remun,2) }}</td>
    <td align="right">{{ number_format($rinc->tpp,2) }}</td>
    <td align="right">{{ number_format($rinc->r_indek,2) }}</td>              
    <td align="right">{{ number_format($rinc->direksi,2) }}</td>
    <td align="right">{{ number_format($rinc->r_direksi,2) }}</td>
    <td align="right">{{ number_format($rinc->staf_direksi,2) }}</td>
    <td align="right">{{ number_format($rinc->r_staf_direksi,2) }}</td>
    <td align="right">{{ number_format($rinc->administrasi,2) }}</td>
    <td align="right">{{ number_format($rinc->r_administrasi,2) }}</td>
    <td align="right">{{ number_format($rinc->total_indek,2) }}</td>
    <td align="right">{{ number_format($rinc->r_total_indek,2) }}</td>
    <td align="right">{{ number_format($rinc->medis,2) }}</td>
    <td align="right">{{ number_format($rinc->titipan,2) }}</td>
    <td align="right">{{ number_format($rinc->jumlah,2) }}</td>
    <td align="right">{{ number_format($rinc->r_medis,2) }}</td>
    <td align="right">{{ number_format($rinc->titipan,2) }}</td>
    <td align="right">{{ number_format($rinc->r_jumlah,2) }}</td>
    <td align="right">{{ number_format($rinc->jasa_pelayanan,2) }}</td>
    <td align="right">{{ number_format($rinc->r_jasa_pelayanan,2) }}</td>
    <td align="right">{{ number_format($rinc->pajak,2) }} %</td>
    <td align="right">{{ number_format($rinc->nominal_pajak,2) }}</td>
    <td align="right">{{ number_format($rinc->sisa,2) }}</td>
  </tr>
  @endforeach  
  <tr>
    <td colspan="4" style="font-weight: bold; text-align: center;">JUMLAH</td>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->pos_remun,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->tpp,0) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->r_indek,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->direksi,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->r_direksi,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->staf_direksi,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->r_staf_direksi,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->administrasi,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->r_administrasi,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->total_indek,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->r_total_indek,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->medis,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->titipan,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->jumlah,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->r_medis,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->titipan,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->r_jumlah,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->jasa_pelayanan,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->r_jasa_pelayanan,2) }}</th>
    <th style="font-weight: bold; text-align: right;"></th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->nominal_pajak,2) }}</th>
    <th style="font-weight: bold; text-align: right;">{{ number_format($jumlah->sisa,2) }}</th>
  </tr>
</table>