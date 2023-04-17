@extends('layouts.content')
@section('title','Data Pasien Keluar')

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <form class="form-inline" method="GET" action="{{ route('pasien_keluar') }}" style="margin-top: 5px; margin-bottom: 0;">
    @csrf    
      <input type="hidden" name="tampil" value="{{ $tampil }}">
      <input type="hidden" name="cari" value="{{ $cari }}">

      <label>TANGGAL</label>
      <input type="date" name="awal" value="{{ $awal }}" style="width: 130px;" onchange="this.form.submit();">

      <label>S/D</label>
      <input type="date" name="akhir" value="{{ $akhir }}" style="width: 130px;" onchange="this.form.submit();">

      <select name="id_jenis" onchange="this.form.submit();">
        <option value="" style="font-style: italic;">SEMUA JENIS PASIEN</option>
        @foreach($jenis as $jns)
          <option value="{{ $jns->id }}" {{ $id_jenis == $jns->id? 'selected' : null }}>{{ strtoupper($jns->jenis) }}</option>
        @endforeach
      </select>

      <select name="id_rawat" onchange="this.form.submit();">
        <option value="" style="font-style: italic;">SEMUA JENIS PERAWATAN</option>
        @foreach($rawat as $rawat)
          <option value="{{ $rawat->id }}" {{ $id_rawat == $rawat->id? 'selected' : null }}>{{ strtoupper($rawat->jenis_rawat) }}</option>
        @endforeach
      </select>

      <select name="id_dpjp" onchange="this.form.submit();">
        <option value="" style="font-style: italic;">SEMUA DPJP</option>
        @foreach($dpjp as $dok)
          <option value="{{ $dok->id }}" {{ $id_dpjp == $dok->id? 'selected' : null }}>{{ $dok->nama }}</option>
        @endforeach
      </select>
    </form>    
  </div>
</div>

