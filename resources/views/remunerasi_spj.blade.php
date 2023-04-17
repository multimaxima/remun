@extends('layouts.content')
@if(Auth::user()->id_akses == 6)
  @section('title','Olah Data Remunerasi')
@endif

@if(Auth::user()->id_akses == 7)
  @section('title','Verifikasi Data Remunerasi')
@endif

@section('style')
  <style type="text/css">
    .DTFC_LeftBodyLiner { overflow-x: hidden; }
  </style>
@endsection

@section('content')
<div class="navbar" style="margin-bottom:5px;">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12">
        <div style="float: left;">
          <form class="form-inline" method="GET" action="{{ route('remunerasi_olah_spj') }}" style="margin-top: 5px; margin-bottom: 0;">
          @csrf
            <input type="hidden" name="id_remun" value="{{ Crypt::encrypt($remun->id) }}">
              
            <select name="jenis" onchange="this.form.submit();">
              <option value="" style="font-style: italic;">SEMUA JENIS KARYAWAN</option>
              <option value="1" {{ $jenis == '1'? 'selected' : null }}>Dokter DPJP</option>
              <option value="2" {{ $jenis == '0'? 'selected' : null }}>Penunjang</option>
            </select>

            <select name="id_status" onchange="this.form.submit();">
              <option value="" style="font-style: italic;">SEMUA STATUS</option>
              @foreach($status as $stat)
                <option value="{{ $stat->id }}" {{ $stat->id == $id_status? 'selected' : null }}>{{ $stat->status }}</option>
              @endforeach
            </select>

            <select name="id_ruang" onchange="this.form.submit();">
              <option value="" style="font-style: italic;">SEMUA RUANG</option>
              @foreach($ruang as $rng)
                <option value="{{ $rng->id }}" {{ $rng->id == $id_ruang? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
              @endforeach
            </select>

            <select name="id_bagian" onchange="this.form.submit();">
              <option value="" style="font-style: italic;">SEMUA BAGIAN</option>
              @foreach($bagian as $bag)
                <option value="{{ $bag->id }}" {{ $bag->id == $id_bagian? 'selected' : null }}>{{ strtoupper($bag->bagian) }}</option>
              @endforeach
            </select>
          </form>
        </div>

        <form hidden method="GET" action="{{ route('remunerasi_spj_data') }}" id="kembali">
        @csrf
        </form>

        <div class="btn-group" style="margin-left: 5px;">
          <div class="btn-group" style="margin-top: 0;">
            <button type="submit" form="kembali" class="btn btn-primary" title="Kembali">
              KEMBALI
            </button>
            <button class="btn btn-primary" data-toggle="dropdown">
              MENU <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">               
              <li>
                <a class="dropdown-item" href="{{ route('remunerasi_cetak_jaspel',Crypt::encrypt($remun->id)) }}" target="_blank">Cetak SPJ Pembayaran</a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('remunerasi_cetak_kwitansi',Crypt::encrypt($remun->id)) }}" target="_blank">Cetak Kwitansi</a>
              </li>             
              <li class="divider"></li>
              <li>
                <a class="dropdown-item" href="{{ route('remunerasi_export_jaspel',Crypt::encrypt($remun->id)) }}">SPJ Export Pembayaran</a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('remunerasi_kwitansi_export',Crypt::encrypt($remun->id)) }}">Export Kwitansi</a>
              </li>
              <li class="divider"></li>
              <li>
                <a href="{{ route('remunerasi_arsip',Crypt::encrypt($remun->id)) }}" onclick="return confirm('Publikasikan data perhitungan Remunerasi ?')" title="Publikasi">
                  Publikasi
                </a>
              </li>             

              @if(Auth::user()->id_akses == 6 || Auth::user()->id_akses == 7)
              <li class="divider"></li>
              <li>
                <a href="javascript:;" onclick="document.getElementById('cetak').submit();" title="Cetak">Cetak</a>
              </li>
              <li>
                <a href="{{ route('remunerasi_export',Crypt::encrypt($remun->id)) }}" title="Export Excel">Export</a>
              </li>
              @endif

              <form hidden method="GET" id="cetak" action="{{ route('remunerasi_cetak') }}" target="_blank">
              @csrf
                <input type="hidden" name="id" value="{{ $remun->id }}">
                <input type="hidden" name="jenis" value="{{ $jenis }}">
                <input type="hidden" name="id_status" value="{{ $id_status }}">
                <input type="hidden" name="id_ruang" value="{{ $id_ruang }}">
                <input type="hidden" name="id_bagian" value="{{ $id_bagian }}">
              </form>
            </ul>            

            <form hidden method="GET" id="jasa" action="{{ route('remunerasi_jasa') }}">
            @csrf
              <input type="text" name="id" value="{{ $remun->id }}">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div>
    <label style="font-size: 16px; font-weight: bold; text-align: center;">
      REMUNERASI PASIEN {{ strtoupper($remun->jenis) }}
      TANGGAL
      {{ strtoupper($remun->tgl_awal) }} - {{ strtoupper($remun->tgl_akhir) }}
    </label>    
  </div>
  <table width="100%" class="table table-bordered" style="font-size: 12px; margin-bottom: 0;">
    <thead style="background-color: #90a736; color: white;">
      <th></th>
      <th style="padding: 0 5px;">JP</th>
      <th style="padding: 0 5px;">PENGHASIL</th>
      <th style="padding: 0 5px;">NON PENGHASIL</th>
      <th style="padding: 0 5px;">POS REMUN</th>
      <th style="padding: 0 5px;">TPP</th>
      <th style="padding: 0 5px;">INDEK</th>
      <th style="padding: 0 5px;">DIREKSI</th>
      <th style="padding: 0 5px;">STAF DIREKSI</th>
      <th style="padding: 0 5px;">ADMIN</th>
      <th style="padding: 0 5px;">TOTAL INDEK</th>
      <th style="padding: 0 5px;">MEDIS/PERAWAT</th>
    </thead>
    <tbody>
      <tr>
        <td style="padding: 0 5px; font-weight: bold;">KALKULASI</td>                
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->jp,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->penghasil,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->nonpenghasil,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->pos_remun,2) }}</td>
        <td style="text-align: right; padding: 0 5px; background-color: #b9b9b9;"></td>
        <td style="text-align: right; padding: 0 5px; background-color: #b9b9b9;"></td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->direksi,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->staf,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->admin,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->indeks,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->medis_perawat,2) }}</td>
      </tr>
      <tr>
        <td style="padding: 0 5px; font-weight: bold;">CONVERSI</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->r_jp,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->r_penghasil,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->r_nonpenghasil,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->r_pos_remun,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->tpp,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->r_indek,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->r_direksi,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->r_staf,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->r_admin,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->r_indeks,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->r_medis_perawat,2) }}</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="content" id="data">
  <table id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
    <thead>
      <tr>
        <th hidden rowspan="3" style="padding: 0 15px;">ID</th>
        <th rowspan="3"></th>
        <th rowspan="3" style="padding: 0 15px;">NAMA KARYAWAN</th>
        <th rowspan="3" style="padding: 0 15px;">RUANG</th>
        <th rowspan="3" style="padding: 0 15px;">BAGIAN</th>
        <th rowspan="3" style="padding: 0 15px;">STATUS</th>
        <th rowspan="3" style="padding: 0 15px;">SCORE</th>
        <th colspan="3" style="padding: 0 15px;">POS REMUN</th>
        <th colspan="2" style="padding: 0 15px;">DIREKSI</th>
        <th colspan="2" style="padding: 0 15px;">STAF DIREKSI</th>
        <th colspan="2" style="padding: 0 15px;">ADMINISTRASI</th>
        <th colspan="2" style="padding: 0 15px;">TOTAL INDEK</th>
        <th colspan="6" style="padding: 0 15px;">MEDIS/PERAWAT SETARA</th>
        <th colspan="2" style="padding: 0 15px;">JASA PELAYANAN</th>
        <th rowspan="3" style="padding: 0 15px;">PAJAK</th>
        <th rowspan="3" style="padding: 0 15px;">NOMINAL PAJAK</th>
        <th rowspan="3" style="padding: 0 15px;">JP DITERIMA</th>
      </tr>
      <tr>
        <th style="padding: 0 15px;" rowspan="2">PERHITUNGAN</th>
        <th style="padding: 0 15px;" colspan="2">CONVERSI</th>
        <th rowspan="2" style="padding: 0 15px;">PERHITUNGAN</th>
        <th rowspan="2" style="padding: 0 15px;">CONVERSI</th>
        <th rowspan="2" style="padding: 0 15px;">PERHITUNGAN</th>
        <th rowspan="2" style="padding: 0 15px;">CONVERSI</th>
        <th rowspan="2" style="padding: 0 15px;">PERHITUNGAN</th>
        <th rowspan="2" style="padding: 0 15px;">CONVERSI</th>
        <th rowspan="2" style="padding: 0 15px;">PERHITUNGAN</th>
        <th rowspan="2" style="padding: 0 15px;">CONVERSI</th>
        <th colspan="3" style="padding: 0 15px;">PERHITUNGAN</th>
        <th colspan="3" style="padding: 0 15px;">CONVERSI</th>
        <th rowspan="2" style="padding: 0 15px;">PERHITUNGAN</th>
        <th rowspan="2" style="padding: 0 15px;">CONVERSI</th>
      </tr>
      <tr>
        <th style="padding: 0 15px;">TPP</th>
        <th style="padding: 0 15px;">INDEK</th>        
        <th style="padding: 0 15px;">JASA</th>
        <th style="padding: 0 15px;">TITIPAN</th>
        <th style="padding: 0 15px;">JUMLAH</th>
        <th style="padding: 0 15px;">JASA</th>
        <th style="padding: 0 15px;">TITIPAN</th>
        <th style="padding: 0 15px;">JUMLAH</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rincian as $rinc)
      <tr>
        <td hidden>{{ $rinc->id }}</td>
        <td class="min">
          <div class="btn-group">
            <a href="{{ route('remunerasi_detil',Crypt::encrypt($rinc->id)) }}" class="btn btn-info btn-mini" title="Rincian Jasa" target="_blank">
              <i class="icon-list"></i>
            </a>          
          </div>
        </td>
        <td class="min">{{ $rinc->nama }}</td>
        <td class="min">{{ strtoupper($rinc->ruang) }}</td>
        <td class="min">{{ strtoupper($rinc->bagian) }}</td>
        <td class="min">{{ $rinc->status }}</td>              
        <td class="min" style="text-align: right;">{{ number_format($rinc->score,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->pos_remun,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->tpp,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->r_indek,2) }}</td>              
        <td class="min" style="text-align: right;">{{ number_format($rinc->direksi,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->r_direksi,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->staf_direksi,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->r_staf_direksi,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->administrasi,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->r_administrasi,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->total_indek,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->r_total_indek,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->medis,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->titipan,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jumlah,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->r_medis,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->titipan,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->r_jumlah,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_pelayanan,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->r_jasa_pelayanan,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->pajak,2) }} %</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->nominal_pajak,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->sisa,2) }}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <th hidden></th>
      <th colspan="4" style="text-align: center;">JUMLAH</th>
      <th></th>
      <th></th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->pos_remun,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->tpp,0) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_indek,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->direksi,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_direksi,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->staf_direksi,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_staf_direksi,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->administrasi,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_administrasi,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->total_indek,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_total_indek,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->medis,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->titipan,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->jumlah,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_medis,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->titipan,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_jumlah,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->jasa_pelayanan,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_jasa_pelayanan,2) }}</th>
      <th style="text-align: right; padding-right: 2px;"></th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->nominal_pajak,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->sisa,2) }}</th>
    </tfoot>
  </table>                
</div>

<input type="hidden" name="id_remun" id="id_remun" value="{{ $remun->id }}">
@endsection

@section('script')
  <script type="text/javascript">    
    $(document).ready(function() {
      var box = document.querySelector('#data');
      var tinggi = box.clientHeight-(0.39*box.clientHeight);

      $('#tabel').DataTable( {     
        scrollY:        tinggi,
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        searching:      false,
        sort:           false,
        info:           false,
        fixedColumns:   {
          leftColumns: 5
        },
      });
    });
  </script>
@endsection