@extends('layouts.content')
@section('title','Histori Karyawan')

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <label style="font-size: 16px; font-weight: bold; margin-top: 1.5vh;" class="pull-left">{{ $karyawan->nama }}</label>
    <div class="pull-right" style="display: inline-flex;">
      <form method="GET" action="{{ route('karyawan_histori') }}" class="form-inline" style="margin-top: 5px; margin-bottom: 0; margin-right: 5px;">
      @csrf
        <input type="hidden" name="id" value="{{ $karyawan->id }}">

        <label>Tanggal</label>
        <input type="date" name="awal" value="{{ $awal }}" style="width: 130px;" onchange="this.form.submit();">
        <label>-</label>
        <input type="date" name="akhir" value="{{ $akhir }}" style="width: 130px;" onchange="this.form.submit();">
      </form>      
      <div class="btn-group">
        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
          MENU <span class="caret"></span>
        </button>
        <ul class="dropdown-menu pull-right">
          <li><a href="#" data-toggle="modal" data-target="#modal_ruang">RUANG UTAMA</a></li>
          <li><a href="#" data-toggle="modal" data-target="#modal_ruang_1">RUANG TAMBAHAN 1</a></li>
          <li><a href="#" data-toggle="modal" data-target="#modal_ruang_2">RUANG TAMBAHAN 2</a></li>
          <li class="divider"></li>
          <li><a href="#" data-toggle="modal" data-target="#modal_cuti">CUTI</a></li>
          <li><a href="#" data-toggle="modal" data-target="#modal_absen">KEHADIRAN</a></li>
          <li class="divider"></li>
          <li>
            <a href="#" onclick="document.getElementById('update_hist').submit();">UPDATE HISTORY</a>
            <a href="#" onclick="document.getElementById('generate_hist').submit();">GENERATE ULANG HISTORY</a>
          </li>
        </ul>

        <form hidden method="POST" action="{{ route('karyawan_update_history') }}" id="update_hist" onsubmit="return confirm('Update history {{ $karyawan->nama }} ?')">
        @csrf
          <input type="text" name="id" value="{{ $karyawan->id }}">
        </form>

        <form hidden method="POST" action="{{ route('karyawan_update_history_ulang') }}" id="generate_hist" onsubmit="return confirm('Buat ulang history {{ $karyawan->nama }} ?')">
        @csrf
          <input type="text" name="id" value="{{ $karyawan->id }}">
        </form>
      </div>
    </div>
  </div>
</div>

