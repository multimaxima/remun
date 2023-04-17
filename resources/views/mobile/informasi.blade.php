@extends('mobile.layouts.content')

@section('bawah')
  <li><a href="{{ route('jasa_remun') }}"><i class="fa fa-heart"></i>Remunerasi</a></li>
  <li><a href="{{ route('informasi_software') }}"><i class="fa fa-info"></i>Informasi</a></li>
@endsection

@section('content')
<div style="margin-top: 8vh;">
  <div class="row isi">
    <div class="container-fluid">
      <div class="card" style="margin-top: 1vh;">
        <div class="card-body" style="font-size: 3vw; background-image: url('/images/informasi.jpg'); background-size: 100% 100%;"> 
          <center>
            <img src="/images/web_logo.png" width="100%">
          </center>
          <table width="100%" style="font-size: 14px; margin-top: 15px; color: white;">
            <tr>
              <td width="8%" style="vertical-align: top;">
                <i class="fa fa-home"></i>
              </td>
              <td>
                {{ ucwords(strtolower($a_param->alamat)) }}, Kecamatan {{ ucwords(strtolower($a_param->kecamatan)) }}, {{ ucwords(strtolower($a_param->kota)) }}, Propinsi {{ ucwords(strtolower($a_param->propinsi)) }}
              </td>
            </tr>
            <tr>
              <td><i class="fa fa-phone"></i></td>
              <td>{{ $a_param->telp }}</td>
            </tr>
            <tr>
              <td><i class="fa fa-fax"></i></td>
              <td>{{ $a_param->fax }}</td>
            </tr>
            <tr>
              <td><i class="fa fa-envelope"></i></td>
              <td>{{ strtolower($a_param->email) }}</td>
            </tr>
            <tr>
              <td><i class="fa fa-globe"></i></td>
              <td>{{ strtolower($a_param->web) }}</td>
            </tr>            
            <tr>
              <td colspan="2" style="font-weight: bold; padding-top: 15vh; padding-bottom: 5px; letter-spacing: 5px; text-align: center;">SUPPORT OLEH</td>
            </tr>
            <tr>
              <td colspan="2" style="padding-bottom: 10px;">
                <center>
                  <img src="/images/mmx.png" width="70%">
                </center>
              </td>
            </tr>
            <tr>
              <td><i class="fa fa-phone"></i></td>
              <td>081279999945</td>
            </tr>
            <tr>
              <td></td>
              <td>081996114777</td>
            </tr>
            <tr>
              <td><i class="fa fa-envelope"></i></td>
              <td>support@multimaxima.com</td>
            </tr>
            <tr>
              <td><i class="fa fa-globe"></i></td>
              <td>https://www.multimaxima.com</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection