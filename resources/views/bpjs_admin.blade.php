@extends('layouts.content')
@section('title','Claim Asuransi')

@section('style')
  @if($cek->bpjs == 1)
    <meta http-equiv="refresh" content="10"/>
  @endif
@endsection

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    @if($cek->bpjs == 0)
    <button class="btn btn-primary" id="hitung_baru" data-toggle="modal" data-target="#modal_baru">HITUNG CLAIM ASURANSI</button>
    @endif
  </div>
</div>

@if($cek->bpjs == 1)
<div class="content">
  <center>
    Sedang melakukan perhitungan claim
    <img src="/images/progress.gif" style="height: 30px;">
  </center>
</div>
@endif

<div class="content" id="process" style="display:none;">
  <center>
    Sedang melakukan perhitungan claim
    <img src="/images/progress.gif" style="height: 30px;">
  </center>
</div>

<div class="content">
  @include('layouts.pesan')  
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered">
    <thead>
      <tr>
        <th style="padding: 0 10px;" rowspan="2"></th>
        <th style="padding: 0 10px;" rowspan="2">TANGGAL PERHITUNGAN</th>
        <th style="padding: 0 10px;" rowspan="2">JENIS</th>
        <th style="padding: 0 10px;" colspan="2">PERIODE</th>
        <th style="padding: 0 10px;" colspan="2">RAWAT JALAN</th>
        <th style="padding: 0 10px;" colspan="2">RAWAT INAP</th>
        <th style="padding: 0 10px;" colspan="2">TOTAL</th>
      </tr>
      <tr>
        <th style="padding: 0 10px;">DARI</th>
        <th style="padding: 0 10px;">SAMPAI</th>
        <th style="padding: 0 10px;">TAGIHAN</th>
        <th style="padding: 0 10px;">CLAIM</th>
        <th style="padding: 0 10px;">TAGIHAN</th>
        <th style="padding: 0 10px;">CLAIM</th>
        <th style="padding: 0 10px;">TAGIHAN</th>
        <th style="padding: 0 10px;">CLAIM</th>
      </tr>
    </thead>
    <tbody>
      @foreach($bpjs as $bpjs)
      <tr>
        <td class="min">
          <a href="{{ route('bpjs',Crypt::encrypt($bpjs->id)) }}" title="Rincian Data" class="btn btn-info btn-mini">
            <i class="icon-list"></i>
          </a>
        </td>
        <td>{{ strtoupper($bpjs->tanggal) }}</td>
        <td>{{ strtoupper($bpjs->jenis) }}</td>
        <td style="text-align: center;">{{ strtoupper($bpjs->dari) }}</td>
        <td style="text-align: center;">{{ strtoupper($bpjs->sampai) }}</td>
        <td style="text-align: right;">{{ number_format($bpjs->nominal_jalan,2) }}</td>
        <td style="text-align: right;">{{ number_format($bpjs->claim_jalan,2) }}</td>
        <td style="text-align: right;">{{ number_format($bpjs->nominal_inap,2) }}</td>
        <td style="text-align: right;">{{ number_format($bpjs->claim_inap,2) }}</td>
        <td style="text-align: right;">{{ number_format($bpjs->nominal_jalan + $bpjs->nominal_inap,2) }}</td>
        <td style="text-align: right;">{{ number_format($bpjs->claim_jalan + $bpjs->claim_inap,2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="modal hide fade" id="modal_baru">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title">Hitung Claim Asuransi</h5>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="ambil">
    @csrf

      <div class="control-group">
        <label class="control-label span3">Tanggal</label>
        <div class="controls span9" style="display: inline-flex;">
          <input type="date" name="awal" id="awal" required style="width: 130px;">
          <label style="margin: 5px 10px;">s/d</label>
          <input type="date" name="akhir" id="akhir" required style="width: 130px;">
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Jenis Pasien</label>
        <div class="controls span8">
          <select name="id_jenis" required class="form-control" size="5">
            @foreach($jenis as $jns)
              <option value="{{ $jns->id }}">{{ strtoupper($jns->jenis) }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">   
    <div class="btn-group">             
      <button type="submit" form="ambil" class="btn bprev">HITUNG</button>
      <button type="button" class="btn" data-dismiss="modal">BATAL</button>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('#ambil').on('submit', function(event){
        event.preventDefault();
        var count_error = 0;

        if(count_error == 0){
          $.ajax({
            url:"{{ route('ambil_data') }}",
            method:"POST",
            data:$(this).serialize(),
            beforeSend:function() {
              $('#hitung_baru').css('display', 'none');    
              $('#process').css('display', 'block');
              $('#modal_baru').modal('hide');
            },
            success:function(data){
              location.reload();
            }
          })
        } else {
          return false;
        }
      });

      $('#tabel').DataTable( {
        "sort": false,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
      });
    });
  </script>
@endsection