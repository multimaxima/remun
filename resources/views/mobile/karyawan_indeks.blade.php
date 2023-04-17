@extends('mobile.layouts.content')

@section('bawah')
  <li><a href="#" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne"><i class="fa fa-filter"></i>Filter</a></li>
  <li><a href="{{ route('informasi_software') }}"><i class="fa fa-info"></i>Informasi</a></li>
@endsection

@section('content')
<div class="row isi">
  <div class="container" style="margin-top: 9vh;">
  </div>
</div>

<div class="collapse" id="collapseOne" aria-labelledby="headingOne" style="margin-bottom: 1vh;">
  <div class="row isi">
    <div class="container">    
      <div class="card">
        <div class="card-body user-data-card" style="font-size: 3vw;"> 
          <form class="form-horizontal" method="GET" action="{{ route('karyawan_indeks') }}" style="margin-top: 5px; margin-bottom: 0;">
          @csrf
            <input type="hidden" name="cari" value="{{ $cari }}">
            <input type="hidden" name="tampil" value="{{ $tampil }}">

            <div class="mb-1">
              <div class="title mb-1">Ruang</div>
              <select name="id_ruang" class="form-control" id="id_ruang" onchange="this.form.submit();">
                <option value="" style="font-style: italic;">SEMUA</option>
                @foreach($ruang as $rng)
                  <option value="{{ $rng->id }}" {{ $id_ruang == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-1">
              <div class="title mb-1">Bagian</div>
              <select name="id_bagian" class="form-control" id="id_bagian" onchange="this.form.submit();">
                <option value="" style="font-style: italic;">=== SEMUA BAGIAN ===</option>
                @foreach($bagian as $bag)
                  <option value="{{ $bag->id }}" {{ $id_bagian == $bag->id? 'selected' : null }}>{{ strtoupper($bag->bagian) }}</option>
                @endforeach
              </select>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row isi" style="padding-bottom: 10vh;">
  <div class="container">
    <div class="card" style="margin-top: 1vh;">
      <div class="card-body" style="font-size: 3vw; overflow-x: auto;">
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
                <button class="btn btn-info btn-mini edit" title="Edit" data-toggle="modal" data-id="{{ $kary->id }}">
                  <i class="icon-edit"></i>
                </button>
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
      </div>
    </div>

    <div style="margin-top: 10px;">
      <div class="pull-left" style="font-size: 12px;">
        {{ number_format($karyawan->firstItem(),0) }} - {{ number_format($karyawan->lastItem(),0) }} dari {{ number_format($karyawan->total(),0) }} data
      </div>                               
      <div class="pagination pagination-sm pull-right">
        {!! $karyawan->appends(request()->input())->render("pagination::simple-bootstrap-4"); !!}
      </div>
    </div>
  </div>
</div>      

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