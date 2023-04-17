<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover, shrink-to-fit=no">
  <meta name="description" content="Suha - Multipurpose Ecommerce Mobile HTML Template">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="theme-color" content="#100DD1">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <title>RSUD Genteng Banyuwangi</title>
  <link rel="stylesheet" href="/mobile/fonts/font.css">
  <link rel="stylesheet" href="/mobile/css/bootstrap.min.css">
  <link rel="stylesheet" href="/mobile/css/animate.css">
  <link rel="stylesheet" href="/mobile/css/owl.carousel.min.css">
  <link rel="stylesheet" href="/mobile/css/font-awesome.min.css">
  <link rel="stylesheet" href="/mobile/css/default/lineicons.min.css">
  <link rel="stylesheet" href="/mobile/style.css">
  <link rel="manifest" href="/mobile/manifest.json">
</head>
<body>
  <div class="preloader" id="preloader">
    <div class="spinner-grow text-secondary" role="status">
      <div class="sr-only">Loading...</div>
    </div>
  </div>

  <div class="login-wrapper d-flex align-items-center justify-content-center text-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-9 col-md-7 col-lg-6 col-xl-5">
          <img class="big-logo" src="/images/logo.png" width="25%">
          <label style="font-size: 10vw; font-weight: bold; color: white; text-shadow: 2px 2px 8px #000000;">RSUD GENTENG</label>
          <label style="font-size: 3vw; font-weight: bold; letter-spacing: 20px; padding-left: 15px; text-shadow: 4px 4px 4px #aaa; color: black;">BANYUWANGI</label>
          <div class="register-form mt-5 px-4">
            <form method="POST" action="{{ route('login') }}" style="padding: 0 5vw;">
            @csrf
              
              <div class="form-group text-start mb-4"><span>Username</span>
                <label for="username"><i class="lni lni-user"></i></label>
                <input class="form-control" id="username" type="text" placeholder="Username" name="username" required autofocus oninvalid="setCustomValidity('Masukkan username Anda.')">

                @error('username')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
              
              <div class="form-group text-start mb-4"><span>Password</span>
                <label for="password"><i class="lni lni-lock"></i></label>
                <input class="form-control" id="password" type="password" placeholder="Password" name="password" required oninvalid="setCustomValidity('Masukkan password Anda.')">

                @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>

              <div class="form-group text-start" style="margin-bottom: 5vh;">
                <label for="remember">Ingat Login Saya</label>
                <input type="checkbox" name="remember" id="remember" value="{{ old('remember') ? 'checked' : '' }}">                
              </div>

              <button class="btn btn-success btn-lg w-100" type="submit">LOGIN</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="/mobile/js/bootstrap.bundle.min.js"></script>
  <script src="/mobile/js/jquery.min.js"></script>
  <script src="/mobile/js/waypoints.min.js"></script>
  <script src="/mobile/js/jquery.easing.min.js"></script>
  <script src="/mobile/js/owl.carousel.min.js"></script>
  <script src="/mobile/js/jquery.counterup.min.js"></script>
  <script src="/mobile/js/jquery.countdown.min.js"></script>
  <script src="/mobile/js/default/jquery.passwordstrength.js"></script>
  <script src="/mobile/js/default/dark-mode-switch.js"></script>
  <script src="/mobile/js/default/active.js"></script>
  <script src="/mobile/js/pwa.js"></script>
</body>
</html>