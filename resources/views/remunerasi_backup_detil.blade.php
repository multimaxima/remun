@extends('layouts.content')
@section('title','Detil Backup Remunerasi')

@section('content')
<div class="navbar" id="nav_remun">
  <div class="navbar-inner">
    <div class="pull-left" style="display: inline-flex;">
      <form class="form-inline" role="form" method="GET" action="{{ route('remunerasi_backup_detil') }}"  style="margin-top: 5px; margin-bottom: 0;">
      @csrf
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

      <div class="btn-group" style="margin-left: 5px;">        
        <div class="btn-group" style="margin-top: 0;">
          <button type="submit" form="kembali" class="btn btn-primary">KEMBALI</button>          
          <button class="btn btn-primary" data-toggle="dropdown">
            MENU <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">                  
            <li>
              <a href="javascript:;" onclick="document.getElementById('cetak').submit();" title="Cetak">Cetak</a>
            </li>
            <li>
              <a href="{{ route('remunerasi_backup_export',Crypt::encrypt($remun->id)) }}" title="Export Excel">Export</a>
            </li>
          </ul>
        </div>

        <form hidden method="GET" id="jasa" action="{{ route('remunerasi_jasa') }}">
        @csrf
          <input type="text" name="id" value="{{ $remun->id }}">
        </form>

        <form hidden method="GET" action="{{ route('remunerasi_backup') }}" id="kembali">
        @csrf
        </form>

        <form hidden method="GET" id="cetak" action="{{ route('remunerasi_backup_cetak') }}" target="_blank">
        @csrf
          <input type="hidden" name="id" value="{{ $remun->id }}">
          <input type="hidden" name="jenis" value="{{ $jenis }}">
          <input type="hidden" name="id_status" value="{{ $id_status }}">
          <input type="hidden" name="id_ruang" value="{{ $id_ruang }}">
          <input type="hidden" name="id_bagian" value="{{ $id_bagian }}">
        </form>        
      </div>        
    </div>
    <label style="margin-top: 10px;" class="pull-right">Waktu Perhitungan : {{ $remun->waktu }}</label>
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
      <th style="text-align: center; padding: 0 5px;">JP</th>
      <th style="text-align: center; padding: 0 5px;">PENGHASIL</th>
      <th style="text-align: center; padding: 0 5px;">NON PENGHASIL</th>
      <th style="text-align: center; padding: 0 5px;">POS REMUN</th>
      <th style="text-align: center; padding: 0 5px;">TPP</th>
      <th style="text-align: center; padding: 0 5px;">INDEK</th>
      <th style="text-align: center; padding: 0 5px;">DIREKSI</th>
      <th style="text-align: center; padding: 0 5px;">STAF DIREKSI</th>
      <th style="text-align: center; padding: 0 5px;">ADMIN</th>
      <th style="text-align: center; padding: 0 5px;">TOTAL INDEK</th>
      <th style="text-align: center; padding: 0 5px;">MEDIS/PERAWAT</th>
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

<div class="content">
  <table id="tabel" class="table table-hover table-striped" style="font-size: 12px;">
    <thead>
      <tr>
        <th hidden rowspan="3" style="text-align: center; vertical-align: middle; padding: 0 15px;">ID</th>
        <th rowspan="3"></th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 0 15px;">NAMA KARYAWAN</th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 0 15px;">RUANG</th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 0 15px;">STATUS</th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 0 15px;">SCORE</th>
        <th colspan="3" style="text-align: center; vertical-align: middle; padding: 0 15px;">POS REMUN</th>
        <th colspan="2" style="text-align: center; vertical-align: middle; padding: 0 15px;">DIREKSI</th>
        <th colspan="2" style="text-align: center; vertical-align: middle; padding: 0 15px;">STAF DIREKSI</th>
        <th colspan="2" style="text-align: center; vertical-align: middle; padding: 0 15px;">ADMINISTRASI</th>
        <th colspan="2" style="text-align: center; vertical-align: middle; padding: 0 15px;">TOTAL INDEK</th>
        <th colspan="2" style="text-align: center; vertical-align: middle; padding: 0 15px;">MEDIS/PERAWAT SETARA</th>
        <th colspan="2" style="text-align: center; vertical-align: middle; padding: 0 15px;">JASA PELAYANAN</th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 0 15px;">PAJAK</th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 0 15px;">NOMINAL PAJAK</th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 0 15px;">JP DITERIMA</th>
      </tr>
      <tr>
        <th style="text-align: center; padding: 0 15px; vertical-align: middle;" rowspan="2">PERHITUNGAN</th>
        <th style="text-align: center; padding: 0 15px;" colspan="2">CONVERSI</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px; vertical-align: middle;">PERHITUNGAN</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px; vertical-align: middle;">CONVERSI</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px; vertical-align: middle;">PERHITUNGAN</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px; vertical-align: middle;">CONVERSI</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px; vertical-align: middle;">PERHITUNGAN</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px; vertical-align: middle;">CONVERSI</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px; vertical-align: middle;">PERHITUNGAN</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px; vertical-align: middle;">CONVERSI</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px; vertical-align: middle;">PERHITUNGAN</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px; vertical-align: middle;">CONVERSI</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px; vertical-align: middle;">PERHITUNGAN</th>
        <th rowspan="2" style="text-align: center; padding: 0 15px; vertical-align: middle;">CONVERSI</th>
      </tr>
      <tr>
        <th style="text-align: center; padding: 0 15px;">TPP</th>
        <th style="text-align: center; padding: 0 15px;">INDEK</th>        
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
        <td class="min">{{ $rinc->ruang }}</td>
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
        <td class="min" style="text-align: right;">{{ number_format($rinc->r_medis,2) }}</td>
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
      <th colspan="2" style="text-align: center;">JUMLAH</th>
      <th colspan="3"></th>
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
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_medis,2) }}</th>
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
      if(window.screen.height < 900){
        $('#tabel').DataTable( {     
          scrollY:        "32vh",
          scrollX:        true,
          scrollCollapse: true,
          paging:         false,
          searching:      false,
          stateSave:      true,
          sort:           false,
          info:           false,
          fixedColumns:   {
              leftColumns: 4
          },
        });
      } else {
        $('#tabel').DataTable( {     
          scrollY:        "45vh",
          scrollX:        true,
          scrollCollapse: true,
          paging:         false,
          searching:      false,
          stateSave:      true,
          sort:           false,
          info:           false,
          fixedColumns:   {
              leftColumns: 4
          },
        });
      }      
    });
  </script> 
@endsection