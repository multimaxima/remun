<table>
  <tr>
    <td rowspan="3" width="40" style="font-weight: bold; text-align: center;">NAMA KARYAWAN</td>
    <td rowspan="3" width="30" style="font-weight: bold; text-align: center;">PENDIDIKAN</td>
    <td colspan="7" style="font-weight: bold; text-align: center;">INDEKS DASAR</td>
    <td colspan="7" style="font-weight: bold; text-align: center;">INDEKS KOMPETENSI</td>
    <td style="font-weight: bold; text-align: center;" width="30" rowspan="3">TEMPAT TUGAS</td>
    <td style="font-weight: bold; text-align: center;" colspan="3">INDEKS RESIKO</td>
    <td style="font-weight: bold; text-align: center;" colspan="3">INDEKS KEGAWATDARURATAN</td>
    <td style="font-weight: bold; text-align: center;" width="30" rowspan="3">JABATAN</td>
    <td style="font-weight: bold; text-align: center;" colspan="7">INDEKS JABATAN</td>
    <td style="font-weight: bold; text-align: center;" colspan="3">INDEKS PERFORMANCE</td>
    <td style="font-weight: bold; text-align: center;" rowspan="3" width="20">TOTAL SCORE</td>
  </tr>
  <tr>
    <td style="font-weight: bold; text-align: center;" colspan="3">KOREKSI</td>
    <td style="font-weight: bold; text-align: center;" colspan="3">MASA KERJA</td>
    <td style="font-weight: bold; text-align: center;" rowspan="2">S</td>
    <td style="font-weight: bold; text-align: center;" colspan="3">PENDIDIKAN</td>
    <td style="font-weight: bold; text-align: center;" colspan="3">DIKLAT</td>
    <td style="font-weight: bold; text-align: center;" rowspan="2">JML</td>
    <td style="font-weight: bold; text-align: center;" rowspan="2">N</td>
    <td style="font-weight: bold; text-align: center;" rowspan="2">B</td>
    <td style="font-weight: bold; text-align: center;" rowspan="2">S</td>
    <td style="font-weight: bold; text-align: center;" rowspan="2">N</td>
    <td style="font-weight: bold; text-align: center;" rowspan="2">B</td>
    <td style="font-weight: bold; text-align: center;" rowspan="2">S</td>
    <td style="font-weight: bold; text-align: center;" colspan="3">JABATAN</td>
    <td style="font-weight: bold; text-align: center;" colspan="3">KEPANITIAAN</td>
    <td style="font-weight: bold; text-align: center;" rowspan="2">JML</td>
    <td style="font-weight: bold; text-align: center;" rowspan="2">N</td>
    <td style="font-weight: bold; text-align: center;" rowspan="2">B</td>
    <td style="font-weight: bold; text-align: center;" rowspan="2">S</td>    
  </tr>
  <tr>
    <td style="font-weight: bold; text-align: center;">N</td>
    <td style="font-weight: bold; text-align: center;">B</td>
    <td style="font-weight: bold; text-align: center;">S</td>
    <td style="font-weight: bold; text-align: center;">N</td>
    <td style="font-weight: bold; text-align: center;">B</td>
    <td style="font-weight: bold; text-align: center;">S</td>
    <td style="font-weight: bold; text-align: center;">N</td>
    <td style="font-weight: bold; text-align: center;">B</td>
    <td style="font-weight: bold; text-align: center;">S</td>
    <td style="font-weight: bold; text-align: center;">N</td>
    <td style="font-weight: bold; text-align: center;">B</td>
    <td style="font-weight: bold; text-align: center;">S</td>
    <td style="font-weight: bold; text-align: center;">N</td>
    <td style="font-weight: bold; text-align: center;">B</td>
    <td style="font-weight: bold; text-align: center;">S</td>
    <td style="font-weight: bold; text-align: center;">N</td>
    <td style="font-weight: bold; text-align: center;">B</td>
    <td style="font-weight: bold; text-align: center;">S</td>
  </tr>
  @foreach($karyawan as $karyawan)
  <tr>              
    <td>{{ $karyawan->nama }}</td>
    <td>{{ $karyawan->pendidikan }}</td>
    <td align="right" class="min">{{ number_format($karyawan->indeks_dasar,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->dasar_bobot,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->skor_indek,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->masa_kerja,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->masa_kerja_bobot,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->indeks_masa_kerja,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->skor_dasar,2) }}</td>    
    <td align="right" class="min">{{ number_format($karyawan->pend_nilai,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->pend_bobot,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->skor_pend,2) }}</td>              
    <td align="right" class="min">{{ number_format($karyawan->diklat_nilai,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->diklat_bobot,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->skor_diklat,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->indeks_komp,2) }}</td>
    <td>{{ $karyawan->temp_tugas }}</td>
    <td align="right" class="min">{{ number_format($karyawan->resiko_nilai,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->resiko_bobot,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->indeks_resiko,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->gawat_nilai,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->gawat_bobot,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->indeks_kegawat,2) }}</td>
    <td>{{ $karyawan->jabatan }}</td>
    <td align="right" class="min">{{ number_format($karyawan->jab_nilai,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->jab_bobot,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->skor_jab,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->panitia_nilai,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->panitia_bobot,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->skor_pan,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->indeks_jabatan,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->perform_nilai,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->perform_bobot,2) }}</td>
    <td align="right" class="min">{{ number_format($karyawan->indeks_perform,2) }}</td>    
    <td align="right">{{ number_format($karyawan->total_indeks,2) }}</td>
  </tr>
  @endforeach
</table>     