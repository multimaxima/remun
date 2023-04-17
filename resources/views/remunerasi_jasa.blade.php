@extends('layouts.content')
@section('title','Perhitungan Jasa')

@section('content')
<div class="content" style="margin-bottom: 5px; padding-bottom: 0;">  
  <input type="hidden" name="id" id="id" value="{{ $remun }}">

  <div class="row-fluid" style="margin-bottom: 10px;">
    <div class="span5">
      <fieldset style="border-radius: 10px;">
        <legend>Filter Data</legend>
        <select name="id_ruang" id="id_ruang" class="form-control" style="margin-bottom: 3px;">
          <option value="" {{ $id_ruang == NULL? 'selected' : null }} style="font-style: italic;">SEMUA RUANG</option>
          @foreach($ruang as $rng)
            <option value="{{ $rng->id }}" {{ $rng->id == $id_ruang? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
          @endforeach
        </select>

        <select name="id_bagian" id="id_bagian" class="form-control" style="margin-bottom: 3px;">
          <option value="" {{ $id_bagian == NULL? 'selected' : null }} style="font-style: italic;">SEMUA BAGIAN</option>
          @foreach($bagian as $bag)
            <option value="{{ $bag->id }}" {{ $bag->id == $id_bagian? 'selected' : null }}>{{ strtoupper($bag->bagian) }}</option>
          @endforeach
        </select>

        <select name="id_tenaga" id="id_tenaga" class="form-control">
          <option value="" {{ $id_tenaga == NULL? 'selected' : null }} style="font-style: italic;">SEMUA PROFESI</option>
          @foreach($tenaga as $ten)
            <option value="{{ $ten->id }}" {{ $ten->id == $id_tenaga? 'selected' : null }}>{{ strtoupper($ten->tenaga) }}</option>
          @endforeach
        </select>
      </fieldset>
    </div>

    <div class="span3">
      <fieldset style="border-radius: 10px;">
        <legend>Jasa</legend>
        <table width="100%">
          <tr>
            <td width="110">Jasa Awal</td>
            <td width="40">= Rp.</td>
            <td id="total" style="text-align: right;"></td>
          </tr>
          <tr>
            <td>Jasa Baru</td>
            <td>= Rp.</td>
            <td id="total_baru" style="text-align: right;"></td>
          </tr>
          <tr>
            <td>Tandon Jasa</td>
            <td>= Rp.</td>
            <td id="tandon" style="text-align: right;"></td>
          </tr>
        </table>
      </fieldset>
      <div class="btn-group" style="margin-top: 2px;">
        <button type="submit" form="kembali" style="width: 57%;" class="btn btn-primary">KEMBALI</button>
        <a href="{{ route('remunerasi_tandon',Crypt::encrypt($remun)) }}" target="_blank" style="width: 57%;" class="btn btn-primary">RINCIAN TANDON</a>

        @if(Auth::user()->id_akses == 1)
        <form hidden method="GET" action="{{ route('remunerasi') }}" id="kembali">
        @csrf
          <input type="text" name="id" value="{{ $remun }}">
        </form>
        @endif

        @if(Auth::user()->id_akses == 6)
        <form hidden method="GET" action="{{ route('remunerasi_olah') }}" id="kembali">
        @csrf
          <input type="text" name="id_remun" value="{{ Crypt::encrypt($remun) }}">
        </form>
        @endif
      </div>
    </div>

    <div class="span4">
      <fieldset style="border-radius: 10px;">
        <legend>Perubahan Jasa</legend>
        <div class="control-group">
          <label class="control-label span2">Jenis</label>
          <div class="controls span10">
            <select name="jenis" id="jenis" class="form-control">
              <option value="1">PENGURANGAN JASA</option>
              <option value="2">PENAMBAHAN JASA</option>
            </select>
          </div>
        </div>

        <div id="pengurangan">
          <form method="POST" action="{{ route('remunerasi_jasa_relokasi') }}" onsubmit="return confirm('Lakukan pengurangan jasa ?')">
          @csrf
            <input type="hidden" name="id" value="{{ $remun }}">
            <input type="hidden" name="id_ruang" id="id_ruang_rel">
            <input type="hidden" name="id_tenaga" id="id_tenaga_rel">
            <input type="hidden" name="id_bagian" id="id_bagian_rel">

            <div class="control-group">
              <label class="control-label span2">Nominal</label>
              <div class="controls span10">
                <div class="input-prepend">
                  <span class="add-on">Rp.</span>
                  <input type="text" name="relokasi" class="nominal" id="relokasi" style="width: 79%; text-align: right;">
                  <button class="btn" type="submit">OK</button>
                </div>
              </div>
            </div>
          </form>
        </div>

        <div id="penambahan">
          <form method="POST" action="{{ route('remunerasi_jasa_alokasi') }}" onsubmit="return confirm('Lakukan penambahan jasa ?')">
          @csrf
            <input type="hidden" name="id" value="{{ $remun }}">
            <input type="hidden" name="id_ruang" id="id_ruang_alok">
            <input type="hidden" name="id_tenaga" id="id_tenaga_alok">
            <input type="hidden" name="id_bagian" id="id_bagian_alok">

            <div class="control-group">
              <label class="control-label span2">Nominal</label>
              <div class="controls span10">
                <div class="input-prepend">
                  <span class="add-on">Rp.</span>
                  <input type="text" name="alokasi" class="nominal" id="alokasi" style="width: 79%; text-align: right;">
                  <button class="btn" type="submit">OK</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </fieldset>
    </div>
  </div>
</div>

<div class="content" style="margin-bottom: 5px; height: 57vh;" id="data">
  @include('layouts.pesan')
  <table id="tabel" width="100%" class="table table-hover table-striped" style="font-size: 12px;">
    <thead>
      <th hidden></th>
      <th></th>
      <th width="10">NO.</th>
      <th width="250">NAMA KARYAWAN</th>
      <th width="150">RUANG</th>
      <th>TENAGA</th>
      <th>BAGIAN</th>
      <th width="50">STATUS</th>
      <th width="20">SCORE</th>
      <th width="80">JASA ASAL</th>
      <th width="100">JASA BARU</th>
    </thead>
    <tbody id="rincian">
    </tbody>
  </table>
</div>

<div class="modal hide fade" id="modal_jasa_edit">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title" id="exampleModalCenterTitle">Edit Jasa</h5>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_jasa" method="POST" action="{{ route('remunerasi_jasa_edit') }}">
    @csrf

      <input type="hidden" name="id" id="edit_id">

      <div class="control-group">
        <label class="control-label span4">Jasa Awal</label>
        <div class="controls span7">
          <div class="input-prepend">
            <span class="add-on">Rp.</span>
            <input type="text" class="form-control" id="edit_jasa_awal" style="width: 130px; text-align: right;" readonly>
          </div>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span4">Jasa Baru</label>
        <div class="controls span7">
          <div class="input-prepend">
            <span class="add-on">Rp.</span>
            <input type="text" name="jasa_baru" class="form-control nominal" style="width: 130px; text-align: right;">
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_jasa" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    window.onload=function() {
      document.getElementById('pengurangan').style.display = 'block';
      document.getElementById('pengurangan').style.visibility = 'visible';
      document.getElementById('penambahan').style.display = 'none';
      document.getElementById('penambahan').style.visibility = 'hidden';

      $id_ruang   = document.getElementById('id_ruang').value;
      $id_bagian  = document.getElementById('id_bagian').value;
      $id_tenaga  = document.getElementById('id_tenaga').value;
      $id_remun   = document.getElementById('id').value;
      $relokasi   = 0;
      $alokasi    = 0;
      $asal       = 0;

      $.ajax({
        type : 'get',
        url : '{{ route("remunerasi_jasa_tampil") }}',
        data: {'id_ruang': $id_ruang, 'id_bagian': $id_bagian, 'id_tenaga': $id_tenaga, 'id': $id_remun, 'relokasi': $relokasi, 'alokasi': $alokasi, 'asal': $asal},
        success: function(data){
          $('#rincian').html(data.output);
          $('#total').html(data.jumlah);
          $('#total_baru').html(data.sisa);
          $('#tandon').html(data.tandon.sisa);
          $('#relokasi').val(0);
          $('#alokasi').val(0);
        }
      });
    };
    
    $('#id_ruang').on('change',function(){
      $id_ruang   = $(this).val();
      $id_bagian  = document.getElementById('id_bagian').value;
      $id_tenaga  = document.getElementById('id_tenaga').value;
      $id_remun   = document.getElementById('id').value;
      $relokasi   = 0;
      $alokasi    = 0;
      $asal       = 0;

      $.ajax({
        type : 'get',
        url : '{{ route("remunerasi_jasa_tampil") }}',
        data: {'id_ruang': $id_ruang, 'id_bagian': $id_bagian, 'id_tenaga': $id_tenaga, 'id': $id_remun, 'relokasi': $relokasi, 'alokasi': $alokasi, 'asal': $asal},
        success: function(data){
          $('#rincian').html(data.output);
          $('#total').html(data.jumlah);
          $('#total_baru').html(data.sisa);
          $('#tandon_medis').html(data.tandon.medis);
          $('#tandon_perawat').html(data.tandon.perawat);
          $('#relokasi').val(0);
          $('#alokasi').val(0);
        }
      });
    });

    $('#id_bagian').on('change',function(){
      $id_ruang   = document.getElementById('id_ruang').value;
      $id_bagian  = $(this).val();
      $id_tenaga  = document.getElementById('id_tenaga').value;
      $id_remun   = document.getElementById('id').value;
      $relokasi   = 0;
      $alokasi    = 0;
      $asal       = 0;

      $.ajax({
        type : 'get',
        url : '{{ route("remunerasi_jasa_tampil") }}',
        data: {'id_ruang': $id_ruang, 'id_bagian': $id_bagian, 'id_tenaga': $id_tenaga, 'id': $id_remun, 'relokasi': $relokasi, 'alokasi': $alokasi, 'asal': $asal},
        success: function(data){
          $('#rincian').html(data.output);
          $('#total').html(data.jumlah);
          $('#total_baru').html(data.sisa);
          $('#tandon_medis').html(data.tandon.medis);
          $('#tandon_perawat').html(data.tandon.perawat);
          $('#relokasi').val(0);
          $('#alokasi').val(0);
        }
      });
    });

    $('#id_tenaga').on('change',function(){
      $id_ruang   = document.getElementById('id_ruang').value;
      $id_bagian  = document.getElementById('id_bagian').value;
      $id_tenaga  = $(this).val();
      $id_remun   = document.getElementById('id').value;
      $relokasi   = 0;
      $alokasi    = 0;
      $asal       = 0;

      $.ajax({
        type : 'get',
        url : '{{ route("remunerasi_jasa_tampil") }}',
        data: {'id_ruang': $id_ruang, 'id_bagian': $id_bagian, 'id_tenaga': $id_tenaga, 'id': $id_remun, 'relokasi': $relokasi, 'alokasi': $alokasi, 'asal': $asal},
        success: function(data){
          $('#rincian').html(data.output);
          $('#total').html(data.jumlah);
          $('#total_baru').html(data.sisa);
          $('#tandon_medis').html(data.tandon.medis);
          $('#tandon_perawat').html(data.tandon.perawat);
          $('#relokasi').val(0);
          $('#alokasi').val(0);
        }
      });
    });

    $('#relokasi').on('input',function(){
      $id_ruang   = document.getElementById('id_ruang').value;
      $id_bagian  = document.getElementById('id_bagian').value;
      $id_tenaga  = document.getElementById('id_tenaga').value;
      $id_remun   = document.getElementById('id').value;
      $relokasi   = $(this).val();        
      $alokasi    = 0;
      $asal       = 0;      

      $.ajax({
        type : 'get',
        url : '{{ route("remunerasi_jasa_tampil") }}',
        data: {'id_ruang': $id_ruang, 'id_bagian': $id_bagian, 'id_tenaga': $id_tenaga, 'id': $id_remun, 'relokasi': $relokasi, 'alokasi': $alokasi, 'asal': $asal},
        success: function(data){
          $('#rincian').html(data.output);
          $('#total').html(data.jumlah);
          $('#total_baru').html(data.sisa);
          $('#tandon_medis').html(data.tandon.medis);
          $('#tandon_perawat').html(data.tandon.perawat);
          $('#alokasi').val(0);

          $('#id_ruang_rel').val($id_ruang);
          $('#id_bagian_rel').val($id_bagian);
          $('#id_tenaga_rel').val($id_tenaga);
        }
      });
    });

    $('#alokasi').on('input',function(){
      $id_ruang   = document.getElementById('id_ruang').value;
      $id_bagian  = document.getElementById('id_bagian').value;
      $id_tenaga  = document.getElementById('id_tenaga').value;
      $id_remun   = document.getElementById('id').value;
      $relokasi   = 0;
      $alokasi    = $(this).val();
      $asal       = 0;

      $.ajax({
        type : 'get',
        url : '{{ route("remunerasi_jasa_tampil") }}',
        data: {'id_ruang': $id_ruang, 'id_bagian': $id_bagian, 'id_tenaga': $id_tenaga, 'id': $id_remun, 'relokasi': $relokasi, 'alokasi': $alokasi, 'asal': $asal},
        success: function(data){
          $('#rincian').html(data.output);
          $('#total').html(data.jumlah);
          $('#total_baru').html(data.sisa);
          $('#tandon_medis').html(data.tandon.medis);
          $('#tandon_perawat').html(data.tandon.perawat);
          $('#relokasi').val(0);

          $('#id_ruang_alok').val($id_ruang);
          $('#id_bagian_alok').val($id_bagian);
          $('#id_tenaga_alok').val($id_tenaga);
        }
      });
    });

    $('#jenis').on('change',function(){
      $jenis    = $(this).val();

      $id_ruang   = document.getElementById('id_ruang').value;
      $id_bagian  = document.getElementById('id_bagian').value;
      $id_tenaga  = document.getElementById('id_tenaga').value;
      $id_remun   = document.getElementById('id').value;
      $relokasi   = 0;
      $alokasi    = 0;
      $asal       = 0;

      $.ajax({
        type : 'get',
        url : '{{ route("remunerasi_jasa_tampil") }}',
        data: {'id_ruang': $id_ruang, 'id_bagian': $id_bagian, 'id_tenaga': $id_tenaga, 'id': $id_remun, 'relokasi': $relokasi, 'alokasi': $alokasi, 'asal': $asal},
        success: function(data){
          $('#rincian').html(data.output);
          $('#total').html(data.jumlah);
          $('#total_baru').html(data.sisa);
          $('#tandon_medis').html(data.tandon.medis);
          $('#tandon_perawat').html(data.tandon.perawat);
          $('#relokasi').val(0);
          $('#alokasi').val(0);
        }
      });

      if($jenis == 1){
        document.getElementById('pengurangan').style.display = 'block';
        document.getElementById('pengurangan').style.visibility = 'visible';
        document.getElementById('penambahan').style.display = 'none';
        document.getElementById('penambahan').style.visibility = 'hidden';
      } else {
        document.getElementById('pengurangan').style.display = 'none';
        document.getElementById('pengurangan').style.visibility = 'hidden';
        document.getElementById('penambahan').style.display = 'block';
        document.getElementById('penambahan').style.visibility = 'visible';
      }
    });    
    
    $(document).ready(function() {
      var box = document.querySelector('#data');
      var tinggi = box.clientHeight-(0.20*box.clientHeight);

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