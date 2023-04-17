@extends('layouts.content')
@section('title','Histori Indeks Karyawan')

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
    <label style="font-size: 16px; font-weight: bold; margin-top: 1.5vh;" class="pull-left">{{ $karyawan->nama }}</label>
    <div class="pull-right" style="display: inline-flex;">
      <form method="GET" action="{{ route('karyawan_indeks_history') }}" class="form-inline" style="margin-top: 5px; margin-bottom: 0; margin-right: 5px;">
      @csrf
        <input type="hidden" name="id" value="{{ $karyawan->id }}">

        <label>Tanggal</label>
        <input type="date" name="awal" value="{{ $awal }}" style="width: 130px;" onchange="this.form.submit();">
        <label>-</label>
        <input type="date" name="akhir" value="{{ $akhir }}" style="width: 130px;" onchange="this.form.submit();">
      </form>
      <div class="btn-group">
        <button class="btn btn-primary edit" data-toggle="modal" data-id="{{ $karyawan->id }}">EDIT</button>
      </div>
    </div>
  </div>
</div>

<div class="content">
  @include('layouts.pesan')  
  <table id="tabel" class="table table-hover table-striped table-bordered">
    <thead>
      <tr>
        <th class="min" rowspan="3" style="padding: 0 10px; width: 150px;">Hari / Tanggal</th>
        <th class="min" rowspan="3" style="padding: 0 10px; width: 150px;">Ruang</th>
        <th class="min" rowspan="3" style="padding: 0 10px;">Gaji Pokok</th>
        <th class="min" rowspan="3" style="padding: 0 10px;">Koreksi</th>
        <th class="min" colspan="7" style="padding: 0 10px;">Indeks Dasar</th>
        <th class="min" colspan="7" style="padding: 0 10px;">Indeks Kompetensi</th>
        <th class="min" style="padding: 0 10px;" rowspan="3">Tempat Tugas</th>
        <th class="min" colspan="3" style="padding: 0 10px;">Indeks Resiko</th>
        <th class="min" colspan="3" style="padding: 0 10px;">Indeks Kegawat Daruratan</th>
        <th class="min" style="padding: 0 10px;" rowspan="3">Jabatan</th>
        <th class="min" colspan="7" style="padding: 0 10px;">Indeks Jabatan</th>
        <th class="min" style="padding: 0 10px;" colspan="3">Indeks Performance</th>
        <th class="min" style="padding: 0 10px;" rowspan="3">Score</th>
      </tr>      
      <tr>
        <th colspan="3" style="padding: 0 10px;">Koreksi</th>
        <th colspan="3" style="padding: 0 10px;">Masa Kerja</th>
        <th rowspan="2" style="padding: 0 10px;">Jml</th>
        <th colspan="3" style="padding: 0 10px;">Pendidikan</th>
        <th colspan="3" style="padding: 0 10px;">Diklat</th>
        <th rowspan="2" style="padding: 0 10px;">Jml</th>
        <th rowspan="2" style="padding: 0 10px;">N</th>
        <th rowspan="2" style="padding: 0 10px;">B</th>
        <th rowspan="2" style="padding: 0 10px;">S</th>
        <th rowspan="2" style="padding: 0 10px;">N</th>
        <th rowspan="2" style="padding: 0 10px;">B</th>
        <th rowspan="2" style="padding: 0 10px;">S</th>
        <th colspan="3" style="padding: 0 10px;">Jabatan</th>
        <th colspan="3" style="padding: 0 10px;">Kepanitiaan</th>
        <th rowspan="2" style="padding: 0 10px;">Jml</th>
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
      @foreach($histori as $hist)
      <tr>
        <td class="min">{{ $hist->tanggal }}</td>
        <td class="min">{{ $hist->ruang }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->gapok,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->koreksi,0) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->indeks_dasar,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->dasar_bobot,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->skor_indek,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->masa_kerja,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->masa_kerja_bobot,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->indeks_masa_kerja,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->skor_dasar,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->pend_nilai,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->pend_bobot,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->skor_pend,2) }}</td>              
        <td class="min" style="text-align: right;">{{ number_format($hist->diklat_nilai,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->diklat_bobot,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->skor_diklat,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->indeks_komp,2) }}</td>
        <td class="min">{{ $hist->temp_tugas }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->resiko_nilai,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->resiko_bobot,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->indeks_resiko,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->gawat_nilai,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->gawat_bobot,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->indeks_kegawat,2) }}</td>
        <td class="min">{{ $hist->jabatan }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->jab_nilai,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->jab_bobot,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->skor_jab,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->panitia_nilai,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->panitia_bobot,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->skor_pan,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->indeks_jabatan,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->perform_nilai,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->perform_bobot,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->indeks_perform,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($hist->total_indeks,2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>  
</div>

<div class="modal hide fade" id="modal_data_edit">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title">History Indeks Karyawan</h5>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_indeks" method="POST" action="{{ route('karyawan_indeks_edit_simpan') }}">
    @csrf
      <input type="hidden" name="id" id="edit_id">

      <div class="control-group">
        <label class="control-label span4">Tanggal</label>
        <div class="controls span7">
          <input type="date" name="awal" required autofocus>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span4">Sampai</label>
        <div class="controls span7">
          <input type="date" name="akhir" required>
        </div>
      </div>

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
      <button type="submit" form="form_indeks" class="btn bprev">SIMPAN</button>
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
          url : "{{route('karyawan_indeks_edit_show')}}?id="+id,
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
    
      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.19*box.clientHeight);

      $('#tabel').DataTable( {     
        scrollY:        tinggi,
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        searching:      false,
        sort:           false,
        info:           true,
        fixedColumns:   {
          leftColumns : 2,
        },
      });
    });
  </script>
@endsection