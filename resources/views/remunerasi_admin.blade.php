@extends('layouts.content')
@section('title','Data Remunerasi')

@section('style')
  @if($cek->remun == 1)
    <meta http-equiv="refresh" content="10"/>
  @endif
@endsection

@section('content')
@if($cek->remun == 0)
<div class="navbar" id="nav_remun">
  <div class="navbar-inner">
    <div class="pull-left" style="display: inline-flex;">
      <div class="btn-group" style="margin-left: 5px;">      
        <button class="btn btn-primary" id="perhitungan_baru" data-toggle="modal" data-target="#data_baru">
          BUAT PERHITUNGAN REMUNERASI
        </button>
      </div>        
    </div>    
  </div>
</div>
@endif

<div class="content" id="process" style="display: none;">
  <center>
    Proses perhitungan remunerasi sedang berjalan
    <img src="/images/progress.gif" style="height: 30px;">
  </center>
</div>

@if($cek->remun == 1)
<div class="content">
  <center>
    Proses perhitungan remunerasi sedang berjalan
    <img src="/images/progress.gif" style="height: 30px;">
  </center>
</div>
@endif

<div class="content">
  <table id="tabel" width="100%" class="table table-hover table-striped table-bordered">
    <thead>
      <tr>
        <th rowspan="2"></th>
        <th rowspan="2" style="padding: 0 10px;">Tanggal Perhitungan</th>
        <th colspan="2" style="padding: 0 10px;">Periode</th>
        <th rowspan="2" style="padding: 0 10px;">Jenis Pasien</th>
        <th rowspan="2" style="padding: 0 10px;">Nominal JP</th>
        <th rowspan="2" style="padding: 0 10px;">Status</th>            
        <th rowspan="2" style="padding: 0 10px;">Waktu Perhitungan</th>
        <th rowspan="2" style="padding: 0 10px;">Petugas</th>
      </tr>
      <tr>
        <th style="padding: 0 10px;">Dari</th>
        <th style="padding: 0 10px;">Sampai</th>
      </tr>
    </thead>
    <tbody>
      @foreach($remun as $remun)       
      <tr>
        <td class="min">
          <form hidden method="GET" action="{{ route('remunerasi') }}" id="rincian{{ $remun->id }}">
          @csrf
            <input type="hidden" name="id" value="{{ $remun->id }}">                  
          </form>

          <button type="submit" form="rincian{{ $remun->id }}" class="btn btn-info btn-mini" title="Rincian">
            <i class="icon-list"></i>
          </button>
        </td>
        <td>{{ strtoupper($remun->tanggal) }}</td>
        <td style="text-align: center;">{{ strtoupper($remun->awal) }}</td>
        <td style="text-align: center;">{{ strtoupper($remun->akhir) }}</td>
        <td>{{ strtoupper($remun->jkn) }}</td>
        <td style="text-align: right;">{{ number_format($remun->a_jp,2) }}</td>              
        <td width="100">
          @if($remun->stat == 0)
            <img src="/images/proces.gif" style="width: 100%;">
          @else
            {{ strtoupper($remun->status) }}
          @endif
        </td>
        <td style="text-align: center;">{{ strtoupper($remun->waktu) }}</td>
        <td style="text-align: center;">{{ $remun->petugas }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>  

<div class="modal-lg hide fade" id="data_baru">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title">Perhitungan Remunerasi Baru</h5>
  </div>
  <div class="modal-body">
    <form class="form-horizontal" id="baru_data">
    @csrf

      <div class="control-group">
        <label class="control-label span3">Jenis Pasien</label>
        <div class="controls span7">
          <select class="form-control" name="jenis" id="jenis" required autofocus>
            <option value=""></option>
            <option value="1">PASIEN UMUM</option>
            <option value="2">PASIEN ASURANSI</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">TPP</label>
        <div class="controls span7">
          <select class="form-control" name="tpp" id="tpp" required>
            <option value=""></option>
            <option value="0">TANPA TPP</option>
            <option value="1">1 X TPP</option>
            <option value="2">2 X TPP</option>
            <option value="3">3 X TPP</option>
            <option value="4">4 X TPP</option>
            <option value="5">5 X TPP</option>
          </select>
        </div>
      </div>

      <div id="jkn">
        <div class="control-group">
          <label class="control-label span3">Claim Asuransi</label>
          <div class="controls span7">
            <select class="form-control" name="bpjs" id="bpjs">
              <option value=""></option>
              @foreach($bpjs as $bpjs)
                <option value="{{ $bpjs->id }}">{{ $bpjs->jenis }} - {{ $bpjs->dari }} s/d {{ $bpjs->sampai }} - Rp. {{ number_format($bpjs->claim,0) }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div id="umum">
        <div class="control-group">
          <label class="control-label span3">Tanggal</label>
          <div class="controls span8" style="display: inline-flex;">
            <input type="date" name="awal" id="awal" style="width: 145px;">
            <label style="margin: 5px 5px;">s/d</label>
            <input type="date" name="akhir" id="akhir" style="width: 145px;">
          </div>                
        </div>            
      </div>

      <div class="control-group">
        <label class="control-label span3">Titipan 1</label>
        <div class="controls span7">
          <select class="form-control select2" name="id_interhensif_1" style="width: 100%;">
            <option value=""></option>
            @foreach($interensif as $int)
              <option value="{{ $int->id }}">
                @if($int->gelar_depan)
                  {{ $int->gelar_depan }}
                @endif

                @if($int->gelar_belakang)
                  {{ $int->nama }} {{ $int->gelar_belakang }}
                @else
                  {{ $int->nama }}
                @endif
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Nominal</label>
        <div class="controls span2">
          <div class="input-prepend">
            <span class="add-on">Rp.</span>
            <input type="text" name="nominal_interhensif_1" id="nominal_interhensif_1" class="form-control nominal" autocomplete="off">
          </div>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Titipan 2</label>
        <div class="controls span7">
          <select class="form-control select2" name="id_interhensif_2" style="width: 100%;">
            <option value=""></option>
            @foreach($interensif as $int)
              <option value="{{ $int->id }}">
                @if($int->gelar_depan)
                  {{ $int->gelar_depan }}
                @endif

                @if($int->gelar_belakang)
                  {{ $int->nama }} {{ $int->gelar_belakang }}
                @else
                  {{ $int->nama }}
                @endif
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Nominal</label>
        <div class="controls span2">
          <div class="input-prepend">
            <span class="add-on">Rp.</span>
            <input type="text" name="nominal_interhensif_2" id="nominal_interhensif_2" class="form-control nominal" autocomplete="off">
          </div>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">JP Dibagikan</label>
        <div class="controls span2">
          <div class="input-prepend">
            <span class="add-on">Rp.</span>
            <input type="text" name="keuangan" id="keuangan" class="form-control nominal" autocomplete="off">
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">   
    <div class="btn-group">             
      <button type="submit" form="baru_data" class="btn bprev">HITUNG</button>
      <button type="button" class="btn" data-dismiss="modal">BATAL</button>
    </div>
  </div>
</div>

<input type="hidden" name="cek" id="cek" value="{{ $cek->remun }}">
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function(){  
      $('#baru_data').on('submit', function(event){
        event.preventDefault();
        var count_error = 0;
        
        if(count_error == 0){
          $.ajax({
            url:"{{ route('remunerasi_hitung') }}",
            method:"POST",
            data:$(this).serialize(),
            beforeSend:function() {
              $('#perhitungan_baru').css('display', 'none');
              $('#nav_remun').css('display', 'none');              
              $('#data_baru').modal('hide');
              $('#process').css('display', 'block');
            },
            success:function(data){
              location.reload();
            }
          })
        } else {
          return false;
        }
      });
    });
  </script>

  <script type="text/javascript">
    window.onload=function() {
      document.getElementById('jkn').style.display = 'none';
      document.getElementById('jkn').style.visibility = 'hidden';
      document.getElementById('umum').style.display = 'none';
      document.getElementById('umum').style.visibility = 'hidden';

      document.getElementById('bpjs').required = false;
      document.getElementById('awal').required = false;
      document.getElementById('akhir').required = false;
    }

    $('#jenis').on('change',function(){
      $jenis = $(this).val();

      if($jenis == 1){
        document.getElementById('jkn').style.display = 'none';
        document.getElementById('jkn').style.visibility = 'hidden';
        document.getElementById('umum').style.display = 'block';
        document.getElementById('umum').style.visibility = 'visible';

        document.getElementById('bpjs').required = false;
        document.getElementById('awal').required = true;
        document.getElementById('akhir').required = true;
      } else {
      if($jenis == 2){
        document.getElementById('jkn').style.display = 'block';
        document.getElementById('jkn').style.visibility = 'visible';
        document.getElementById('umum').style.display = 'none';
        document.getElementById('umum').style.visibility = 'hidden';

        document.getElementById('bpjs').required = true;
        document.getElementById('awal').required = false;
        document.getElementById('akhir').required = false;
      }}
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('.select2').select2();     
    });

    $(document).ready(function() {
      $('#tabel').DataTable( {
        "sort": false,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
      });
    });
  </script>
@endsection