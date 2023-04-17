<!DOCTYPE html>
<html lang="id_ID" moznomarginboxes mozdisallowselectionprint>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>RSUD Genteng - @yield('title')</title>
  <meta content="Admin Dashboard" name="description" />
  <meta content="Themesbrand" name="author" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />

  <link rel="shortcut icon" type="image/x-icon" href="/images/fav.png"> 

  {!! Html::style('/bootstrap/css/bootstrap-cetak.css') !!}
  @yield('style')
</head>
<body class="cetak">
  <table width="100%" style="line-height: 15px;">
    <tr>
      <td width="65" rowspan="5">
        <img src="/images/logo.png" width="100%">
      </td>
      <td style="padding-left: 20px;">
        <span style="font-size: 30px; font-weight: bold;">{{ strtoupper($a_param->alias) }} {{ strtoupper($a_param->kota) }}</span>            
      </td>
    </tr>
    <tr>
      <td style="padding-left: 20px; font-size: 12px;">
        {{ $a_param->alamat }}, Kecamatan {{ $a_param->kecamatan }}, {{ ucwords(strtolower($a_param->kota)) }}, {{ ucwords(strtolower($a_param->propinsi)) }}
      </td>
    </tr>
    <tr>
      <td style="padding-left: 20px; font-size: 12px;">Email : {{ strtolower($a_param->email) }}</td>
    </tr>
    <tr>
      <td style="padding-left: 20px; font-size: 12px;">Website : {{ $a_param->web }}</td>
    </tr>
    <tr>
      <td style="padding-left: 20px; font-size: 12px;">Telp. {{ $a_param->telp }} - Fax. {{ $a_param->fax }}</td>
    </tr>    
  </table>

  <hr style="border-top-width: 1px; border-color: #000000; margin-bottom: 0;">
  <hr style="border-top-width: 3px; border-color: #000000; margin-top: 3px;">
  @yield('content')      

  <script src="/bootstrap/js/jquery.table.marge.js"></script>
  @yield('script')
</body>
</html>