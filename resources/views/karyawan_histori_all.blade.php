@extends('layouts.content')
@section('title','Histori Karyawan')

@section('content')
<div class="navbar">
  <div class="navbar-inner">
    <form method="GET" action="{{ route('karyawan_histori_all') }}" class="form-inline" style="margin-top: 5px; margin-bottom: 0; margin-right: 5px;">
    @csrf
      <label>Nama Karyawan</label>
      <select name="id_karyawan" class="select2">
        <option value="">=== PILIH NAMA KARYAWAN ===</option>
        @foreach($karyawan as $kar)
        <option value="{{ $kar->id }}" {{ $kar->id == $id_karyawan? 'selected' : null }}>{{ $kar->nama }}</option>
        @endforeach
      </select>

      <label style="margin-left: 10px;">Tanggal</label>
      <input type="date" name="awal" value="{{ $awal }}" style="width: 130px;">
      <label>s/d</label>
      <input type="date" name="akhir" value="{{ $akhir }}" style="width: 130px;">
      <button class="btn btn-primary" type="submit" style="margin-top: 0;">TAMPILKAN</button>
    </form>      
  </div>
</div>

@if($histori)
<div class="content">
  @include('layouts.pesan')  
  <table width="100%" id="tabel" class="table table-hover table-striped table-bordered">
    <thead>
      <tr>
        <th rowspan="3" style="padding: 0 10px;">Tanggal</th>
        <th rowspan="3" style="padding: 0 10px;">Bagian</th>
        <th rowspan="3" style="padding: 0 10px;">Status</th>
        <th colspan="3" style="padding: 0 10px;">Ruang</th>
        <th rowspan="3" style="padding: 0 10px;">Pendidikan</th>
        <th rowspan="3" style="padding: 0 10px;">Gapok</th>
        <th rowspan="3" style="padding: 0 10px;">Koreksi</th>
        <th colspan="7" style="padding: 0 10px;">Indeks Dasar</th>
        <th colspan="7" style="padding: 0 10px;">Indeks Kompetensi</th>
        <th colspan="3" style="padding: 0 10px;">Indeks Resiko</th>
        <th colspan="3" style="padding: 0 10px;">Indeks Kegawat Daruratan</th>
        <th colspan="7" style="padding: 0 10px;">Indeks Jabatan</th>
        <th colspan="3" style="padding: 0 10px;">Indeks Performance</th>
        <th rowspan="3" style="padding: 0 10px;">Score</th>
      </tr>
      <tr>
        <th rowspan="2" style="padding: 0 10px;">Utama</th>
        <th rowspan="2" style="padding: 0 10px;">Tambahan 1</th>
        <th rowspan="2" style="padding: 0 10px;">Tambahan 2</th>
        <th colspan="3" style="padding: 0 10px;">Koreksi</th>
        <th colspan="3" style="padding: 0 10px;">Masa Kerja</th>
        <th rowspan="2" style="padding: 0 10px;">JML</th>
        <th colspan="3" style="padding: 0 10px;">Pendiikan</th>
        <th colspan="3" style="padding: 0 10px;">Diklat</th>
        <th rowspan="2" style="padding: 0 10px;">JML</th>
        <th rowspan="2">N</th>
        <th rowspan="2">B</th>
        <th rowspan="2">S</th>
        <th rowspan="2">N</th>
        <th rowspan="2">B</th>
        <th rowspan="2">S</th>
        <th colspan="3" style="padding: 0 10px;">Jabatan</th>
        <th colspan="3" style="padding: 0 10px;">Kepanitiaan</th>
        <th rowspan="2" style="padding: 0 10px;">JML</th>
        <th rowspan="2">N</th>
        <th rowspan="2">B</th>
        <th rowspan="2">S</th>
      </tr>
      <tr>
        <th>N</th>
        <th>B</th>
        <th>S</th>
        <th>N</th>
        <th>B</th>
        <th>S</th>
        <th>N</th>
        <th>B</th>
        <th>S</th>
        <th>N</th>
        <th>B</th>
        <th>S</th>
        <th>N</th>
        <th>B</th>
        <th>S</th>
        <th>N</th>
        <th>B</th>
        <th>S</th>
      </tr>
      <tr>
        <th></th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_bagian">EDIT KOLEKTIF</button>
        </th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_status">EDIT KOLEKTIF</button>
        </th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_ruang">EDIT KOLEKTIF</button>
        </th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_ruang_1">EDIT KOLEKTIF</button>
        </th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_ruang_2">EDIT KOLEKTIF</button>
        </th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block"  data-toggle="modal" data-target="#edit_pendidikan">EDIT KOLEKTIF</button>
        </th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_gapok">EDIT KOLEKTIF</button>
        </th>        
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_koreksi_gaji">EDIT KOLEKTIF</button>
        </th>
        <th></th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_koreksi_bobot">EDIT KOLEKTIF</button>
        </th>
        <th></th>
        <th></th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_masa_kerja_bobot">EDIT KOLEKTIF</button>
        </th>
        <th></th>
        <th></th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_pendidikan_nilai">EDIT KOLEKTIF</button>
        </th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_pendidikan_bobot">EDIT KOLEKTIF</button>
        </th>
        <th></th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_diklat_nilai">EDIT KOLEKTIF</button>
        </th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_diklat_bobot">EDIT KOLEKTIF</button>
        </th>
        <th></th>
        <th></th>
        <th></th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_resiko_bobot">EDIT KOLEKTIF</button>
        </th>
        <th></th>
        <th></th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_darurat_bobot">EDIT KOLEKTIF</button>
        </th>
        <th></th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_jabatan_nilai">EDIT KOLEKTIF</button>
        </th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_jabatan_bobot">EDIT KOLEKTIF</button>
        </th>
        <th></th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_panitia_nilai">EDIT KOLEKTIF</button>
        </th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_panitia_bobot">EDIT KOLEKTIF</button>
        </th>
        <th></th>
        <th></th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_performa_nilai">EDIT KOLEKTIF</button>
        </th>
        <th style="padding: 3px;">
          <button class="btn btn-mini btn-primary btn-block" data-toggle="modal" data-target="#edit_performa_bobot">EDIT KOLEKTIF</button>
        </th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($histori as $his)
      <tr>
        <td class="min">{{ strtoupper($his->tanggal) }}</td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <select name="id_tenaga_bagian" onchange="this.form.submit()">
              <option value=""></option>
              @foreach($bagian as $bag)
              <option value="{{ $bag->id }}" {{ $his->id_tenaga_bagian == $bag->id? 'selected':null }}>{{ strtoupper($bag->bagian) }}</option>
              @endforeach
            </select>
          </form>
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <select name="id_status" onchange="this.form.submit()">
              <option value=""></option>
              @foreach($status as $stat)
              <option value="{{ $stat->id }}" {{ $his->id_status === $stat->id? 'selected':null }}>{{ strtoupper($stat->status) }}</option>
              @endforeach
            </select>
          </form>
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <select name="id_ruang" onchange="this.form.submit()">
              <option value=""></option>
              @foreach($ruang as $run1)
              <option value="{{ $run1->id }}" {{ $his->id_ruang == $run1->id? 'selected':null }}>{{ strtoupper($run1->ruang) }}</option>
              @endforeach
            </select>
          </form>
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <select name="id_ruang_1" onchange="this.form.submit()">
              <option value=""></option>
              @foreach($ruang as $run2)
              <option value="{{ $run2->id }}" {{ $his->id_ruang_1 == $run2->id? 'selected':null }}>{{ strtoupper($run2->ruang) }}</option>
              @endforeach
            </select>
          </form>
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <select name="id_ruang_2" onchange="this.form.submit()">
              <option value=""></option>
              @foreach($ruang as $run3)
              <option value="{{ $run3->id }}" {{ $his->id_ruang_2 == $run3->id? 'selected':null }}>{{ strtoupper($run3->ruang) }}</option>
              @endforeach
            </select>
          </form>
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <div class="input-prepend">          
              <input type="text" name="pendidikan" value="{{ $his->pendidikan }}">
              <button class="btn btn-warning" title="Simpan" type="submit">
                <i class="fa fa-save"></i>
              </button>
            </div>
          </form>
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <div class="input-prepend">          
              <span class="add-on">Rp.</span>
              <input type="text" name="gapok" value="{{ $his->gapok }}" style="width: 100px;">
              <button class="btn btn-warning" title="Simpan" type="submit">
                <i class="fa fa-save"></i>
              </button>
            </div>
          </form>
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <div class="input-prepend">          
              <span class="add-on">Rp.</span>
              <input type="text" name="koreksi" value="{{ $his->koreksi }}" style="width: 100px;">
              <button class="btn btn-warning" title="Simpan" type="submit">
                <i class="fa fa-save"></i>
              </button>
            </div>
          </form>
        </td>
        <td>
          <input type="text" value="{{ $his->indeks_dasar }}" readonly style="width: 40px;">
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <div class="input-prepend">
              <input type="number" name="dasar_bobot" value="{{ $his->dasar_bobot }}" style="width: 40px;">
              <button class="btn btn-warning" title="Simpan" type="submit">
                <i class="fa fa-save"></i>
              </button>
            </div>
          </form>
        </td>
        <td>
          <input type="text" value="{{ $his->skor_indek }}" readonly style="width: 40px;">
        </td>
        <td>
          <input type="text" value="{{ $his->masa_kerja }}" readonly style="width: 40px;">
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <div class="input-prepend">
              <input type="number" name="masa_kerja_bobot" value="{{ $his->masa_kerja_bobot }}" style="width: 40px;">
              <button class="btn btn-warning" title="Simpan" type="submit">
                <i class="fa fa-save"></i>
              </button>
            </div>
          </form>
        </td>
        <td>
          <input type="text" value="{{ $his->indeks_masa_kerja }}" readonly style="width: 40px;">
        </td>
        <td>
          <input type="text" value="{{ $his->skor_dasar }}" readonly style="width: 40px;">
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <div class="input-prepend">
              <input type="number" name="pend_nilai" value="{{ $his->pend_nilai }}" style="width: 40px;">
              <button class="btn btn-warning" title="Simpan" type="submit">
                <i class="fa fa-save"></i>
              </button>
            </div>
          </form>
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <div class="input-prepend">
              <input type="number" name="pend_bobot" value="{{ $his->pend_bobot }}" style="width: 40px;">
              <button class="btn btn-warning" title="Simpan" type="submit">
                <i class="fa fa-save"></i>
              </button>
            </div>
          </form>
        </td>
        <td>
          <input type="text" value="{{ $his->skor_pend }}" readonly style="width: 40px;">
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <div class="input-prepend">
              <input type="number" name="diklat_nilai" value="{{ $his->diklat_nilai }}" style="width: 40px;">
              <button class="btn btn-warning" title="Simpan" type="submit">
                <i class="fa fa-save"></i>
              </button>
            </div>
          </form>
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <div class="input-prepend">
              <input type="number" name="diklat_bobot" value="{{ $his->diklat_bobot }}" style="width: 40px;">
              <button class="btn btn-warning" title="Simpan" type="submit">
                <i class="fa fa-save"></i>
              </button>
            </div>
          </form>
        </td>
        <td>
          <input type="text" value="{{ $his->skor_diklat }}" readonly style="width: 40px;">
        </td>
        <td>
          <input type="text" value="{{ $his->indeks_komp }}" readonly style="width: 40px;">
        </td>
        <td>
          <input type="text" value="{{ $his->resiko_nilai }}" readonly style="width: 40px;">
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <div class="input-prepend">
              <input type="number" name="resiko_bobot" value="{{ $his->resiko_bobot }}" style="width: 40px;">
              <button class="btn btn-warning" title="Simpan" type="submit">
                <i class="fa fa-save"></i>
              </button>
            </div>
          </form>
        </td>
        <td>
          <input type="text" value="{{ $his->indeks_resiko }}" readonly style="width: 40px;">
        </td>
        <td>
          <input type="text" value="{{ $his->gawat_nilai }}" readonly style="width: 40px;">
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <div class="input-prepend">
              <input type="number" name="gawat_bobot" value="{{ $his->gawat_bobot }}" style="width: 40px;">
              <button class="btn btn-warning" title="Simpan" type="submit">
                <i class="fa fa-save"></i>
              </button>
            </div>
          </form>
        </td>
        <td>
          <input type="text" value="{{ $his->indeks_kegawat }}" readonly style="width: 40px;">
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <div class="input-prepend">
              <input type="number" name="jab_nilai" value="{{ $his->jab_nilai }}" style="width: 40px;">
              <button class="btn btn-warning" title="Simpan" type="submit">
                <i class="fa fa-save"></i>
              </button>
            </div>
          </form>
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <div class="input-prepend">
              <input type="number" name="jab_bobot" value="{{ $his->jab_bobot }}" style="width: 40px;">
              <button class="btn btn-warning" title="Simpan" type="submit">
                <i class="fa fa-save"></i>
              </button>
            </div>
          </form>
        </td>
        <td>
          <input type="text" value="{{ $his->skor_jab }}" readonly style="width: 40px;">
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <div class="input-prepend">
              <input type="number" name="panitia_nilai" value="{{ $his->panitia_nilai }}" style="width: 40px;">
              <button class="btn btn-warning" title="Simpan" type="submit">
                <i class="fa fa-save"></i>
              </button>
            </div>
          </form>
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <div class="input-prepend">
              <input type="number" name="panitia_bobot" value="{{ $his->panitia_bobot }}" style="width: 40px;">
              <button class="btn btn-warning" title="Simpan" type="submit">
                <i class="fa fa-save"></i>
              </button>
            </div>
          </form>
        </td>
        <td>
          <input type="text" value="{{ $his->skor_pan }}" readonly style="width: 40px;">
        </td>
        <td>
          <input type="text" value="{{ $his->indeks_jabatan }}" readonly style="width: 40px;">
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <div class="input-prepend">
              <input type="number" name="perform_nilai" value="{{ $his->perform_nilai }}" style="width: 40px;">
              <button class="btn btn-warning" title="Simpan" type="submit">
                <i class="fa fa-save"></i>
              </button>
            </div>
          </form>
        </td>
        <td>
          <form method="POST" action="{{ route('karyawan_histori_all_update') }}" style="margin: 0;">
          @csrf
            <input type="hidden" name="id" value="{{ $his->id }}">
            <div class="input-prepend">
              <input type="number" name="perform_bobot" value="{{ $his->perform_bobot }}" style="width: 40px;">
              <button class="btn btn-warning" title="Simpan" type="submit">
                <i class="fa fa-save"></i>
              </button>
            </div>
          </form>
        </td>
        <td>
          <input type="text" value="{{ $his->indeks_perform }}" readonly style="width: 40px;">
        </td>
        <td>
          <input type="text" value="{{ $his->skore }}" readonly style="width: 40px;">
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>  
</div>

