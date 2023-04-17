@extends('layouts.content')
@section('title','Data Ruang')

@section('style')
  <style type="text/css">
    .table table tbody td {
      padding: 0 10px;
    }

    .table table thead th {
      padding: 0 10px;
    }
  </style>
@endsection

@section('content')
<div class="navbar">
  <div class="navbar-inner">
    <ul class="nav">
      <li><a href="{{ route('bank') }}">Bank</a></li>
      <li class="active"><a href="#">Ruang</a></li>
      <li><a href="{{ route('rekening_layanan') }}">Rekening Tarif</a></li>
      <li><a href="{{ route('bagian_tenaga') }}">Jenis Tenaga</a></li>
      <li><a href="{{ route('bagian') }}">Bagian</a></li>
      <li><a href="{{ route('jasa_layanan') }}">Jasa</a></li>
      <li><a href="{{ route('kategori_layanan') }}">Kategori Layanan</a></li>
      <li><a href="{{ route('jenis_pasien') }}">Jenis Pasien</a></li>
      <li><a href="{{ route('absensi') }}">Absensi</a></li>
    </ul>
    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#data_baru">TAMBAH</button>
  </div>
</div>

<div class="content">  
  <form method="GET" action="{{ route('ruang') }}" class="form-inline" style="margin-bottom: 5px;">
  @csrf
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

  @include('layouts.pesan')

  <table id="tabel" width="100%" class="table table-bordered">
    <thead>
      <tr>
        <th rowspan="2"></th>
        <th rowspan="2" style="padding: 0 10px;">Ruang</th>
        <th rowspan="2" style="padding: 0 10px;">Rawat Jalan</th>
        <th rowspan="2" style="padding: 0 10px;">Rawat Inap</th>
        <th rowspan="2" style="padding: 0 10px;">Terima Pasien</th>
        <th rowspan="2" style="padding: 0 10px;">Pasien Luar</th>
        <th colspan="2" style="padding: 0 10px;">Indeks Medis</th>
        <th colspan="2" style="padding: 0 10px;">Indeks Perawat</th>
        <th rowspan="2" style="padding: 0 10px;">Jenis Pasien</th>
        <th rowspan="2" style="padding: 0 10px;">Layanan</th>
      </tr>
      <tr>
        <th style="padding: 0 10px;">Gawat Darurat</th>
        <th style="padding: 0 10px;">Resiko</th>
        <th style="padding: 0 10px;">Gawat Darurat</th>
        <th style="padding: 0 10px;">Resiko</th>
      </tr>
    </thead>
    <tbody>
      @foreach($ruang as $rng)
      <tr>
        <td class="min" style="vertical-align: top;">
          <div class="btn-group">
            <a href="#" class="btn btn-info btn-mini btn-edit_ruang" data-toggle="modal" data-id="{{ $rng->id }}" title="Edit Ruang">
              <i class="icon-edit"></i>
            </a>

            <a href="{{ route('ruang_hapus',Crypt::encrypt($rng->id)) }}" class="btn btn-info btn-mini" title="Hapus Ruang" onclick="return confirm('Hapus ruang {{ $rng->ruang }} ?')">
              <i class="icon-trash"></i>
            </a>
          </div>
        </td>
        <td style="vertical-align: top;">{{ strtoupper($rng->ruang) }}</td>
        <td class="min" style="text-align: center; vertical-align: top;">
          @if($rng->jalan == 1)
            <i class="icon-ok"></i>                
          @endif
        </td>
        <td class="min" style="text-align: center; vertical-align: top;">
          @if($rng->inap == 1)
            <i class="icon-ok"></i>                
          @endif
        </td>
        <td class="min" style="text-align: center; vertical-align: top;">
          @if($rng->terima_pasien == 1)
            <i class="icon-ok"></i>                
          @endif
        </td>
        <td class="min" style="text-align: center; vertical-align: top;">
          @if($rng->nonpasien == 1)
            <i class="icon-ok"></i>                
          @endif
        </td>
        <td class="min" style="text-align: center; vertical-align: top;">{{ $rng->m_gawat_darurat }}</td>
        <td class="min" style="text-align: center; vertical-align: top;">{{ $rng->m_resiko }}</td>
        <td class="min" style="text-align: center; vertical-align: top;">{{ $rng->p_gawat_darurat }}</td>
        <td class="min" style="text-align: center; vertical-align: top;">{{ $rng->p_resiko }}</td>
        <td style="vertical-align: top;" class="min">
          @if(count($rng->ruang_jenis) > 0)
            @foreach($rng->ruang_jenis as $jen)
              @if($jen->aktif == 1)
                <a href="#" class="aktif" title="Non Aktifkan" data-id="{{ $jen->id }}">
                  <input type="checkbox" checked style="margin-top: -3px;">
                </a>
              @else
                <a href="#" class="aktif" title="Aktifkan" data-id="{{ $jen->id }}">
                  <input type="checkbox" style="margin-top: -3px;">
                </a>
              @endif
              {{ $jen->jenis }}<br>
            @endforeach
          @endif
        </td>
        <td style="vertical-align: top;" class="min">
          @if(count($rng->ruang_jasa) > 0)          
            @foreach($rng->ruang_jasa as $jasa)            
              <div style="margin-bottom: 2px;">
              <div class="btn-group">
                <a href="#" class="btn btn-info btn-mini edit_layanan" title="Edit" data-toggle="modal" data-id="{{ $jasa->id }}">
                  <i class="icon-edit"></i>
                </a>
                <a href="{{ route('ruang_layanan_hapus',Crypt::encrypt($jasa->id)) }}" class="btn btn-info btn-mini" onclick="return confirm('Hapus jasa ?')" title="Hapus">
                  <i class="icon-trash"></i>
                </a>
              </div>              
              {{ $jasa->jasa }}<br>
              </div>
            @endforeach
          @endif
          <button class="btn btn-secondary btn-mini btn-block layanan_baru" data-toggle="modal" data-id="{{ $rng->id }}">TAMBAH LAYANAN</button>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div>
    <div class="pull-left" style="font-size: 12px;">
      Menampilkan {{ number_format($ruang->firstItem(),0) }} - {{ number_format($ruang->lastItem(),0) }} dari {{ number_format($ruang->total(),0) }} data
    </div>                               
    <div class="pagination pagination-small pull-right">
      {!! $ruang->appends(request()->input())->render("pagination::bootstrap-4"); !!}
    </div>
  </div>
