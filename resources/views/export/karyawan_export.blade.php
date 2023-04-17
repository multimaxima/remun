<table>
  <tr>
    <td style="font-weight: bold;" height="20" width="50">NAMA</td>
    <td style="font-weight: bold;" height="20" width="30">STATUS</td>
    <td style="font-weight: bold;" height="20" width="30">GOLONGAN</td>
    <td style="font-weight: bold;" height="20" width="30">NPWP</td>
    <td style="font-weight: bold;" height="20" width="10">PAJAK (%)</td>
    <td style="font-weight: bold;" height="20" width="30">BAGIAN</td>
    <td style="font-weight: bold;" height="20" width="30">RUANG</td>
    <td style="font-weight: bold;" height="20" width="20">MULAI KERJA</td>
    <td style="font-weight: bold;" height="20" width="20">MASA KERJA</td>
    <td style="font-weight: bold;" height="20" width="20">INDEKS MASA KERJA</td>
    <td style="font-weight: bold;" height="20" width="15">GAPOK (Rp.)</td>
    <td style="font-weight: bold;" height="20" width="15">INDEKS DASAR</td>
    <td style="font-weight: bold;" height="20" width="15">TPP (Rp.)</td>    
    <td style="font-weight: bold;" height="20" width="10">SCORE</td>
    <td style="font-weight: bold;" height="20" width="20">HAK AKSES</td>
    <td style="font-weight: bold;" height="20" width="20">REKENING</td>
  </tr>
    @foreach($karyawan as $kary)
    <tr>
      <td>{{ strtoupper($kary->nama) }}</td>
      <td>{{ strtoupper($kary->status) }}</td>
      <td>{{ strtoupper($kary->golongan) }}</td>
      <td>{{ strtoupper($kary->npwp) }}</td>
      <td align="right">{{ $kary->pajak }}</td>
      <td>{{ strtoupper($kary->bagian) }}</td>
      <td>{{ strtoupper($kary->ruang) }}</td>
      <td>{{ strtoupper($kary->mulai_kerja) }}</td>
      <td>{{ strtoupper($kary->masa_kerja) }}</td>
      <td align="right">{{ $kary->indeks_kerja }}</td>
      <td align="right">{{ $kary->gapok }}</td>
      <td align="right">{{ $kary->indeks_dasar }}</td>
      <td align="right">{{ $kary->tpp }}</td>      
      <td align="right">{{ $kary->skore }}</td>
      <td>{{ strtoupper($kary->akses) }}</td>
      <td>{{ $kary->rekening }}</td>
    </tr>
    @endforeach
</table>