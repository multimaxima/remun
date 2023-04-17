@extends('layouts.cetak')
@section('title','Cetak Skema Tarif')

@section('content')
<center>
  <label style="font-size: 18px; font-weight: bold; margin-bottom: 20px;">SKEMA TARIF PASIEN {{ strtoupper($jenis->jenis) }} {{ strtoupper($rawat->jenis_rawat) }}</label>
</center>
@foreach($perhitungan as $hitung)     
<table width="100%" class="table-bordered">
  <tr>            
    <td style="font-weight: bold; padding: 5px 10px;">{{ strtoupper($hitung->jasa) }}</td>
    <td width="70%" style="padding: 0; margin: 0;">
      <table width="100%" style="border: none;" border="1">
        @foreach($hitung->perhitungan_1 as $hitung_1)
        <tr>
          <td style="padding: 5px 10px;">{{ strtoupper($hitung_1->nama) }}</td>
          <td width="75" align="right" style="padding: 5px 10px;">{{ $hitung_1->nilai }} %</td>
          <td width="80%" style="padding: 0; margin: 0;">
            <table width="100%" style="border: none;" border="1">
              @foreach($hitung_1->perhitungan_2 as $hitung_2)
              <tr>
                <td style="padding: 5px 10px;">{{ strtoupper($hitung_2->nama) }}</td>
                <td width="75" align="right" style="padding: 5px 10px;">{{ $hitung_2->nilai }} %</td>
                <td width="60%" style="padding: 0; margin: 0;">
                  <table width="100%" style="font-size: 13px; border: none;" border="1">
                    @foreach($hitung_2->perhitungan_3 as $hitung_3)
                    <tr>
                      <td style="padding: 5px 10px;">{{ strtoupper($hitung_3->nama) }}</td>
                      <td width="75" align="right" style="padding: 5px 10px;">{{ number_format($hitung_3->nilai,2) }} %</td>
                    </tr>
                    @endforeach
                  </table>                              
                </td>
              </tr>
              @endforeach
            </table>
          </td>
        </tr>                  
        @endforeach                
      </table>
    </td>
  </tr>
</table>
@endforeach        
@endsection