<div class="content">
  <form method="GET" action="{{ route('pasien_keluar') }}" class="form-inline" style="margin-bottom: 5px;">
  @csrf
    <input type="hidden" name="awal" value="{{ $awal }}">
    <input type="hidden" name="akhir" value="{{ $akhir }}">
    <input type="hidden" name="id_jenis" value="{{ $id_jenis }}">
    <input type="hidden" name="id_rawat" value="{{ $id_rawat }}">
    <input type="hidden" name="id_dpjp" value="{{ $id_dpjp }}">

    Menampilkan
    <select onchange="this.form.submit();" name="tampil">
      <option value="10" {{ $tampil == '10'? 'selected' : null }}>10</option>
      <option value="25" {{ $tampil == '25'? 'selected' : null }}>25</option>
      <option value="50" {{ $tampil == '50'? 'selected' : null }}>50</option>
      <option value="100" {{ $tampil == '100'? 'selected' : null }}>100</option>
      <option value="9999999999999" {{ $tampil == '9999999999999'? 'selected' : null }}>Semua</option>
    </select> data

    <input type="text" name="cari" class="pull-right" placeholder="Cari..." value="{{ $cari }}">
  </form>

  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered">
    <thead>
      <tr>
        <th rowspan="2" style="text-align: center; padding: 0 15px;"></th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">NAMA PASIEN</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;" width="100">MR</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">REGISTER</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">JENIS</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">PERAWATAN</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">DPJP</th>
        <th colspan="3" style="text-align: center; padding: 0 15px;">MASA PERAWATAN</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px;">TAGIHAN</th>
      </tr>
      <tr>
        <th style="text-align: center; padding: 0 15px;">MASUK</th>
        <th style="text-align: center; padding: 0 15px;">KELUAR</th>
        <th style="text-align: center; padding: 0 15px;">HARI</th>
      </tr>
    </thead>
    <tbody>
      @foreach($pasien as $pas)  
        <tr>
          <td class="min">
            <form hidden method="GET" action="{{ route('pasien_detil') }}" id="detil{{ $pas->id }}" target="_blank">
            @csrf
              <input type="text" name="id_pasien" value="{{ Crypt::encrypt($pas->id) }}">
              <input type="text" name="id_jenis" value="{{ $id_jenis }}">
            </form>            

            <div class="btn-group">
              <button class="btn btn-info btn-mini" type="submit" form="detil{{ $pas->id }}" title="Detil Data Pasien">
                <i class="icon-list"></i>
              </button>

              <button class="btn btn-info btn-mini edit-jenis" type="button" title="Ubah Jenis Pasien" data-id="{{ $pas->id }}">
                <i class="icon-check"></i>
              </button>

              <button class="btn btn-info btn-mini edit-dpjp" type="button" title="Ganti DPJP" data-id="{{ $pas->id }}">
                <i class="icon-user"></i>
              </button>
                  
              @if(Auth::user()->id_akses == 1)
                <a href="{{ route('pasien_batal_pulang',Crypt::encrypt($pas->id)) }}" class="btn btn-info btn-mini" title="Kembalikan Status Pasien" onclick="return confirm('Batalkan pasien keluar ?')">
                  <i class="icon-remove"></i>
                </a>
              @endif
            </div>
          </td>
          <td>{{ strtoupper($pas->nama) }}</td>
          <td class="min" style="text-align: center;">{{ $pas->no_mr }}</td>
          <td class="min" style="text-align: center;">{{ $pas->register }}</td>
          <td>{{ strtoupper($pas->jenis_pasien) }}</td>
          <td>{{ strtoupper($pas->jenis_rawat) }}</td>
          <td>{{ $pas->dpjp }}</td>
          <td class="min" style="text-align: center;">{{ strtoupper($pas->masuk) }}</td>
          <td class="min" style="text-align: center;">{{ strtoupper($pas->keluar) }}</td>
          <td style="text-align: right; padding-right: 15px;" class="min">{{ $pas->masa }}</td>
          <td style="text-align: right;">{{ number_format($pas->tagihan,0) }}</td>
        </tr>              
      @endforeach
    </tbody>
  </table>        

  <div>
    <div class="pull-left" style="font-size: 12px;">
      Menampilkan {{ number_format($pasien->firstItem(),0) }} - {{ number_format($pasien->lastItem(),0) }} dari {{ number_format($pasien->total(),0) }} data
    </div>                               
    <div class="pagination pagination-small pull-right">
      {!! $pasien->appends(request()->input())->render("pagination::bootstrap-4"); !!}
    </div>
  </div>
</div>

<div class="modal hide fade" id="modal_jenis">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Ubah Jenis Pasien</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_jenis" method="POST" action="{{ route('pasien_keluar_jenis_simpan') }}">
    @csrf
      <input type="hidden" name="id" id="jenis_id">

      <div class="control-group">
        <div class="controls span12">
          <select class="form-control" name="id_jenis" id="jenis_id_jenis" size="10">
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
      <button type="submit" form="form_edit_jenis" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="modal_dpjp">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Ganti DPJP</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_dpjp" method="POST" action="{{ route('pasien_keluar_dpjp_simpan') }}">
    @csrf
      <input type="hidden" name="id" id="dpjp_id">

      <div class="control-group">
        <div class="controls span12">
          <select class="form-control" name="id_dpjp" id="dpjp_id_dpjp" size="15">
            @foreach($dpjp as $dok)
            <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
            @endforeach
          </select>
        </div>
      </div>      
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_dpjp" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('.edit-jenis').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{route('pasien_keluar_jenis')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#jenis_id').val(data.id);
            $('#jenis_id_jenis').val(data.id_pasien_jenis);
            $('#modal_jenis').modal('show');
          }
        });
      });

      $('.edit-dpjp').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{route('pasien_keluar_dpjp')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#dpjp_id').val(data.id);
            $('#dpjp_id_dpjp').val(data.id_dpjp);
            $('#modal_dpjp').modal('show');
          }
        });
      });
    });

    $(document).ready(function() {
      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.25*box.clientHeight);

      $('#tabel').DataTable( {     
        "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": 0
        }],        
        "order": [[ 1, "asc" ]],
        scrollY:        tinggi,
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        searching:      false,
        sort:           true,
        info:           false,
      });
    });
  </script>
@endsection