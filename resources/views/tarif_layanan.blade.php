@extends('layouts.content')
@section('title','Tarif Layanan')

@section('judul')
  <h4 class="page-title"> <i class="dripicons-pulse"></i> @yield('title')</h4>
@endsection

@section('content')
<div class="wrapper">
  <div class="col-12" style="padding: 0 50px;">
    <div class="card m-b-30 konten">
      <div class="card-body">   
        <form class="form-inline col-6 offset-3" method="GET" action="{{ route('tarif') }}">
        @csrf
            <label>Jenis Pasien : </label>
              <select class="form-control" name="jns" onchange="this.form.submit();" style="margin: 0 10px; width: 400px;">
                <option value=""></option>
                @foreach($jenis as $jenis)
                  <option value="{{ $jenis->id }}" {{ $jenis->id == $jns? 'selected' : null }}>{{ $jenis->jenis_pasien }}</option>
                @endforeach
              </select>
        </form>
      </div>      
    </div>
  </div>
</div>

@yield('tarif')

@if($jns)
<div class="modal fade" id="perhitungan_baru" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Tarif</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal fprev" id="baru_perhitungan" method="POST" action="{{ route('tarif_1_baru') }}">
        @csrf
          <input type="hidden" name="id_jenis_pasien" value="{{ $jns }}">

          <div class="form-group row">
            <label class="control-label col-4">Jasa</label>
            <div class="col-7">
              <select class="form-control" name="id_jasa" required autofocus>
                <option value=""></option>
                @foreach($jasa as $jas)
                  <option value="{{ $jas->id }}">{{ $jas->jasa }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">                
        <button type="submit" form="baru_perhitungan" class="btn btn-primary bprev">
          <i class="fa fa-save"></i> SIMPAN
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fa fa-times"></i> TUTUP
        </button>
      </div>
    </div>
  </div>
</div>  
@endif

@if($hitung)
<div class="modal fade" id="perhitungan_1_baru" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Tarif</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal fprev" id="baru_perhitungan_1" method="POST" action="{{ route('tarif_2_baru') }}">
        @csrf
          <input type="hidden" name="id_perhitungan" value="{{ $hitung->id }}">

          <div class="form-group row">
            <label class="control-label col-4">Rekening</label>
            <div class="col-7">
              <select class="form-control" name="id_rekening" required autofocus>
                <option value=""></option>
                @foreach($rekening as $rek)
                  <option value="{{ $rek->id }}">{{ $rek->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="control-label col-4">Nilai</label>
            <div class="col-7">
              <input type="number" name="nilai" step="0.01" class="form-control" required>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">                
        <button type="submit" form="baru_perhitungan_1" class="btn btn-primary bprev">
          <i class="fa fa-save"></i> SIMPAN
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fa fa-times"></i> TUTUP
        </button>
      </div>
    </div>
  </div>
</div>  
@endif

@if($hitung_1)
<div class="modal fade" id="perhitungan_2_baru" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Tarif</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal fprev" id="baru_perhitungan_2" method="POST" action="{{ route('tarif_3_baru') }}">
        @csrf
          <input type="hidden" name="id_perhitungan" value="{{ $hitung->id }}">
          <input type="hidden" name="id_perhitungan_1" value="{{ $hitung_1->id }}">

          <div class="form-group row">
            <label class="control-label col-4">Rekening</label>
            <div class="col-7">
              <select class="form-control" name="id_rekening" required autofocus>
                <option value=""></option>
                @foreach($rekening as $rek)
                  <option value="{{ $rek->id }}">{{ $rek->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="control-label col-4">Nilai</label>
            <div class="col-7">
              <input type="number" name="nilai" step="0.01" class="form-control" required>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">                
        <button type="submit" form="baru_perhitungan_2" class="btn btn-primary bprev">
          <i class="fa fa-save"></i> SIMPAN
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fa fa-times"></i> TUTUP
        </button>
      </div>
    </div>
  </div>
</div>   
@endif

@if($hitung_2)
<div class="modal fade" id="perhitungan_3_baru" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Tarif</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal fprev" id="baru_perhitungan_3" method="POST" action="{{ route('tarif_4_baru') }}">
        @csrf
          <input type="hidden" name="id_perhitungan" value="{{ $hitung->id }}">
          <input type="hidden" name="id_perhitungan_1" value="{{ $hitung_1->id }}">
          <input type="hidden" name="id_perhitungan_2" value="{{ $hitung_2->id }}">

          <div class="form-group row">
            <label class="control-label col-4">Rekening</label>
            <div class="col-7">
              <select class="form-control" name="id_rekening" required autofocus>
                <option value=""></option>
                @foreach($rekening as $rek)
                  <option value="{{ $rek->id }}">{{ $rek->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="control-label col-4">Nilai</label>
            <div class="col-7">
              <input type="number" name="nilai" step="0.01" class="form-control" required>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">                
        <button type="submit" form="baru_perhitungan_3" class="btn btn-primary bprev">
          <i class="fa fa-save"></i> SIMPAN
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fa fa-times"></i> TUTUP
        </button>
      </div>
    </div>
  </div>
</div>
@endif
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('#tabel1').DataTable( {        
        "stateSave": true,      
        "searching": false,
        "info": false,
        "sort": false,
        "scrollY": "550px",
        "scrollCollapse": true,
        "paging":  false
      });

      $('#tabel2').DataTable( {        
        "stateSave": true,      
        "searching": false,
        "info": false,
        "sort": false,
        "scrollY": "550px",
        "scrollCollapse": true,
        "paging":  false
      });

      $('#tabel3').DataTable( {        
        "stateSave": true,      
        "searching": false,
        "info": false,
        "sort": false,
        "scrollY": "550px",
        "scrollCollapse": true,
        "paging":  false
      });

      $('#tabel4').DataTable( {        
        "stateSave": true,      
        "searching": false,
        "info": false,
        "sort": false,
        "scrollY": "550px",
        "scrollCollapse": true,
        "paging":  false
      });
    });
  </script>
@endsection