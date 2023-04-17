@if (session('error'))
  <div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session('error') }}
  </div>
@endif
    
@if (session('success'))
  <div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    {{ session('success') }}
  </div>
@endif