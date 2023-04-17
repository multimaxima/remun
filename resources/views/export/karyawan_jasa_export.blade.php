<table>
  <tr>      
    <td width="50" rowspan="2" style="font-weight: bold; text-align: center;">Nama Karyawan</td>
    <td colspan="5" style="font-weight: bold; text-align: center;">Non Penghasil</td>
    <td colspan="11" style="font-weight: bold; text-align: center;">Penghasil</td>
  </tr>
  <tr>
    <td width="30" style="font-weight: bold; text-align: center;">Pos Renumerasi</td>
    <td width="30" style="font-weight: bold; text-align: center;">Insentif Kel. Perawat / Setara</td>
    <td width="30" style="font-weight: bold; text-align: center;">Direksi</td>
    <td width="30" style="font-weight: bold; text-align: center;">Staf Direksi</td>
    <td width="30" style="font-weight: bold; text-align: center;">JP Langsung Administrasi</td>
    <td width="30" style="font-weight: bold; text-align: center;">JP Langsung Perawat Setara</td>
    <td width="30" style="font-weight: bold; text-align: center;">Apoteker</td>
    <td width="30" style="font-weight: bold; text-align: center;">Asisten Apoteker</td>
    <td width="30" style="font-weight: bold; text-align: center;">Admin Farmasi</td>
    <td width="30" style="font-weight: bold; text-align: center;">Penata Anastesi</td>
    <td width="30" style="font-weight: bold; text-align: center;">Perawat Asistensi 1</td>
    <td width="30" style="font-weight: bold; text-align: center;">Perawat Asistensi 2</td>
    <td width="30" style="font-weight: bold; text-align: center;">Instrumen</td>
    <td width="30" style="font-weight: bold; text-align: center;">Sirkuler</td>                    
    <td width="30" style="font-weight: bold; text-align: center;">Perawat Pendamping 1</td>      
    <td width="30" style="font-weight: bold; text-align: center;">Perawat Pendamping 2</td>      
  </tr>
  @foreach($karyawan as $karyawan)
  <tr>
    <td>{{ $karyawan->nama }}</td>
    <td align="center">
      @if($karyawan->pos_remun == 1)
        YA
      @endif
    </td>
    <td align="center">
      @if($karyawan->insentif_perawat == 1)
        YA
      @endif
    </td>
    <td align="center">
      @if($karyawan->direksi == 1)
        YA
      @endif
    </td>
    <td align="center">
      @if($karyawan->staf == 1)
        YA
      @endif
    </td>
    <td align="center">
      @if($karyawan->jp_admin == 1)
        YA
      @endif
    </td>
    <td align="center">
      @if($karyawan->jp_perawat == 1)
        YA
      @endif
    </td>
    <td align="center">
      @if($karyawan->apoteker == 1)
        YA
      @endif
    </td>
    <td align="center">
      @if($karyawan->ass_apoteker == 1)
        YA
      @endif
    </td>
    <td align="center">
      @if($karyawan->admin_farmasi == 1)
        YA
      @endif
    </td>
    <td align="center">
      @if($karyawan->pen_anastesi == 1)
        YA
      @endif
    </td>
    <td align="center">
      @if($karyawan->per_asisten_1 == 1)
        YA
      @endif
    </td>
    <td align="center">
      @if($karyawan->per_asisten_2 == 1)
        YA
      @endif
    </td>
    <td align="center">
      @if($karyawan->instrumen == 1)
        YA
      @endif
    </td>
    <td align="center">
      @if($karyawan->sirkuler == 1)
        YA
      @endif
    </td>
    <td align="center">
      @if($karyawan->per_pendamping_1 == 1)
        YA
      @endif
    </td>
    <td align="center">
      @if($karyawan->per_pendamping_2 == 1)
        YA
      @endif
    </td>
  </tr>
  @endforeach
</table>