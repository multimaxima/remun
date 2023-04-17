@extends('layouts.content')
@section('title','Indeks Karyawan')

@section('style')
  <style type="text/css">
    td a{
      color: #000000;
    }

    .DTFC_LeftBodyLiner { overflow-x: hidden; }
  </style>
@endsection

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12" style="display: inline-flex;">
        <form class="form-inline" method="GET" action="{{ route('karyawan_indeks') }}" style="margin-top: 5px; margin-bottom: 0;">
        @csrf
          <input type="hidden" name="cari" value="{{ $cari }}">
          <input type="hidden" name="tampil" value="{{ $tampil }}">

          <select name="id_ruang" id="id_ruang" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">=== SEMUA RUANG ===</option>
            @foreach($ruang as $rng)
              <option value="{{ $rng->id }}" {{ $id_ruang == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
            @endforeach
          </select>

          <select name="id_bagian" id="id_bagian" onchange="this.form.submit();">
            <option value="" style="font-style: italic;">=== SEMUA BAGIAN ===</option>
            @foreach($bagian as $bag)
              <option value="{{ $bag->id }}" {{ $id_bagian == $bag->id? 'selected' : null }}>{{ strtoupper($bag->bagian) }}</option>
            @endforeach
          </select>
        </form>

        <div class="btn-group" style="margin-left: 5px;">
          <button type="submit" form="cetak" class="btn btn-primary" title="Cetak">CETAK</button>
          <a href="{{ route('karyawan_indeks_export') }}" class="btn btn-primary" title="Export">EXPORT</a>
        </div>

        <form hidden id="cetak" method="GET" action="{{ route('karyawan_indeks_cetak') }}" target="_blank">
        @csrf
          <input type="text" name="id_ruang" id="c_id_ruang" value="">
        </form>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <form method="GET" action="{{ route('karyawan_indeks') }}" class="form-inline" style="margin-bottom: 5px;">
  @csrf
    <input type="hidden" name="id_ruang" value="{{ $id_ruang }}">
    <input type="hidden" name="id_bagian" value="{{ $id_bagian }}">

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
  <table id="tabel" class="table table-hover table-striped table-bordered">
    <thead>
      <tr>
        <th class="min" rowspan="3"></th>
        <th class="min" rowspan="3" style="padding: 5px;">Nama Karyawan</th>
        <th class="min" rowspan="3" style="padding: 0 10px;">Pendidikan</th>
        <th class="min" style="padding: 0 10px;" colspan="7">Indeks Dasar</th>
        <th class="min" colspan="7" style="padding: 0 10px;">Indeks Kompetensi</th>
        <th class="min" style="padding: 0 10px;" rowspan="3">Tempat Tugas</th>              
        <th class="min" style="padding: 0 10px;" colspan="3">Indeks Resiko</th>
        <th class="min" style="padding: 0 10px;" colspan="3">Indeks Kegawat Daruratan</th>
        <th class="min" style="padding: 0 10px;" rowspan="3">Jabatan</th>
        <th class="min" style="padding: 0 10px;" colspan="7">Indeks Jabatan</th>
        <th class="min" style="padding: 0 10px;" colspan="3">Indeks Performance</th>
        <th class="min" style="padding: 0 10px;" rowspan="3">Score</th>
      </tr>
      <tr>
        <th style="padding: 0 10px;" colspan="3">Koreksi</th>
        <th style="padding: 0 10px;" colspan="3">Masa Kerja</th>
        <th style="padding: 0 10px; min-width: 30px;" rowspan="2">S</th>
        <th style="padding: 0 10px;" colspan="3">Pendidikan</th>
        <th style="padding: 0 10px;" colspan="3">Diklat</th>
        <th style="padding: 0 10px; min-width: 30px;" rowspan="2">Jml</th>
        <th style="padding: 0 10px; min-width: 30px;" rowspan="2">N</th>
        <th style="padding: 0 10px; min-width: 30px;" rowspan="2">B</th>
        <th style="padding: 0 10px; min-width: 30px;" rowspan="2">S</th>
        <th style="padding: 0 10px; min-width: 30px;" rowspan="2">N</th>
        <th style="padding: 0 10px; min-width: 30px;" rowspan="2">B</th>
        <th style="padding: 0 10px; min-width: 30px;" rowspan="2">S</th>
        <th style="padding: 0 10px;" colspan="3">Jabatan</th>
        <th style="padding: 0 10px;" colspan="3">Kepanitiaan</th>
        <th style="padding: 0 10px; min-width: 30px;" rowspan="2">Jml</th>
        <th style="padding: 0 10px; min-width: 30px;" rowspan="2">N</th>
        <th style="padding: 0 10px; min-width: 30px;" rowspan="2">B</th>
        <th style="padding: 0 10px; min-width: 30px;" rowspan="2">S</th>
      </tr>
      <tr>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">N</th>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">B</th>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">S</th>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">N</th>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">B</th>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">S</th>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">N</th>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">B</th>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">S</th>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">N</th>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">B</th>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">S</th>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">N</th>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">B</th>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">S</th>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">N</th>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">B</th>
        <th style="padding: 0 10px; text-align: center; min-width: 30px;">S</th>
      </tr>
    </thead>
    <tbody>
      @foreach($karyawan as $kary)                
      <tr>
        <td class="min">
          <div class="btn-group">
            <button class="btn btn-info btn-mini edit" title="Edit" data-toggle="modal" data-id="{{ $kary->id }}">
              <i class="icon-edit"></i>
            </button>
            <button class="btn btn-info btn-mini edit_history" target="_blank" title="Edit History Indeks" data-id="{{ $kary->id }}">
              <i class="icon-time"></i>
            </button>            
          </div>
        </td>
        <td class="min">{{ $kary->nama }}</td>
        <td class="min">{{ $kary->pendidikan }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->indeks_dasar,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->dasar_bobot,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->skor_indek,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->masa_kerja,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->masa_kerja_bobot,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->indeks_masa_kerja,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->skor_dasar,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->pend_nilai,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->pend_bobot,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->skor_pend,2) }}</td>              
        <td style="text-align: right;" class="min">{{ number_format($kary->diklat_nilai,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->diklat_bobot,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->skor_diklat,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->indeks_komp,2) }}</td>
        <td class="min">{{ $kary->temp_tugas }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->resiko_nilai,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->resiko_bobot,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->indeks_resiko,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->gawat_nilai,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->gawat_bobot,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->indeks_kegawat,2) }}</td>
        <td class="min">{{ $kary->jabatan }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->jab_nilai,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->jab_bobot,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->skor_jab,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->panitia_nilai,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->panitia_bobot,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->skor_pan,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->indeks_jabatan,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->perform_nilai,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->perform_bobot,2) }}</td>
        <td style="text-align: right;" class="min">{{ number_format($kary->indeks_perform,2) }}</td>              
        <td style="text-align: right;" class="min">{{ number_format($kary->total_indeks,2) }}</td>
      </tr>
      @endforeach
    </tbody>          
  </table>

  <div style="margin-top: 10px;">
    <div class="pull-left" style="font-size: 12px;">
      Menampilkan {{ number_format($karyawan->firstItem(),0) }} - {{ number_format($karyawan->lastItem(),0) }} dari {{ number_format($karyawan->total(),0) }} data
    </div>                               
    <div class="pagination pagination-small pull-right">
      {!! $karyawan->appends(request()->input())->render("pagination::bootstrap-4"); !!}
    </div>
  </div>