<div class="content">
  @include('layouts.pesan')  
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered">
    <thead>
      <tr>
        <th rowspan="2" style="padding: 0 10px;">Hari / Tanggal</th>
        <th colspan="3" style="padding: 0 10px;">Ruang</th>        
        <th rowspan="2" style="padding: 0 10px;">Kehadiran</th>
        <th rowspan="2" style="padding: 0 10px;">Skore</th>
      </tr>
      <tr>
        <th style="padding: 0 10px;">Utama</th>
        <th style="padding: 0 10px;">Tambahan 1</th>
        <th style="padding: 0 10px;">Tambahan 2</th>
      </tr>      
    </thead>
    <tbody>
      @foreach($histori as $hist)
      <tr>
        <td>{{ $hist->tanggal }}</td>
        <td class="min">
          <select style="margin-bottom: 0;" class="ruang" data-id="{{ $hist->id }}">
            @foreach($ruang as $rng)
            <option value="{{ $rng->id }}" {{ $hist->id_ruang == $rng->id? 'selected' : null }}>{{ $rng->ruang }}</option>
            @endforeach
          </select>
        </td>
        <td class="min">
          <select style="margin-bottom: 0;" class="ruang_1" data-id="{{ $hist->id }}">
            <option value=""></option>
            @foreach($ruang as $rng)
            <option value="{{ $rng->id }}" {{ $hist->id_ruang_1 == $rng->id? 'selected' : null }}>{{ $rng->ruang }}</option>
            @endforeach
          </select>
        </td>
        <td class="min">
          <select style="margin-bottom: 0;" class="ruang_2" data-id="{{ $hist->id }}">
            <option value=""></option>
            @foreach($ruang as $rng)
            <option value="{{ $rng->id }}" {{ $hist->id_ruang_2 == $rng->id? 'selected' : null }}>{{ $rng->ruang }}</option>
            @endforeach
          </select>
        </td>        
        <td class="min">
          @if($hist->cuti == 0)
          <select style="margin-bottom: 0;" class="hadir" data-id="{{ $hist->id }}">
            @foreach($absen as $abs)
            <option value="{{ $abs->id }}" {{ $hist->hadir == $abs->id? 'selected' : null }}>{{ $abs->absen }}</option>
            @endforeach
          </select>
          @else
          <select style="margin-bottom: 0;" disabled>
            @foreach($absen as $abs)
            <option value="{{ $abs->id }}" {{ $hist->hadir == $abs->id? 'selected' : null }}>{{ $abs->absen }}</option>
            @endforeach
          </select>
          @endif
        </td>
        <td class="min" style="text-align: right;">{{ number_format($hist->skore,2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>  
</div>

<div class="modal hide fade" id="modal_ruang">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title">Ruang Kerja Utama</h5>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_ruang" method="POST" action="{{ route('karyawan_his_ruang_periode') }}">
    @csrf
      <input type="hidden" name="id" value="{{ $karyawan->id }}">

      <div class="control-group">
        <label class="control-label span2">Tanggal</label>
        <div class="controls span7">
          <input type="date" name="awal" required autofocus>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span2">Sampai</label>
        <div class="controls span7">
          <input type="date" name="akhir" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span2">Ruang</label>
        <div class="controls span10">
          <select name="id_ruang" size="15" class="form-control">
            @foreach($ruang as $rng)
            <option value="{{ $rng->id }}">{{ $rng->ruang }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">   
    <div class="btn-group">             
      <button type="submit" form="form_ruang" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="modal_ruang_1">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title">Ruang Kerja Tambahan 1</h5>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_ruang_1" method="POST" action="{{ route('karyawan_his_ruang_1_periode') }}">
    @csrf
      <input type="hidden" name="id" value="{{ $karyawan->id }}">

      <div class="control-group">
        <label class="control-label span2">Tanggal</label>
        <div class="controls span7">
          <input type="date" name="awal" required autofocus>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span2">Sampai</label>
        <div class="controls span7">
          <input type="date" name="akhir" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span2">Ruang</label>
        <div class="controls span10">
          <select name="id_ruang_1" size="15" class="form-control">
            <option value=""></option>
            @foreach($ruang as $rng)
            <option value="{{ $rng->id }}">{{ $rng->ruang }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">   
    <div class="btn-group">             
      <button type="submit" form="form_ruang_1" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="modal_ruang_2">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title">Ruang Kerja Tambahan 2</h5>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_ruang_2" method="POST" action="{{ route('karyawan_his_ruang_2_periode') }}">
    @csrf
      <input type="hidden" name="id" value="{{ $karyawan->id }}">

      <div class="control-group">
        <label class="control-label span2">Tanggal</label>
        <div class="controls span7">
          <input type="date" name="awal" required autofocus>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span2">Sampai</label>
        <div class="controls span7">
          <input type="date" name="akhir" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span2">Ruang</label>
        <div class="controls span10">
          <select name="id_ruang_2" size="15" class="form-control">
            <option value=""></option>
            @foreach($ruang as $rng)
            <option value="{{ $rng->id }}">{{ $rng->ruang }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">   
    <div class="btn-group">             
      <button type="submit" form="form_ruang_2" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="modal_cuti">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title">Cuti Karyawan</h5>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_cuti" method="POST" action="{{ route('karyawan_his_cuti_periode') }}">
    @csrf
      <input type="hidden" name="id_karyawan" value="{{ $karyawan->id }}">

      <div class="control-group">
        <label class="control-label span3">Jenis</label>
        <div class="controls span6">
          <select name="id_jenis" class="form-control" required autofocus>
            <option value=""></option>
            @foreach($cuti as $cut)
            <option value="{{ $cut->id }}">{{ $cut->absen }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Tanggal</label>
        <div class="controls span6">
          <input type="date" name="awal" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Sampai</label>
        <div class="controls span6">
          <input type="date" name="akhir" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Keterangan</label>
        <div class="controls span8">
          <input type="text" name="keterangan" class="form-control">
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">   
    <div class="btn-group">             
      <button type="submit" form="form_cuti" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="modal_absen">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title">Kehadiran</h5>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_absen" method="POST" action="{{ route('karyawan_his_hadir_periode') }}">
    @csrf
      <input type="hidden" name="id" value="{{ $karyawan->id }}">

      <div class="control-group">
        <label class="control-label span2">Tanggal</label>
        <div class="controls span7">
          <input type="date" name="awal" required autofocus>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span2">Sampai</label>
        <div class="controls span7">
          <input type="date" name="akhir" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span2">Status</label>
        <div class="controls span10">
          <select name="hadir" class="form-control" size="10">
            @foreach($absen as $abs)
            <option value="{{ $abs->id }}">{{ $abs->absen }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">   
    <div class="btn-group">             
      <button type="submit" form="form_absen" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('.hadir').on("change",function() {
        $id    = $(this).attr('data-id');
        $hadir = $(this).val();

        $.ajax({
          url : "{{ route('karyawan_his_absen') }}",
          type: "GET",
          data: {'id': $id, 'hadir': $hadir},
        });
      });

      $('.ruang').on("change",function() {
        $id    = $(this).attr('data-id');
        $ruang = $(this).val();

        $.ajax({
          url : "{{ route('karyawan_his_ruang') }}",
          type: "GET",
          data: {'id': $id, 'ruang': $ruang},
        });
      });

      $('.ruang_1').on("change",function() {
        $id    = $(this).attr('data-id');
        $ruang_1 = $(this).val();

        $.ajax({
          url : "{{ route('karyawan_his_ruang_1') }}",
          type: "GET",
          data: {'id': $id, 'ruang_1': $ruang_1},
        });
      });

      $('.ruang_2').on("change",function() {
        $id    = $(this).attr('data-id');
        $ruang_2 = $(this).val();

        $.ajax({
          url : "{{ route('karyawan_his_ruang_2') }}",
          type: "GET",
          data: {'id': $id, 'ruang_2': $ruang_2},
        });
      });
    
      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.13*box.clientHeight);

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