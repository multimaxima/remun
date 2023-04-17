@extends('layouts.content')
@section('title','Claim Asuransi')

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <div class="pull-left" style="display: inline-flex;">
      <div class="btn-group">
        <button type="submit" form="kembali" class="btn btn-primary">KEMBALI</button>
        <form hidden method="GET" id="kembali" action="{{ route('bpjs_admin') }}">
        @csrf
        </form>

        <a href="{{ route('bpjs_salin_jalan',Crypt::encrypt($bpjs->id)) }}" onclick="return confirm('Salin claim rawat jalan ?')" class="btn btn-primary" style="margin-left: 2px;">
          SALIN RAWAT JALAN
        </a>
        <a class="btn btn-primary" href="{{ route('bpjs_salin_inap',Crypt::encrypt($bpjs->id)) }}" onclick="return confirm('Salin claim rawat inap ?')">
          SALIN RAWAT INAP
        </a>
        <a class="btn btn-primary" href="{{ route('bpjs_seimbang',Crypt::encrypt($bpjs->id)) }}" onclick="return confirm('Salin claim rawat jalan dan rawat inap ?')">
          CLAIM SEIMBANG
        </a>
        <a class="btn btn-danger" href="{{ route('bpjs_batal',Crypt::encrypt($bpjs->id)) }}" onclick="return confirm('Batalkan perhitungan BPJS ?')" title="Batalkan Perhitungan" style="margin: 0 3px 0 2px;">
          BATAL
        </a>       

        <a class="btn btn-primary" href="{{ route('bpjs_cetak',Crypt::encrypt($bpjs->id)) }}" target="_blank" title="Cetak">
          CETAK
        </a>
        <a class="btn btn-primary" href="{{ route('bpjs_export',Crypt::encrypt($bpjs->id)) }}" title="Export">
          EXPORT
        </a>
      </div>   
    </div>

    <div class="pull-right">
      <label style="margin-top: 8px;">Waktu Perhitungan : {{ $bpjs->waktu }}</label>
    </div>
  </div>
</div>

<div class="content" id="data">
  @include('layouts.pesan')
  <label style="font-weight: bold; text-align: center; font-size: 16px;">
    CLAIM {{ strtoupper($bpjs->jenis) }} TANGGAL {{ strtoupper($bpjs->periode_awal) }} - {{ strtoupper($bpjs->periode_akhir) }}
  </label>
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered">
    <thead>
      <tr>
        <th style="padding: 0 10px;" rowspan="2"></th>
        <th style="padding: 0 10px;" rowspan="2">NO.</th>
        <th style="padding: 0 10px;" rowspan="2">DOKTER DPJP</th>
        <th style="padding: 0 10px;" colspan="2">RAWAT JALAN</th>
        <th style="padding: 0 10px;" colspan="2">RAWAT INAP</th>
        <th style="padding: 0 10px;" colspan="2">TOTAL</th>
      </tr>
      <tr>
        <th style="padding: 0 10px;">TAGIHAN</th>
        <th style="padding: 0 10px;">CLAIM</th>
        <th style="padding: 0 10px;">TAGIHAN</th>
        <th style="padding: 0 10px;">CLAIM</th>
        <th style="padding: 0 10px;">TAGIHAN</th>
        <th style="padding: 0 10px;">CLAIM</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1 ?>
      @foreach($detil as $detil)
      <tr>
        <td class="min">
          <a href="{{ route('bpjs_rincian',Crypt::encrypt($detil->id)) }}" title="Rincian" class="btn btn-info btn-mini" target="_blank">
            <i class="icon-list"></i>
          </a>
        </td>
        <td class="min" style="text-align: right;">{{ $no++ }}.</td>
        <td class="min">{{ $detil->nama }}</td>
        <td style="text-align: right;">{{ number_format($detil->nominal_jalan,0) }}</td>
        <td style="text-align: right;" class="min">
          <div class="input-prepend" style="margin-bottom: 0;">
            <span class="add-on">Rp.</span>
            <input type="text" class="edit_jalan nominal" name="claim_jalan" data-id="{{ $detil->id }}" style="width: 110px; font-size: 13px; text-align: right;" value="{{ $detil->claim_jalan }}" autocomplete="false">
          </div>
        </td>
        <td style="text-align: right;">{{ number_format($detil->nominal_inap,0) }}</td>
        <td style="text-align: right;" class="min">
          <div class="input-prepend" style="margin-bottom: 0;">
            <span class="add-on">Rp.</span>
            <input type="text" class="edit_inap nominal" name="claim_inap" data-id="{{ $detil->id }}" style="width: 110px; font-size: 13px; text-align: right;" value="{{ $detil->claim_inap }}" autocomplete="false">
          </div>
        </td>
        <td style="text-align: right; background-color: #e4ebfe;">
          {{ number_format($detil->nominal_jalan + $detil->nominal_inap,0) }}
        </td>
        <td style="background-color: #e4ebfe; text-align: right;">
          {{ number_format($detil->claim_jalan + $detil->claim_inap,0) }}
        </td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <th colspan="3" style="text-align: center;">JUMLAH</th>
      <th style="text-align: right; padding-right: 5px;">{{ number_format($tag->t_jalan,0) }}</th>
      <th style="text-align: right; padding-right: 5px;">{{ number_format($tag->c_jalan,0) }}</th>
      <th style="text-align: right; padding-right: 5px;">{{ number_format($tag->t_inap,0) }}</th>
      <th style="text-align: right; padding-right: 5px;">{{ number_format($tag->c_inap,0) }}</th>
      <th style="text-align: right; padding-right: 5px;">{{ number_format($tag->t_jalan + $tag->t_inap,0) }}</th>            
      <th style="text-align: right; padding-right: 5px;">{{ number_format($tag->c_jalan + $tag->c_inap,0) }}</th>            
    </tfoot>
  </table>
</div>

<input type="hidden" name="stat" id="stat" value="{{ $bpjs->stat }}">
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function(){
      $('.edit_inap').on("change",function() {
        $id           = $(this).attr('data-id');
        $claim_inap   = $(this).val();

        $.ajax({
          type : 'get',
          url : '{{ route("bpjs_inap") }}',
          data: {'id': $id, 'claim_inap': $claim_inap}      
        });
      });

      $('.edit_jalan').on("change",function() {
        $id           = $(this).attr('data-id');
        $claim_jalan  = $(this).val();

        $.ajax({
          type : 'get',
          url : '{{ route("bpjs_jalan") }}',
          data: {'id': $id, 'claim_jalan': $claim_jalan}      
        });
      });
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      var box = document.querySelector('#data');
      var tinggi = box.clientHeight-(0.22*box.clientHeight);

      $('#tabel').DataTable( {     
        scrollY:        tinggi,
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        searching:      false,
        sort:           false,
        info:           false,
      });
    });
  </script>
@endsection