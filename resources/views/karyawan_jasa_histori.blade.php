@extends('layouts.content')
@section('title','History Jasa Karyawan')

@section('style')
  <style type="text/css">
    .DTFC_LeftBodyLiner { overflow-x: hidden; }
  </style>
@endsection

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <label style="font-size: 16px; font-weight: bold; margin-top: 1.5vh;" class="pull-left">{{ $karyawan->nama }}</label>
    <div class="pull-right" style="display: inline-flex;">
      <form method="GET" action="{{ route('karyawan_jasa_history') }}" class="form-inline" style="margin-top: 5px; margin-bottom: 0; margin-right: 5px;">
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
        <th rowspan="2" style="padding: 0 10px;">Taanggal</th>
        <th rowspan="2" style="padding: 0 10px;">Ruang</th>
        <th rowspan="2" style="padding: 0 10px;">Bagian</th>
        <th colspan="4" style="padding: 0 10px;">Non Penghasil</th>
        <th colspan="13" style="padding: 0 10px;">Penghasil</th>
      </tr>
      <tr>        
        <th class="min" style="padding: 5px; writing-mode: vertical-lr;" title="Direksi">Direksi</th>
        <th class="min" style="padding: 5px; writing-mode: vertical-lr;" title="Staf Direksi">Staf</th>
        <th class="min" style="padding: 5px; writing-mode: vertical-lr;" title="Administrasi">Admin</th>
        <th class="min" style="padding: 5px; writing-mode: vertical-lr;" title="Pos Remunerasi">Pos Remun</th>
        <th class="min" style="padding: 5px; writing-mode: vertical-lr;" title="JP Langsung Perawat">Perawat</th>
        <th class="min" style="padding: 5px; writing-mode: vertical-lr;" title="Apoteker">Apoteker</th>
        <th class="min" style="padding: 5px; writing-mode: vertical-lr;" title="Asisten Apoteker">Ass Apotek</th>
        <th class="min" style="padding: 5px; writing-mode: vertical-lr;" title="Admin Farmasi">Adm Farmasi</th>
        <th class="min" style="padding: 5px; writing-mode: vertical-lr;" title="Penata Anastesi">Pen Anastesi</th>
        <th class="min" style="padding: 5px; writing-mode: vertical-lr;" title="Perawat Asisten 1">Per Ass 1</th>
        <th class="min" style="padding: 5px; writing-mode: vertical-lr;" title="Perawat Asisten 2">Per Ass 2</th>
        <th class="min" style="padding: 5px; writing-mode: vertical-lr;" title="Instrumen">Instrumen</th>
        <th class="min" style="padding: 5px; writing-mode: vertical-lr;" title="Sirkuler">Sirkuler</th>
        <th class="min" style="padding: 5px; writing-mode: vertical-lr;" title="Perawat Pendamping 1">Per Pend 1</th>      
        <th class="min" style="padding: 5px; writing-mode: vertical-lr;" title="Perawat Pendamping 2">Per Pend 2</th>      
        <th class="min" style="padding: 5px; writing-mode: vertical-lr;" title="Fisioterapis">Fisioterapis</th>
        <th class="min" style="padding: 5px; writing-mode: vertical-lr;" title="Pemulasaran">Pemulasaran</th>
      </tr>
    </thead>
    <tbody>
      @foreach($histori as $hist)                
      <tr>        
        <td class="min">{{ $hist->tanggal }}</td>
        <td class="min">{{ $hist->ruang }}</td>
        <td class="min">{{ strtoupper($hist->bagian) }}</td>
        <td style="text-align: center;" class="min">
          @if($hist->direksi == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($hist->staf == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($hist->jp_admin == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($hist->pos_remun == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($hist->jp_perawat == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($hist->apoteker == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($hist->ass_apoteker == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($hist->admin_farmasi == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($hist->pen_anastesi == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($hist->per_asisten_1 == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($hist->per_asisten_2 == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($hist->instrumen == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($hist->sirkuler == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($hist->per_pendamping_1 == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($hist->per_pendamping_2 == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($hist->fisioterapis == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($hist->pemulasaran == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>      

<div class="modal hide fade" id="modal_data_edit">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <label style="font-size: 14px; font-weight: bold; margin-top: 5px;" class="modal-title" id="judul"></label>
  </div>
  <div class="modal-body">
    <form class="form-horizontal container-fluid fprev" id="edit_data" method="POST" action="{{ route('karyawan_jasa_history_simpan') }}">
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
      <hr>
      <div class="row">
        <div class="span6">
          <div class="control-group">
            <label class="control-label span7">Direksi</label>
            <div class="controls span5">
              <select class="form-control" name="direksi" id="edit_direksi">
                <option value="0">TIDAK</option>
                <option value="1">YA</option>
              </select>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label span7">Staf Direksi</label>
            <div class="controls span5">
              <select class="form-control" name="staf" id="edit_staf">
                <option value="0">TIDAK</option>
                <option value="1">YA</option>
              </select>
            </div>
          </div>          

          <div class="control-group">
            <label class="control-label span7">Administrasi</label>
            <div class="controls span5">
              <select class="form-control" name="jp_admin" id="edit_jp_admin">
                <option value="0">TIDAK</option>
                <option value="1">YA</option>
              </select>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label span7">Pos Remunerasi</label>
            <div class="controls span5">
              <select class="form-control" name="pos_remun" id="edit_pos_remun">
                <option value="0">TIDAK</option>
                <option value="1">YA</option>
              </select>
            </div>
          </div>
        </div>

        <div class="span6">
          <div class="control-group">
            <label class="control-label span7">Perawat</label>
            <div class="controls span5">
              <select class="form-control" name="jp_perawat" id="edit_jp_perawat">
                <option value="0">TIDAK</option>
                <option value="1">YA</option>
              </select>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label span7">Apoteker</label>
            <div class="controls span5">
              <select class="form-control" name="apoteker" id="edit_apoteker">
                <option value="0">TIDAK</option>
                <option value="1">YA</option>
              </select>
            </div>
          </div>                

          <div class="control-group">
            <label class="control-label span7">Ass Apoteker</label>
            <div class="controls span5">
              <select class="form-control" name="ass_apoteker" id="edit_ass_apoteker">
                <option value="0">TIDAK</option>
                <option value="1">YA</option>
              </select>
            </div>
          </div>                

          <div class="control-group">
            <label class="control-label span7">Admin Farmasi</label>
            <div class="controls span5">
              <select class="form-control" name="admin_farmasi" id="edit_admin_farmasi">
                <option value="0">TIDAK</option>
                <option value="1">YA</option>
              </select>
            </div>
          </div>                

          <div class="control-group">
            <label class="control-label span7">Pen. Anastesi</label>
            <div class="controls span5">
              <select class="form-control" name="pen_anastesi" id="edit_pen_anastesi">
                <option value="0">TIDAK</option>
                <option value="1">YA</option>
              </select>
            </div>
          </div>          

          <div class="control-group">
            <label class="control-label span7">Per. Asisten 1</label>
            <div class="controls span5">
              <select class="form-control" name="per_asisten_1" id="edit_per_asisten_1">
                <option value="0">TIDAK</option>
                <option value="1">YA</option>
              </select>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label span7">Per. Asisten 2</label>
            <div class="controls span5">
              <select class="form-control" name="per_asisten_2" id="edit_per_asisten_2">
                <option value="0">TIDAK</option>
                <option value="1">YA</option>
              </select>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label span7">Instrumen</label>
            <div class="controls span5">
              <select class="form-control" name="instrumen" id="edit_instrumen">
                <option value="0">TIDAK</option>
                <option value="1">YA</option>
              </select>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label span7">Sirkuler</label>
            <div class="controls span5">
              <select class="form-control" name="sirkuler" id="edit_sirkuler">
                <option value="0">TIDAK</option>
                <option value="1">YA</option>
              </select>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label span7">Pemdamping 1</label>
            <div class="controls span5">
              <select class="form-control" name="per_pendamping_1" id="edit_per_pendamping_1">
                <option value="0">TIDAK</option>
                <option value="1">YA</option>
              </select>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label span7">Pendamping 2</label>
            <div class="controls span5">
              <select class="form-control" name="per_pendamping_2" id="edit_per_pendamping_2">
                <option value="0">TIDAK</option>
                <option value="1">YA</option>
              </select>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label span7">Fisioterapis</label>
            <div class="controls span5">
              <select class="form-control" name="fisioterapis" id="edit_fisioterapis">
                <option value="0">TIDAK</option>
                <option value="1">YA</option>
              </select>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label span7">Pemulasaran</label>
            <div class="controls span5">
              <select class="form-control" name="pemulasaran" id="edit_pemulasaran">
                <option value="0">TIDAK</option>
                <option value="1">YA</option>
              </select>
            </div>
          </div>
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
    window.onload=function() {
      $id_ruang = document.getElementById('id_ruang').value;
      document.getElementById('c_id_ruang').value = $id_ruang;
    }
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('.edit').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{route('karyawan_jasa_simpan_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#edit_id').val(data.id);
            $('#edit_pos_remun').val(data.pos_remun);
            $('#edit_jp_perawat').val(data.jp_perawat);
            $('#edit_apoteker').val(data.apoteker);
            $('#edit_direksi').val(data.direksi);
            $('#edit_ass_apoteker').val(data.ass_apoteker);
            $('#edit_staf').val(data.staf);
            $('#edit_admin_farmasi').val(data.admin_farmasi);
            $('#edit_jp_admin').val(data.jp_admin);
            $('#edit_pen_anastesi').val(data.pen_anastesi);
            $('#edit_per_asisten_1').val(data.per_asisten_1);
            $('#edit_per_asisten_2').val(data.per_asisten_2);
            $('#edit_instrumen').val(data.instrumen);
            $('#edit_sirkuler').val(data.sirkuler);
            $('#edit_per_pendamping_1').val(data.per_pendamping_1);
            $('#edit_per_pendamping_2').val(data.per_pendamping_2);
            $('#edit_fisioterapis').val(data.fisioterapis);
            $('#edit_pemulasaran').val(data.pemulasaran);
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

    $(document).ready(function() {
      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.34*box.clientHeight);

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