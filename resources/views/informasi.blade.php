@extends('layouts.content')
@section('title','Informasi')

@section('content')
<div style="background-image: url('/images/informasi.jpg'); background-size: 100% 100%; padding: 50px; height: 75vh;">
  <img src="/images/web_logo.png" width="500">
  
  <table width="100%" style="font-size: 14px; margin-top: 15px; color: white;">
    <tr>
      <td width="3%">
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
      <td><a style="color: white;" href="mailto:{{ $a_param->email }}">{{ strtolower($a_param->email) }}</a></td>
    </tr>
    <tr>
      <td><i class="fa fa-globe"></i></td>
      <td><a style="color: white;" href="{{ $a_param->web }}">{{ strtolower($a_param->web) }}</a></td>
    </tr>
    <tr>
      <td colspan="2" style="padding-top: 10px; font-size: 20px; letter-spacing: 10px;">
        <a style="color: white;" href="{{ $a_param->facebook }}" target="_blank"><i class="fab fa-facebook"></i></a>
        <a style="color: white;" href="{{ $a_param->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
        <a style="color: white;" href="{{ $a_param->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
        <a style="color: white;" href="{{ $a_param->google }}" target="_blank"><i class="fab fa-google"></i></a>
        <a style="color: white;" href="{{ $a_param->youtube }}" target="_blank"><i class="fab fa-youtube"></i></a>
        <a style="color: white;" href="{{ $a_param->likedin }}" target="_blank"><i class="fab fa-linkedin"></i></a>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="font-weight: bold; padding-top: 50px; padding-bottom: 5px; letter-spacing: 5px;">SUPPORT</td>
    </tr>
    <tr>
      <td colspan="2" style="padding-bottom: 10px;">
        <img src="/images/mmx.png" width="250">
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
      <td><a style="color: white;" href="mailto:support@multimaxima.com">support@multimaxima.com</a></td>
    </tr>
    <tr>
      <td><i class="fa fa-globe"></i></td>
      <td><a style="color: white;" href="https://www.multimaxima.com" target="_blank">https://www.multimaxima.com</a></td>
    </tr>
  </table>
</div>

@endsection