</div>      

<!--Tambah Ruang-->
<div class="modal hide fade" id="data_baru">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title">TAMBAH RUANG</h5>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_baru_ruang" method="POST" action="{{ route('ruang_baru') }}">
    @csrf

      <div class="control-group">
        <label class="control-label span3">Nama Ruang</label>
        <div class="controls span8">
          <input type="text" class="form-control" name="ruang" required autofocus>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Rawat Jalan</label>
        <div class="controls span4">
          <select class="form-control" name="jalan">
            <option value="0">TIDAK</option>
            <option value="1">YA</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Rawat Inap</label>
        <div class="controls span4">
          <select class="form-control" name="inap">
            <option value="0">TIDAK</option>
            <option value="1">YA</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Terima Pasien</label>
        <div class="controls span4">
          <select class="form-control" name="terima_pasien">
            <option value="0">TIDAK</option>
            <option value="1">YA</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Pasien Luar</label>
        <div class="controls span4">
          <select class="form-control" name="nonpasien">
            <option value="0">TIDAK</option>
            <option value="1">YA</option>
          </select>
        </div>
      </div>

      <div class="control-group" style="margin-top: 10px;">        
        <div class="span12">
        <table width="100%" class="table table-bordered">
          <tr style="background-color: #f1f1f1;">
            <td colspan="2" style="text-align: center;">INDEKS SCORE MEDIS</td>
            <td colspan="2" style="text-align: center;">INDEKS SCORE PERAWAT</td>
          </tr>
          <tr>
            <td style="text-align: center;">Gawat Darurat</td>
            <td style="text-align: center;">Resiko</td>
            <td style="text-align: center;">Gawat Darurat</td>
            <td style="text-align: center;">Resiko</td>
          </tr>
          <tr>
            <td style="padding: 5px;">
              <input type="number" name="m_gawat_darurat" class="form-control" required style="width: 90%; text-align: right;">
            </td>
            <td style="padding: 5px;">
              <input type="number" name="m_resiko" class="form-control" required style="width: 90%; text-align: right;">
            </td>
            <td style="padding: 5px;">
              <input type="number" name="p_gawat_darurat" class="form-control" required style="width: 90%; text-align: right;">
            </td>
            <td style="padding: 5px;">
              <input type="number" name="p_resiko" class="form-control" required style="width: 90%; text-align: right;">
            </td>
          </tr>
        </table>
        </div>        
      </div>
    </form>
  </div>
  <div class="modal-footer">   
    <div class="btn-group">             
      <button type="submit" form="form_baru_ruang" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>    