</div>

<form hidden method="GET" action="{{ route('karyawan_indeks_history') }}" id="form_history" target="_blank">
@csrf
  <input type="text" name="id" id="id_edit_history">
</form>

<div class="modal hide fade" id="modal_data_edit">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <label id="judul" style="font-size: 14px; font-weight: bold; margin-top: 5px;"></label>
  </div>
  <div class="modal-body">
    <form class="form-horizontal container-fluid fprev" id="edit_data" method="POST" action="{{ route('karyawan_indeks_simpan') }}">
    @csrf
      <input type="hidden" name="id" id="edit_id">

      <div class="control-group">
        <label class="control-label span5"></label>            
        <label class="control-label span2" style="text-align: center;">NILAI</label>            
        <label class="control-label span2 offset1" style="text-align: center;">BOBOT</label>            
      </div>

      <div class="control-group">
        <label class="span4 offset1">Koreksi</label>            
        <div class="controls span2">
          <input type="text" style="text-align: right;" class="form-control" id="edit_indeks_dasar" readonly>
        </div>
        <div class="controls span2 offset1">
          <input type="number" step="0.2" style="text-align: right;" class="form-control" name="dasar_bobot" id="edit_dasar_bobot" required autofocus>
        </div>
      </div>

      <div class="control-group">
        <label class="span4 offset1">Masa Kerja</label>            
        <div class="controls span2">
          <input type="text" style="text-align: right;" class="form-control" id="edit_masa_kerja" required readonly>
        </div>
        <div class="controls span2 offset1">
          <input type="number" step="0.2" style="text-align: right;" class="form-control" name="masa_kerja_bobot" id="edit_masa_kerja_bobot" required>
        </div>
      </div>

      <div class="control-group">
        <label class="span4 offset1">Pendidikan</label>
        <div class="controls span2">
          <input type="number" step="0.2" style="text-align: right;" class="form-control" name="pend_nilai" id="edit_pend_nilai" required>
        </div>
        <div class="controls span2 offset1">
          <input type="number" step="0.2" style="text-align: right;" class="form-control" name="pend_bobot" id="edit_pend_bobot" required>
        </div>
      </div>

      <div class="control-group">
        <label class="span4 offset1">Diklat</label>
        <div class="controls span2">
          <input type="number" step="0.2" style="text-align: right;" class="form-control" name="diklat_nilai" id="edit_diklat_nilai" required>
        </div>
        <div class="controls span2 offset1">
          <input type="number" step="0.2" style="text-align: right;" class="form-control" name="diklat_bobot" id="edit_diklat_bobot" required>
        </div>
      </div>

      <div class="control-group">
        <label class="span4 offset1">Indeks Resiko</label>
        <div class="controls span2">
          <input type="number" step="0.2" style="text-align: right;" class="form-control" name="resiko_nilai" id="edit_resiko_nilai" readonly>
        </div>
        <div class="controls span2 offset1">
          <input type="number" step="0.2" style="text-align: right;" class="form-control" name="resiko_bobot" id="edit_resiko_bobot" required>
        </div>
      </div>

      <div class="control-group">
        <label class="span4 offset1">Kegawat Daruratan</label>
        <div class="controls span2">
          <input type="number" step="0.2" style="text-align: right;" class="form-control" name="gawat_nilai" id="edit_gawat_nilai" readonly>
        </div>
        <div class="controls span2 offset1">
          <input type="number" step="0.2" style="text-align: right;" class="form-control" name="gawat_bobot" id="edit_gawat_bobot" required>
        </div>
      </div>

      <div class="control-group">
        <label class="span4 offset1">Indeks Jabatan</label>
        <div class="controls span2">
          <input type="number" step="0.2" style="text-align: right;" class="form-control" name="jab_nilai" id="edit_jab_nilai" required>
        </div>
        <div class="controls span2 offset1">
          <input type="number" step="0.2" style="text-align: right;" class="form-control" name="jab_bobot" id="edit_jab_bobot" required>
        </div>
      </div>

      <div class="control-group">
        <label class="span4 offset1">Kepanitiaan</label>
        <div class="controls span2">
          <input type="number" step="0.2" style="text-align: right;" class="form-control" name="panitia_nilai" id="edit_panitia_nilai" required>
        </div>
        <div class="controls span2 offset1">
          <input type="number" step="0.2" style="text-align: right;" class="form-control" name="panitia_bobot" id="edit_panitia_bobot" required>
        </div>
      </div>

      <div class="control-group">
        <label class="span4 offset1">Performance</label>
        <div class="controls span2">
          <input type="number" step="0.2" style="text-align: right;" class="form-control" name="perform_nilai" id="edit_perform_nilai" required>
        </div>
        <div class="controls span2 offset1">
          <input type="number" step="0.2" style="text-align: right;" class="form-control" name="perform_bobot" id="edit_perform_bobot" required>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="edit_data" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('.edit').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{route('karyawan_indeks_simpan_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#edit_id').val(data.id);
            $('#edit_indeks_dasar').val(data.indeks_dasar);
            $('#edit_dasar_bobot').val(data.dasar_bobot);
            $('#edit_masa_kerja').val(data.masa_kerja);
            $('#edit_masa_kerja_bobot').val(data.masa_kerja_bobot);
            $('#edit_pend_nilai').val(data.pend_nilai);
            $('#edit_pend_bobot').val(data.pend_bobot);
            $('#edit_diklat_nilai').val(data.diklat_nilai);
            $('#edit_diklat_bobot').val(data.diklat_bobot);
            $('#edit_resiko_nilai').val(data.resiko_nilai);
            $('#edit_resiko_bobot').val(data.resiko_bobot);
            $('#edit_gawat_nilai').val(data.gawat_nilai);
            $('#edit_gawat_bobot').val(data.gawat_bobot);
            $('#edit_jab_nilai').val(data.jab_nilai);
            $('#edit_jab_bobot').val(data.jab_bobot);
            $('#edit_panitia_nilai').val(data.panitia_nilai);
            $('#edit_panitia_bobot').val(data.panitia_bobot);
            $('#edit_perform_nilai').val(data.perform_nilai);
            $('#edit_perform_bobot').val(data.perform_bobot);
            $('#judul').html(data.nama);
            $('#modal_data_edit').modal('show');
          }
        });
      });

      $('.edit_history').on("click",function() {
        var id = $(this).attr('data-id');
        $('#id_edit_history').val(id);
        $('#form_history').submit();
      });
    });

    window.onload=function() {
      $id_ruang = document.getElementById('id_ruang').value;
      document.getElementById('c_id_ruang').value = $id_ruang;
    }
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.26*box.clientHeight);

      $('#tabel').DataTable( {     
        scrollY:        tinggi,
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        searching:      false,
        sort:           false,
        info:           false,
        fixedColumns:   {
          leftColumns : 3,
        },
      });
    });
  </script>
@endsection