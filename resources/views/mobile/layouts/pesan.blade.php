<div class="row isi">  
  <div class="container" style="margin-top: 1vh; margin-bottom: -2vh;">
    @if (session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 3.5vw;">
        <i class="fa fa-times-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    
    @if (session('success'))
      <div class="alert alert-warning alert-dismissible fade show" role="alert" style="font-size: 3.5vw;">
        <i class="fa fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
  </div>
</div>