<!--Edit RUang-->
<div id="modal_edit_ruang" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title">EDIT RUANG</h5>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_ruang" method="POST" action="{{ route('ruang_edit') }}">
    @csrf
      <input type="hidden" name="id" id="edit_id">

      <div class="control-group">
        <label class="control-label span3">Nama Ruang</label>
        <div class="controls span8">
          <input type="text" class="form-control" name="ruang" id="edit_ruang" required autofocus>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Rawat Jalan</label>
        <div class="controls span4">
          <select class="form-control" name="jalan" id="edit_jalan">
            <option value="0" {{ $rng->jalan == '0'? 'selected' : null }}>TIDAK</option>
            <option value="1" {{ $rng->jalan == '1'? 'selected' : null }}>YA</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Rawat Inap</label>
        <div class="controls span4">
          <select class="form-control" name="inap" id="edit_inap">
            <option value="0" {{ $rng->inap == '0'? 'selected' : null }}>TIDAK</option>
            <option value="1" {{ $rng->inap == '1'? 'selected' : null }}>YA</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Terima Pasien</label>
        <div class="controls span4">
          <select class="form-control" name="terima_pasien" id="edit_terima_pasien">
            <option value="0" {{ $rng->terima_pasien == '0'? 'selected' : null }}>TIDAK</option>
            <option value="1" {{ $rng->terima_pasien == '1'? 'selected' : null }}>YA</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Pasien Luar</label>
        <div class="controls span4">
          <select class="form-control" name="nonpasien" id="edit_nonpasien">
            <option value="0" {{ $rng->nonpasien == '0'? 'selected' : null }}>TIDAK</option>
            <option value="1" {{ $rng->nonpasien == '1'? 'selected' : null }}>YA</option>
          </select>
        </div>
      </div>

      <div class="control-group" style="margin-top: 10px;">        
        <div class="span12">
        <table width="100%" class="table table-bordered">
          <tr style="background-color: #f1f1f1;">
            <td colspan="2" style="text-align: center;">INDEKS SCORE MEDIS</td>
            <td colspan="2" style="text-align: center;">INDEKS SCORE PERAWAT</td>
          </tr>
          <tr>
            <td style="text-align: center;">Gawat Darurat</td>
            <td style="text-align: center;">Resiko</td>
            <td style="text-align: center;">Gawat Darurat</td>
            <td style="text-align: center;">Resiko</td>
          </tr>
          <tr>
            <td style="padding: 5px;">
              <input type="number" name="m_gawat_darurat" id="edit_m_gawat_darurat" class="form-control" required style="width: 90%; text-align: right;">
            </td>
            <td style="padding: 5px;">
              <input type="number" name="m_resiko" id="edit_m_resiko" class="form-control" required style="width: 90%; text-align: right;">
            </td>
            <td style="padding: 5px;">
              <input type="number" name="p_gawat_darurat" id="edit_p_gawat_darurat" class="form-control" required style="width: 90%; text-align: right;">
            </td>
            <td style="padding: 5px;">
              <input type="number" name="p_resiko" id="edit_p_resiko" class="form-control" required style="width: 90%; text-align: right;">
            </td>
          </tr>
        </table>
        </div>        
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_ruang" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="modal_layanan_edit">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title">Edit Layanan</h5>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_layanan_edit" method="POST" action="{{ route('ruang_layanan_edit') }}">
    @csrf
      <input type="hidden" name="id" id="id_layanan_edit">

      <select class="form-control" name="id_jasa" id="id_layanan_jasa_edit" size="15" required multiple>
        @foreach($layanan as $lay)
        <option value="{{ $lay->id }}" {{ $jasa->id_jasa == $lay->id? 'selected' : null }}>{{ $lay->jasa }}</option>
        @endforeach
      </select>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_layanan_edit" class="btn bprev">SIMPAN</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="modal_layanan_baru">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="exampleModalCenterTitle">Tambah Layanan</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_layanan_baru" method="POST" action="{{ route('ruang_layanan_baru') }}">
    @csrf
      <input type="hidden" name="id_ruang" id="id_ruang_layanan">

      <select class="form-control" name="id_jasa[]" id="id_jasa_layanan_baru" size="15" required multiple>
        @foreach($layanan as $lay)
        <option value="{{ $lay->id }}">{{ $lay->jasa }}</option>
        @endforeach
      </select>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_layanan_baru" class="btn bprev">SIMPAN</button>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('.btn-edit_ruang').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{route('ruang_editing')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#edit_id').val(data.id);
            $('#edit_ruang').val(data.ruang);
            $('#edit_jalan').val(data.jalan);
            $('#edit_inap').val(data.inap);
            $('#edit_terima_pasien').val(data.terima_pasien);
            $('#edit_nonpasien').val(data.nonpasien);
            $('#edit_m_gawat_darurat').val(data.m_gawat_darurat);
            $('#edit_m_resiko').val(data.m_resiko);
            $('#edit_p_gawat_darurat').val(data.p_gawat_darurat);
            $('#edit_p_resiko').val(data.p_resiko);
            $('#modal_edit_ruang').modal('show');
          }
        });
      });
    });

    $(document).ready(function() {
      $('.edit_layanan').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{route('ruang_layanan_editing')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#id_layanan_edit').val(data.id);
            $('#id_layanan_jasa_edit').val(data.id_jasa);
            $('#modal_layanan_edit').modal('show');
          }
        });
      });
    });

    $(document).ready(function() {
      $('.layanan_baru').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{route('ruang_layanan_baru_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#id_ruang_layanan').val(data);
            $('#modal_layanan_baru').modal('show');
          }
        });
      });
    });

    $(document).ready(function() {
      $('.aktif').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{ route('ruang_jenis') }}?id="+id,
          type: "GET",
          dataType: "JSON"          
        });
      });
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.25*box.clientHeight);

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