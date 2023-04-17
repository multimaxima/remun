@extends('layouts.content')
@section('title','History Karyawan')

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12" style="display: inline-flex;">
        <form class="form-inline" method="GET" action="{{ route('karyawan_histori_update') }}" style="margin-top: 5px; margin-bottom: 0;">
        @csrf
          <label style="margin-top: 5px;">Histori Karyawan Tanggal</label>
          <input type="date" name="awal" value="{{ $awal }}" onchange="this.form.submit();" style="width: 130px;">
          <label style="margin-top: 5px;">s/d</label>
          <input type="date" name="akhir" value="{{ $akhir }}" onchange="this.form.submit();" style="width: 130px;">          
        </form>
        <div class="btn-group" style="margin-left: 5px;">
          <button class="btn btn-primary" data-toggle="modal" data-target="#modal_ruang">RUANG UTAMA</button>
          <button class="btn btn-primary" data-toggle="modal" data-target="#modal_ruang_1">RUANG TAMBAHAN 1</button>
          <button class="btn btn-primary" data-toggle="modal" data-target="#modal_ruang_2">RUANG TAMBAHAN 2</button>
          <button class="btn btn-primary" data-toggle="modal" data-target="#modal_absen">ABSENSI</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="content">  
  @include('layouts.pesan')
  <table id="tabel" width="100%" class="table table-striped table-hover table-bordered" style="font-size: 12px;">
    <thead>
      <tr>
        <th style="text-align: center;">Tanggal</th>
        <th style="text-align: center;">Ruang Utama</th>        
        <th style="text-align: center;">Ruang Tambahan 1</th>
        <th style="text-align: center;">Ruang Tambahan 2</th>        
        <th style="text-align: center;">Absen</th>
        <th style="text-align: center;">Skor</th>
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
          <select style="margin-bottom: 0;" class="hadir" data-id="{{ $hist->id }}">
            @foreach($absen as $abs)
            <option value="{{ $abs->id }}" {{ $hist->hadir == $abs->id? 'selected' : null }}>{{ $abs->absen }}</option>
            @endforeach
          </select>
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
    <h4 class="modal-title">Ruang Kerja Utama</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_ruang" method="POST" action="{{ route('karyawan_his_ruang_periode') }}">
    @csrf
      <input type="hidden" name="id" value="{{ Auth::user()->id }}">

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
    <h4 class="modal-title">Ruang Kerja Tambahan 1</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_ruang_1" method="POST" action="{{ route('karyawan_his_ruang_1_periode') }}">
    @csrf
      <input type="hidden" name="id" value="{{ Auth::user()->id }}">

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
    <h4 class="modal-title">Ruang Kerja Tambahan 2</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_ruang_2" method="POST" action="{{ route('karyawan_his_ruang_2_periode') }}">
    @csrf
      <input type="hidden" name="id" value="{{ Auth::user()->id }}">

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
    <h4 class="modal-title">Cuti</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_cuti" method="POST" action="{{ route('karyawan_his_cuti_periode') }}">
    @csrf
      <input type="hidden" name="id" value="{{ Auth::user()->id }}">

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
        <label class="control-label span2">Cuti</label>
        <div class="controls span10">
          <select name="cuti" class="form-control">
            <option value="1">CUTI</option>
            <option value="0">TIDAK</option>
          </select>
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
    <h4 class="modal-title">Kehadiran</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_absen" method="POST" action="{{ route('karyawan_his_hadir_periode') }}">
    @csrf
      <input type="hidden" name="id" value="{{ Auth::user()->id }}">

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
          <select name="hadir" class="form-control">
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
      $('.cuti').on("change",function() {
        $id    = $(this).attr('data-id');
        $cuti  = $(this).val();

        $.ajax({
          url : "{{ route('karyawan_his_cuti') }}",
          type: "GET",
          data: {'id': $id, 'cuti': $cuti},
        });
      });

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
      var tinggi = box.clientHeight-(0.11*box.clientHeight);

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