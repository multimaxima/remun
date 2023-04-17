@extends('layouts.content')
@section('title','Skema Tarif')

@section('content')
<div class="navbar" style="margin-bottom:5px;">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12" style="display: inline-flex;">
        <form class="form-inline" id="data" method="GET" action="{{ route('tarif_user') }}" style="margin-top: 5px; margin-bottom: 0;">
        @csrf

          <select name="jns" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">==PILIH JENIS PASIEN==</option>
            @foreach($jenis as $jenis)
              <option value="{{ $jenis->id }}" {{ $jenis->id == $jns? 'selected' : null }}>{{ strtoupper($jenis->jenis) }}</option>
            @endforeach
          </select>

          <select name="rwt" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">==PILIH JENIS PERAWATAN==</option>
            @foreach($rawat as $rawat)
              <option value="{{ $rawat->id }}" {{ $rawat->id == $rwt? 'selected' : null }}>{{ strtoupper($rawat->jenis_rawat) }}</option>
            @endforeach
          </select>          
        </form>

        @if($jns && $rwt)
        <button type="submit" form="cetak" class="btn btn-primary" style="margin-left: 5px;">CETAK</button>

        <form hidden method="GET" target="_blank" id="cetak" action="{{ route('tarif_cetak') }}">
        @csrf
          <input type="hidden" name="jns" value="{{ $jns }}">
          <input type="hidden" name="rwt" value="{{ $rwt }}">
          
        </form>
        @endif        
      </div>
    </div>
  </div>
</div>

@if($perhitungan && count($perhitungan) > 0)
<div class="content">
          @foreach($perhitungan as $hitung)     
          <table width="100%" class="table-bordered table-hover">
          <tr>            
            <td style="font-weight: bold; padding: 5px 10px;">{{ strtoupper($hitung->jasa) }}</td>
            <td width="70%" style="padding: 0; margin: 0;">
              <table width="100%" border="1">
                @foreach($hitung->perhitungan_1 as $hitung_1)
                  <tr>
                    <td style="padding: 5px 10px;">{{ strtoupper($hitung_1->nama) }}</td>
                    <td width="75" align="right" style="padding: 5px 10px;">{{ $hitung_1->nilai }} %</td>
                    <td width="80%" style="padding: 0; margin: 0;">
                      <table width="100%" style="border-top-style: none; border-bottom-style: none;" border="1">
                        @foreach($hitung_1->perhitungan_2 as $hitung_2)
                          <tr>
                            <td style="padding: 5px 10px;">{{ strtoupper($hitung_2->nama) }}</td>
                            <td width="75" align="right" style="padding: 5px 10px;">{{ $hitung_2->nilai }} %</td>
                            <td width="60%" style="padding: 0; margin: 0;">
                              <table width="100%" class="table-striped" style="font-size: 13px;" border="1">
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
</div>
@endif  
@endsection