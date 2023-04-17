<table>
  <tr>
    <td style="font-weight: bold; text-align: center;">NAMA PASIEN</td>
    <td widtd="60" style="font-weight: bold; text-align: center;">MR</td>
    <td style="font-weight: bold; text-align: center;">REGISTER</td>
    <td style="font-weight: bold; text-align: center;">JENIS</td>
    <td style="font-weight: bold; text-align: center;">ALAMAT</td>
    <td style="font-weight: bold; text-align: center;">MASUK</td>
    <td style="font-weight: bold; text-align: center;">RUANG</td>
  </tr>
  @foreach($pasien as $pas)  
    <tr>              
      <td>{{ strtoupper($pas->nama) }}</td>
      <td class="min" align="center">{{ $pas->no_mr }}</td>
      <td class="min" align="center">{{ $pas->register }}</td>
      <td>{{ strtoupper($pas->jenis_pasien) }}</td>
      <td>{{ $pas->alamat }}</td>
      <td class="min">{{ $pas->masuk }}</td>                
      <td>{{ strtoupper($pas->ruang) }}</td>                
    </tr>              
  @endforeach
</table>