<div class="modal hide fade" id="edit_bagian">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Bagian</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_bagian" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Bagian</label>
        <div class="controls span7">
          <select class="form-control" name="id_tenaga_bagian" required size="10">
            @foreach($bagian as $bag)
            <option value="{{ $bag->id }}">{{ strtoupper($bag->bagian) }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_bagian" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_status">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Status</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_status" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Status</label>
        <div class="controls span7">
          <select class="form-control" name="id_status" required size="4">
            @foreach($status as $stat)
            <option value="{{ $stat->id }}">{{ $stat->status }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_status" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_ruang">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Ruang Utama</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_ruang" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Ruang Utama</label>
        <div class="controls span7">
          <select class="form-control" name="id_ruang" required size="10">
            @foreach($ruang as $run)
            <option value="{{ $run->id }}">{{ $run->ruang }}</option>
            @endforeach
          </select>
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

<div class="modal hide fade" id="edit_ruang_1">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Ruang Tambahan 1</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_ruang_1" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Ruang Tambahan 1</label>
        <div class="controls span7">
          <select class="form-control" name="id_ruang_1" required size="10">
            @foreach($ruang as $run)
            <option value="{{ $run->id }}">{{ $run->ruang }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_ruang_1" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_ruang_2">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Ruang Tambahan 2</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_ruang_2" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Ruang Tambahan 1</label>
        <div class="controls span7">
          <select class="form-control" name="id_ruang_2" required size="10">
            @foreach($ruang as $run)
            <option value="{{ $run->id }}">{{ $run->ruang }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_ruang_2" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_pendidikan">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Pendidikan</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_pendidikan" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Pendidikan</label>
        <div class="controls span7">
          <input type="text" class="form-control" name="pendidikan">          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_pendidikan" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_gapok">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Gaji Pokok</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_gapok" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Gaji Pokok Rp.</label>
        <div class="controls span7">
          <input type="number" class="form-control" name="gapok">          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_gapok" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_koreksi_gaji">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Koreksi Gaji</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_koreksi_gaji" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Koreksi Gaji Rp.</label>
        <div class="controls span7">
          <input type="number" class="form-control" name="koreksi">          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_koreksi_gaji" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_koreksi_bobot">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Bobot Koreksi</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_koreksi_bobot" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Bobot Koreksi</label>
        <div class="controls span2">
          <input type="number" class="form-control" name="dasar_bobot">          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_koreksi_bobot" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_masa_kerja_bobot">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Bobot Koreksi</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_masa_kerja_bobot" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Bobot Masa Kerja</label>
        <div class="controls span2">
          <input type="number" class="form-control" name="masa_kerja_bobot">          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_masa_kerja_bobot" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_pendidikan_nilai">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Nilai Pendidikan</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_pendidikan_nilai" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Nilai Pendidikan</label>
        <div class="controls span2">
          <input type="number" class="form-control" name="pend_nilai">          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_pendidikan_nilai" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_pendidikan_bobot">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Bobot Pendidikan</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_pendidikan_bobot" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Bobot Pendidikan</label>
        <div class="controls span2">
          <input type="number" class="form-control" name="pend_bobot">          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_pendidikan_bobot" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_diklat_nilai">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Nilai Diklat</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_diklat_nilai" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Nilai Diklat</label>
        <div class="controls span2">
          <input type="number" class="form-control" name="diklat_nilai">          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_diklat_nilai" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_diklat_bobot">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Bobot Diklat</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_diklat_bobot" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Bobot Diklat</label>
        <div class="controls span2">
          <input type="number" class="form-control" name="diklat_bobot">          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_diklat_bobot" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_resiko_bobot">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Bobot Resiko</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_resiko_bobot" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Bobot Resiko</label>
        <div class="controls span2">
          <input type="number" class="form-control" name="resiko_bobot">          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_resiko_bobot" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_darurat_bobot">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Bobot Kegawat Daruratan</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_gawat_bobot" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Bobot Gawat Darurat</label>
        <div class="controls span2">
          <input type="number" class="form-control" name="gawat_bobot">          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_gawat_bobot" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_jabatan_nilai">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Nilai Jabatan</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_jabatan_nilai" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Nilai Jabatan</label>
        <div class="controls span2">
          <input type="number" class="form-control" name="jab_nilai">          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_jabatan_nilai" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_jabatan_bobot">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Bobot Jabatan</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_jabatan_bobot" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Bobot Jabatan</label>
        <div class="controls span2">
          <input type="number" class="form-control" name="jab_bobot">          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_jabatan_bobot" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_panitia_nilai">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Nilai Kepanitiaan</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_panitia_nilai" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Nilai Kepanitiaan</label>
        <div class="controls span2">
          <input type="number" class="form-control" name="panitia_nilai">          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_panitia_nilai" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_panitia_bobot">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Bobot Kepanitiaan</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_panitia_bobot" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Bobot Kepanitiaan</label>
        <div class="controls span2">
          <input type="number" class="form-control" name="panitia_bobot">          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_panitia_bobot" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_performa_nilai">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Nilai Performance</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_performa_nilai" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Nilai Performance</label>
        <div class="controls span2">
          <input type="number" class="form-control" name="perform_nilai">          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_performa_nilai" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="edit_performa_bobot">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Edit Bobot Performance</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_performa_bobot" method="POST" action="{{ route('karyawan_histori_all_kolektif') }}">
    @csrf
      <input type="hidden" name="id_users" value="{{ $id_karyawan }}">

      <div class="control-group">
        <label class="control-label span3">Tanggal Awal</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>
        </div>
      </div>
      
      <div class="control-group">
        <label class="control-label span3">Tanggal Akhir</label>
        <div class="controls span7">
          <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Bobot Performance</label>
        <div class="controls span2">
          <input type="number" class="form-control" name="perform_bobot">          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_edit_performa_bobot" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>
@endif
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {   
      $('.select2').select2();

      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.21*box.clientHeight);

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