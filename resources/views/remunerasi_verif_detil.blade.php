@extends('layouts.content')
@section('title','Verifikasi Remunerasi')

@section('judul')
  <div class="float-right">
    @if($remun)
      @if($remun->stat == 4 && Auth::user()->id_akses == 7)
      <a href="{{ route('remunerasi_verifikasi',Crypt::encrypt($remun->id)) }}" class="btn btn-primary" onclick="return confirm('Verifikasi data perhitungan Remunerasi ?')" title="Verifikasi Remunerasi">
        <i class="fa fa-check"></i>
      </a>

      <a href="{{ route('remunerasi_tolak',Crypt::encrypt($remun->id)) }}" class="btn btn-primary" onclick="return confirm('Kembalikan ke Pengolah Data ?')" title="Kembalikan ke Pengolah Data">
        <i class="fa fa-times"></i>
      </a>
      @endif

      <div class="btn-group" role="group">
        <button id="btnGroupVerticalDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Cetak">
          <i class="fa fa-print"></i>
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
          <button class="dropdown-item" type="submit" form="cetak">Remunerasi</button>
        </div>
      </div>

      <div class="btn-group" role="group">
        <button id="btnGroupVerticalDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Export">
          <i class="fa fa-file-excel-o"></i>
        </button>
        <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
          <a href="{{ route('remunerasi_export',Crypt::encrypt($remun->id)) }}" class="dropdown-item">
            Remunerasi
          </a>
        </div>
      </div>

      <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" title="Filter Data">
      <i class="fa fa-filter"></i>

      <form method="GET" id="cetak" action="{{ route('remunerasi_cetak') }}" target="_blank">
      @csrf
        <input type="hidden" name="id" value="{{ $remun->id }}">
      </form>         
    @endif  
  </div>

  <h4 class="page-title"> <i class="dripicons-document-edit"></i> @yield('title')</h4>
@endsection

@section('content')
@if($remun)
@if($id_bagian || $id_ruang || $id_status || $jenis)
<div class="wrapper collapse show" id="collapseExample" style="margin-bottom: 60px;">
@else
<div class="wrapper collapse" id="collapseExample" style="margin-bottom: 60px;">
@endif
  <div class="col-12" style="padding: 0 50px;">
    <div class="card card-body" style="padding: 10px 0;">      
      <form class="form-inline justify-content-center" method="GET" action="{{ route('remunerasi_verif') }}">
      @csrf
        <input type="hidden" name="id" value="{{ Crypt::encrypt($remun->id) }}">
              
        <label>Jenis Karyawan</label>
        <select class="form-control form-control-sm" name="jenis" style="margin: 0 10px;" onchange="this.form.submit();">
          <option value=""></option>
          <option value="1" {{ $jenis == '1'? 'selected' : null }}>Dokter DPJP</option>
          <option value="2" {{ $jenis == '0'? 'selected' : null }}>Penunjang</option>
        </select>

        <label style="margin-left: 30px;">Status</label>
        <select class="form-control form-control-sm" name="id_status" style="margin: 0 10px;" onchange="this.form.submit();">
          <option value=""></option>
          @foreach($status as $stat)
            <option value="{{ $stat->id }}" {{ $stat->id == $id_status? 'selected' : null }}>{{ $stat->status }}</option>
          @endforeach
        </select>

        <label style="margin-left: 30px;">Ruang</label>
        <select class="form-control form-control-sm" name="id_ruang" style="margin: 0 10px;" onchange="this.form.submit();">
          <option value=""></option>
          @foreach($ruang as $rng)
            <option value="{{ $rng->id }}" {{ $rng->id == $id_ruang? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
          @endforeach
        </select>

        <label style="margin-left: 30px;">Bagian</label>
        <select class="form-control form-control-sm" name="id_bagian" style="margin: 0 10px;" onchange="this.form.submit();">
          <option value=""></option>
          @foreach($bagian as $bag)
            <option value="{{ $bag->id }}" {{ $bag->id == $id_bagian? 'selected' : null }}>{{ strtoupper($bag->bagian) }}</option>
          @endforeach
        </select>
      </form>
    </div>
  </div>
</div>

<div class="wrapper" style="margin-top: -50px;">
  <div class="col-12" style="padding: 0 50px;">
    <div class="card m-b-30 konten">
      <div class="card-body">
        <center>
          <label style="font-size: 20px; font-weight: bold;">
            REMUNERASI PASIEN
            @if($remun->id_bpjs)
              BPJS
            @else
              UMUM
            @endif
            TANGGAL
            {{ strtoupper($remun->tgl_awal) }} - {{ strtoupper($remun->tgl_akhir) }}
          </label>
        </center>
        <table width="100%" class="table-bordered" style="font-size: 12px;">
          <thead style="background-color: #90a736; color: white;">
            <th></th>
            <th style="text-align: center; padding: 10px 5px;">JP</th>
            <th style="text-align: center; padding: 10px 5px;">PENGHASIL</th>
            <th style="text-align: center; padding: 10px 5px;">NON PENGHASIL</th>
            <th style="text-align: center; padding: 10px 5px;">POS REMUN</th>
            <th style="text-align: center; padding: 10px 5px;">TPP</th>
            <th style="text-align: center; padding: 10px 5px;">INDEK</th>
            <th style="text-align: center; padding: 10px 5px;">PENYESUAIAN</th>
            <th style="text-align: center; padding: 10px 5px;">DIREKSI</th>
            <th style="text-align: center; padding: 10px 5px;">STAF DIREKSI</th>
            <th style="text-align: center; padding: 10px 5px;">ADMIN</th>
            <th style="text-align: center; padding: 10px 5px;">TOTAL INDEK</th>
            <th style="text-align: center; padding: 10px 5px;">MEDIS/PERAWAT</th>
          </thead>
          <tbody>
            <tr>
              <td style="padding-left: 5px; font-weight: bold;">KALKULASI</td>                
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->jp,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->penghasil,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->nonpenghasil,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->pos_remun,2) }}</td>
              <td style="text-align: right; padding-right: 5px; background-color: #b9b9b9;"></td>
              <td style="text-align: right; padding-right: 5px; background-color: #b9b9b9;"></td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->penyesuaian,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->direksi,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->staf,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->admin,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->indeks,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->medis_perawat,2) }}</td>
            </tr>
            <tr>
              <td style="padding-left: 5px; font-weight: bold;">CONVERSI</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->r_jp,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->r_penghasil,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->r_nonpenghasil,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->r_pos_remun,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->tpp,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->r_indek,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->r_penyesuaian,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->r_direksi,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->r_staf,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->r_admin,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->r_indeks,2) }}</td>
              <td style="text-align: right; padding-right: 5px;">{{ number_format($remun->r_medis_perawat,2) }}</td>
            </tr>
          </tbody>
        </table>
      </div>      
    </div>
  </div>
