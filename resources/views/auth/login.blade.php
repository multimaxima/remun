<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <title>RSUD Genteng Banyuwangi</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" type="image/x-icon" href="/images/fav.png">
  <link rel="stylesheet" type="text/css" href="/frontend/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="/frontend/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="/frontend/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
  <link rel="stylesheet" type="text/css" href="/frontend/vendor/animate/animate.css">
  <link rel="stylesheet" type="text/css" href="/frontend/vendor/css-hamburgers/hamburgers.min.css">
  <link rel="stylesheet" type="text/css" href="/frontend/vendor/animsition/css/animsition.min.css">
  <link rel="stylesheet" type="text/css" href="/frontend/vendor/select2/select2.min.css">
  <link rel="stylesheet" type="text/css" href="/frontend/vendor/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" type="text/css" href="/frontend/css/util.css">
  <link rel="stylesheet" type="text/css" href="/frontend/css/main.css">
</head>
<body style="background-color: #666666;">
  
  <div class="limiter">
    <div class="container-login100">
      <div class="wrap-login100">
        <form class="login100-form validate-form" method="POST" action="{{ route('login') }}" style="background-image: url('/images/login_side.jpg'); background-size: 100% 100%;">
        @csrf

          <table width="100%">
            <tr>
              <td rowspan="2" width="50">
                <img src="/images/logo.png" width="100%">
              </td>
              <td style="padding-left: 10px;">
                <label style="font-size: 46px; font-weight: bold; color: white; text-shadow: 2px 2px 8px #000000; line-height: 2px;">RSUD GENTENG</label>
              </td>
            </tr>
            <tr>
              <td>
                <label style="font-size: 20px; font-weight: bold; letter-spacing: 20px; padding-left: 15px; line-height: 1px; text-shadow: 4px 4px 4px #aaa;">BANYUWANGI</label>
              </td>
            </tr>
          </table>
          
          <span class="login100-form-title p-b-20" style="margin-top: 10vh;">
            LOGIN PETUGAS
          </span>

          <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
            <input id="username" type="text" class="input100 @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autofocus>
            <span class="focus-input100"></span>
            <span class="label-input100">Username</span>

            @error('username')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          
          <div class="wrap-input100 validate-input" data-validate="Password is required">
            <input id="password" type="password" class="input100 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">            
            <span class="focus-input100"></span>
            <span class="label-input100">Password</span>

            @error('password')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="flex-sb-m w-full p-t-3 p-b-32">
            <div class="contact100-form-checkbox">
              <input class="input-checkbox100" type="checkbox" name="remember" id="ckb1" {{ old('remember') ? 'checked' : '' }}>
              <label class="label-checkbox100" for="ckb1">
                Ingat Saya
              </label>
            </div>
          </div>
      

          <div class="container-login100-form-btn">
            <button class="login100-form-btn">
              Login
            </button>
          </div>
        </form>

        <div class="login100-more" style="background-image: url('/images/login.jpg'); box-shadow: 0 0 15px 1px;">
        </div>
      </div>
    </div>
  </div>

  <script src="/frontend/vendor/jquery/jquery-3.2.1.min.js"></script>
  <script src="/frontend/vendor/animsition/js/animsition.min.js"></script>
  <script src="/frontend/vendor/bootstrap/js/popper.js"></script>
  <script src="/frontend/vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="/frontend/vendor/select2/select2.min.js"></script>
  <script src="/frontend/vendor/daterangepicker/moment.min.js"></script>
  <script src="/frontend/vendor/daterangepicker/daterangepicker.js"></script>
  <script src="/frontend/vendor/countdowntime/countdowntime.js"></script>
  <script src="/frontend/js/main.js"></script>
</body>
</html>