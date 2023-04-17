@extends('layouts.content')
@section('title','Histori Karyawan')

@section('style')
  <style type="text/css">
    td a{
      color: #424242;
    }
  </style>
@endsection

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12" style="display: inline-flex;">
        <form class="form-inline" method="GET" action="{{ route('karyawan_histori_admin') }}" style="margin-top: 5px; margin-bottom: 0;">
        @csrf
          <input type="hidden" name="cari" value="{{ $cari }}">
          <input type="hidden" name="tampil" value="{{ $tampil }}">

          <label style="margin-right: 5px; margin-top: 5px;">Tanggal</label>
          <input type="date" name="tanggal" value="{{ $tanggal }}" style="width: 130px;" onchange="this.form.submit();">
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
      </div>
    </div>
  </div>
</div>

<div class="content">
  <form method="GET" action="{{ route('karyawan_histori_admin') }}" class="form-inline" style="margin-bottom: 5px;">
  @csrf
    <input type="hidden" name="id_ruang" value="{{ $id_ruang }}">
    <input type="hidden" name="id_bagian" value="{{ $id_bagian }}">
    <input type="hidden" name="tanggal" value="{{ $tanggal }}">

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
  <table id="tabel" width="100%" class="table table-striped table-hover table-bordered" style="font-size: 12px;">
    <thead>
      <tr>
        <th rowspan="3" style="vertical-align: middle; text-align: center; padding: 0 10px;">No.</th>
        <th rowspan="3" style="vertical-align: middle; text-align: center; padding: 0 10px;">Nama</th>
        <th rowspan="3" style="vertical-align: middle; text-align: center; padding: 0 10px;">Bagian</th>
        <th class="min" colspan="3" style="vertical-align: middle; text-align: center; padding: 0 10px;">Ruang Kerja</th>        
        <th class="min" rowspan="3" style="vertical-align: middle; text-align: center; padding: 0 10px;">Jabatan</th>
        <th class="min" rowspan="3" style="vertical-align: middle; text-align: center; padding: 0 10px;">Status</th>
        <th class="min" rowspan="3" style="vertical-align: middle; text-align: center; padding: 0 10px;">Gol</th>        
        <th class="min" rowspan="3" style="vertical-align: middle; text-align: center; padding: 0 10px;">Cuti</th>
        <th class="min" rowspan="3" style="vertical-align: middle; text-align: center; padding: 0 10px;">Hadir</th>
        <th class="min" rowspan="3" style="vertical-align: middle; text-align: center; padding: 0 10px;">Pendidikan</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;" colspan="7">Indeks Dasar</th>
        <th class="min" colspan="7" style="vertical-align: middle; text-align: center; padding: 0 10px;">Indeks Kompetensi</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;" rowspan="3">Tempat Tugas</th>              
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;" colspan="3">Indeks Resiko</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;" colspan="3">Indeks Kegawat Daruratan</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;" rowspan="3">Jabatan</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;" colspan="7">Indeks Jabatan</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;" colspan="3">Indeks Performance</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;" rowspan="3">Total Score</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;" colspan="17">Jasa</th>
      </tr>
      <tr>
        <th class="min" rowspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">Utama</th>
        <th class="min" rowspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">1</th>
        <th class="min" rowspan="2" style="vertical-align: middle; text-align: center; padding: 0 10px;">2</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;" colspan="3">Koreksi</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;" colspan="3">Masa Kerja</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px; min-width: 30px;" rowspan="2">S</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;" colspan="3">Pendidikan</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;" colspan="3">Diklat</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px; min-width: 30px;" rowspan="2">Jml</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px; min-width: 30px;" rowspan="2">N</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px; min-width: 30px;" rowspan="2">B</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px; min-width: 30px;" rowspan="2">S</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px; min-width: 30px;" rowspan="2">N</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px; min-width: 30px;" rowspan="2">B</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px; min-width: 30px;" rowspan="2">S</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;" colspan="3">Jabatan</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px;" colspan="3">Kepanitiaan</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px; min-width: 30px;" rowspan="2">Jml</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px; min-width: 30px;" rowspan="2">N</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px; min-width: 30px;" rowspan="2">B</th>
        <th class="min" style="vertical-align: middle; text-align: center; padding: 0 10px; min-width: 30px;" rowspan="2">S</th>
        <th class="min" colspan="4" style="text-align: center; vertical-align: middle;">Non Penghasil</th>
        <th class="min" colspan="13" style="text-align: center; vertical-align: middle;">Penghasil</th>
      </tr>    
      <tr>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">N</th>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">B</th>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">S</th>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">N</th>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">B</th>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">S</th>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">N</th>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">B</th>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">S</th>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">N</th>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">B</th>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">S</th>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">N</th>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">B</th>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">S</th>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">N</th>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">B</th>
        <th class="min" style="padding: 0 10px; text-align: center; min-width: 30px;">S</th>
        <th class="min" style="text-align: center; vertical-align: middle; padding: 0 10px;">Pos Remun</th>
        <th class="min" style="text-align: center; vertical-align: middle; padding: 0 10px;">Direksi</th>
        <th class="min" style="text-align: center; vertical-align: middle; padding: 0 10px;">Staf</th>
        <th class="min" style="text-align: center; vertical-align: middle; padding: 0 10px;">Admin</th>
        <th class="min" style="text-align: center; vertical-align: middle; padding: 0 10px;">Perawat</th>
        <th class="min" style="text-align: center; vertical-align: middle; padding: 0 10px;">Apoteker</th>
        <th class="min" style="text-align: center; vertical-align: middle; padding: 0 10px;">Ass Apotek</th>
        <th class="min" style="text-align: center; vertical-align: middle; padding: 0 10px;">Adm Farmasi</th>
        <th class="min" style="text-align: center; vertical-align: middle; padding: 0 10px;">Pen Anastesi</th>
        <th class="min" style="text-align: center; vertical-align: middle; padding: 0 10px;">Per Ass 1</th>
        <th class="min" style="text-align: center; vertical-align: middle; padding: 0 10px;">Per Ass 2</th>
        <th class="min" style="text-align: center; vertical-align: middle; padding: 0 10px;">Instrumen</th>
        <th class="min" style="text-align: center; vertical-align: middle; padding: 0 10px;">Sirkuler</th>
        <th class="min" style="text-align: center; vertical-align: middle; padding: 0 10px;">Per Pend 1</th>      
        <th class="min" style="text-align: center; vertical-align: middle; padding: 0 10px;">Per Pend 2</th>      
        <th class="min" style="text-align: center; vertical-align: middle; padding: 0 10px;">Fisioterapis</th>
        <th class="min" style="text-align: center; vertical-align: middle; padding: 0 10px;">Pemulasaran</th>
      </tr>  
    </thead>
    <tbody>
      <?php $no = 0;?>
      @foreach($karyawan as $kary)
      <?php $no++ ;?>
      <tr>        
        <td class="min" style="text-align: right; padding-right: 5px;">{{ $no }}.</td>
        <td class="min">{{ $kary->nama }}</td>
        <td class="min">{{ strtoupper($kary->bagian) }}</td>
        <td class="min">{{ strtoupper($kary->ruang) }}</td>
        <td class="min">{{ strtoupper($kary->ruang_1) }}</td>
        <td class="min">{{ strtoupper($kary->ruang_2) }}</td>
        <td class="min">{{ strtoupper($kary->jabatan) }}</td>
        <td class="min">{{ strtoupper($kary->status) }}</td>
        <td style="text-align: center;" class="min">{{ strtoupper($kary->golongan) }}</td>        
        <td class="min" style="text-align: center;">
          @if($kary->cuti == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td class="min" style="text-align: center;">
          @if($kary->hadir == 0)
            <i class="icon-ok"></i>
          @endif
        </td>
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
        <td style="text-align: right;" class="min">{{ number_format($kary->skore,2) }}</td>
        <td style="text-align: center;" class="min">
          @if($kary->pos_remun == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($kary->direksi == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($kary->staf == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($kary->jp_admin == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($kary->jp_perawat == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($kary->apoteker == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($kary->ass_apoteker == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($kary->admin_farmasi == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($kary->pen_anastesi == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($kary->per_asisten_1 == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($kary->per_asisten_2 == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($kary->instrumen == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($kary->sirkuler == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($kary->per_pendamping_1 == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($kary->per_pendamping_2 == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($kary->fisioterapis == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
        <td style="text-align: center;" class="min">
          @if($kary->pemulasaran == 1)
            <i class="icon-ok"></i>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div>
    <div class="pull-left" style="font-size: 12px;">
      Menampilkan {{ number_format($karyawan->firstItem(),0) }} - {{ number_format($karyawan->lastItem(),0) }} dari {{ number_format($karyawan->total(),0) }} data
    </div>                               
    <div class="pagination pagination-small pull-right">
      {!! $karyawan->appends(request()->input())->render("pagination::bootstrap-4"); !!}
    </div>
  </div>
</div>       
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.3*box.clientHeight);

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