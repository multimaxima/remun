@extends('mobile.layouts.content')

@section('bawah')
  <li><a href="{{ route('jasa_remun') }}"><i class="fa fa-heart"></i>Remunerasi</a></li>
  <li><a href="{{ route('informasi_software') }}"><i class="fa fa-info"></i>Informasi</a></li>
@endsection

@section('content')
<div style="margin-top: 8vh;">
  <div class="row isi">
    <div class="container-fluid">
      @foreach($remun as $remun)
      <div class="card" style="margin-top: 1vh;">
        <div class="card-body" style="font-size: 3vw;">       
          <table width="100%">
            <tr>
              <td width="40%">Tanggal Perhitungan</td>
              <td width="5%">:</td>
              <td>{{ strtoupper($remun->tanggal) }}</td>
            </tr>
            <tr>
              <td>Periode</td>
              <td>:</td>
              <td>{{ strtoupper($remun->awal) }} s/d {{ strtoupper($remun->akhir) }}</td>
            </tr>
            <tr>
              <td>Jenis</td>
              <td>:</td>
              <td>PASIEN {{ strtoupper($remun->jenis) }}</td>
            </tr>
            <tr>
              <td>Total JP</td>
              <td>:</td>
              <td>{{ number_format($remun->r_jp,2) }}</td>
            </tr>        
            <tr>
              <td colspan="3">
                <a href="{{ route('jasa_remun_rincian',Crypt::encrypt($remun->id)) }}" class="btn btn-info btn-sm w-100">
                  RINCIAN
                </a>
              </td>
            </tr>        
          </table>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>
@endsection