</div>

<div class="wrapper" style="margin-top: -20px;">
  <div class="col-12" style="padding: 0 50px;">
    <div class="card m-b-30 konten">
      <div class="card-body">
        <table width="175%" id="tabel" class="table table-hover table-striped" style="font-size: 12px;">
          <thead>
            <tr>
              <th hidden rowspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">ID</th>
              <th rowspan="3"></th>
              <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">NAMA KARYAWAN</th>
              <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">RUANG</th>
              <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">STATUS</th>
              <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">PAJAK</th>
              <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">SCORE</th>
              <th colspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">POS REMUN</th>
              <th colspan="2" style="text-align: center; vertical-align: middle; padding: 15px;">DIREKSI</th>
              <th colspan="2" style="text-align: center; vertical-align: middle; padding: 15px;">STAF DIREKSI</th>
              <th colspan="2" style="text-align: center; vertical-align: middle; padding: 15px;">ADMINISTRASI</th>
              <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">PENYESUAIAN</th>
              <th colspan="2" style="text-align: center; vertical-align: middle; padding: 15px;">TOTAL INDEK</th>
              <th colspan="2" style="text-align: center; vertical-align: middle; padding: 15px;">MEDIS/PERAWAT SETARA</th>
              <th colspan="2" style="text-align: center; vertical-align: middle; padding: 15px;">JASA PELAYANAN</th>
              <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">PAJAK</th>
              <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">NOMINAL PAJAK</th>
              <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">JP DITERIMA</th>
            </tr>
            <tr>
              <th style="text-align: center; padding: 5px; vertical-align: middle;" rowspan="2">PERHITUNGAN</th>
              <th style="text-align: center; padding: 5px;" colspan="2">CONVERSI</th>
              <th rowspan="2" style="text-align: center; padding: 5px; vertical-align: middle;">PERHITUNGAN</th>
              <th rowspan="2" style="text-align: center; padding: 5px; vertical-align: middle;">CONVERSI</th>
              <th rowspan="2" style="text-align: center; padding: 5px; vertical-align: middle;">PERHITUNGAN</th>
              <th rowspan="2" style="text-align: center; padding: 5px; vertical-align: middle;">CONVERSI</th>
              <th rowspan="2" style="text-align: center; padding: 5px; vertical-align: middle;">PERHITUNGAN</th>
              <th rowspan="2" style="text-align: center; padding: 5px; vertical-align: middle;">CONVERSI</th>
              <th rowspan="2" style="text-align: center; padding: 5px; vertical-align: middle;">PERHITUNGAN</th>
              <th rowspan="2" style="text-align: center; padding: 5px; vertical-align: middle;">CONVERSI</th>
              <th rowspan="2" style="text-align: center; padding: 5px; vertical-align: middle;">PERHITUNGAN</th>
              <th rowspan="2" style="text-align: center; padding: 5px; vertical-align: middle;">CONVERSI</th>
              <th rowspan="2" style="text-align: center; padding: 5px; vertical-align: middle;">PERHITUNGAN</th>
              <th rowspan="2" style="text-align: center; padding: 5px; vertical-align: middle;">CONVERSI</th>
            </tr>
            <tr>
              <th style="text-align: center; padding: 5px;">TPP</th>
              <th style="text-align: center; padding: 5px;">INDEK</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rincian as $rinc)   
            <tr>
              <td hidden>{{ $rinc->id }}</td>
              <td class="min">
                <a href="{{ route('remunerasi_detil',Crypt::encrypt($rinc->id)) }}" class="btn btn-primary btn-xs" title="Rincian Jasa" target="_blank">
                  <i class="fa fa-list"></i>
                </a>
              </td>              
              <td class="min">{{ $rinc->nama }}</td>                            
              <td class="min">{{ $rinc->ruang }}</td>
              <td class="min">{{ $rinc->status }}</td>
              <td class="min" align="right">{{ number_format($rinc->pajak,2) }} %</td>
              <td class="min" align="right">{{ number_format($rinc->score,2) }}</td>
              <td class="min" align="right">{{ number_format($rinc->pos_remun,2) }}</td>
              <td class="min" align="right">{{ number_format($rinc->tpp,2) }}</td>
              <td class="min" align="right">{{ number_format($rinc->r_indek,2) }}</td>              
              <td class="min" align="right">{{ number_format($rinc->direksi,2) }}</td>
              <td class="min" align="right">{{ number_format($rinc->r_direksi,2) }}</td>
              <td class="min" align="right">{{ number_format($rinc->staf_direksi,2) }}</td>
              <td class="min" align="right">{{ number_format($rinc->r_staf_direksi,2) }}</td>
              <td class="min" align="right">{{ number_format($rinc->administrasi,2) }}</td>
              <td class="min" align="right">{{ number_format($rinc->r_administrasi,2) }}</td>
              <td class="min" align="right">{{ number_format($rinc->r_penyesuaian,2) }}</td>
              <td class="min" align="right">{{ number_format($rinc->total_indek,2) }}</td>
              <td class="min" align="right">{{ number_format($rinc->r_total_indek,2) }}</td>
              <td class="min" align="right">{{ number_format($rinc->medis,2) }}</td>
              <td class="min" align="right">{{ number_format($rinc->r_medis,2) }}</td>
              <td class="min" align="right">{{ number_format($rinc->jasa_pelayanan,2) }}</td>
              <td class="min" align="right">{{ number_format($rinc->r_jasa_pelayanan,2) }}</td>
              <td class="min" align="right">{{ number_format($rinc->pajak,2) }} %</td>
              <td class="min" align="right">{{ number_format($rinc->nominal_pajak,2) }}</td>
              <td class="min" align="right">{{ number_format($rinc->sisa,2) }}</td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <th hidden></th>
            <th colspan="6" style="text-align: center;">JUMLAH</th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->pos_remun,2) }}</th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->tpp,0) }}</th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_indek,2) }}</th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->direksi,2) }}</th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_direksi,2) }}</th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->staf_direksi,2) }}</th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_staf_direksi,2) }}</th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->administrasi,2) }}</th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_administrasi,2) }}</th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_penyesuaian,2) }}</th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->total_indek + $remun->penyesuaian,2) }}</th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_total_indek,2) }}</th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->medis,2) }}</th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_medis,2) }}</th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->jasa_pelayanan + $remun->penyesuaian,2) }}</th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_jasa_pelayanan,2) }}</th>
            <th style="text-align: right; padding-right: 2px;"></th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->nominal_pajak,2) }}</th>
            <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->sisa,2) }}</th>
          </tfoot>
        </table>        
      </div>      
    </div>
  </div>
</div>
@else
<input type="hidden" name="id_remun" id="id_remun" value="NULL">
@endif
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('#tabel').DataTable( {     
        "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": [0,1],
        }],
        "order": [[ 2, "asc" ]],
        scrollY:        "400px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   {
            leftColumns: 4,
            rightColumns: 1
        },
        stateSave: true,
        sort:           false,
      });
    });
  </script> 
@endsection