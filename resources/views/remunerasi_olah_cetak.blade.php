@extends('layouts.cetak')
@section('title','Remunerasi')

@section('content')
  <center>
    <span style="font-weight: bold; font-size: 16px; margin-bottom: 20px;">
      <center>
          <label style="font-size: 20px; font-weight: bold;">
            REMUNERASI PASIEN
            @if($remun->id_bpjs)
              JKN
            @else
              UMUM
            @endif
            TANGGAL
            {{ strtoupper($remun->tgl_awal) }} - {{ strtoupper($remun->tgl_akhir) }}
          </label>
        </center>
    </span>
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

  <table width="100%" id="tabel" class="table table-bordered" style="font-size: 12px; margin-top: 10px;">
    <thead>
      <tr>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">NAMA KARYAWAN</th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">RUANG</th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">STATUS</th>        
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">SCORE</th>
        <th colspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">POS REMUN</th>
        <th colspan="2" style="text-align: center; vertical-align: middle; padding: 15px;">DIREKSI</th>
        <th colspan="2" style="text-align: center; vertical-align: middle; padding: 15px;">PENGEMBALIAN LANGSUNG STAF DIREKSI</th>
        <th colspan="2" style="text-align: center; vertical-align: middle; padding: 15px;">KELOMPOK ADMINISTRASI</th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">PENYESUAIAN</th>
        <th colspan="2" style="text-align: center; vertical-align: middle; padding: 15px;">TOTAL PENDAPATAN INDEK</th>
        <th colspan="2" style="text-align: center; vertical-align: middle; padding: 15px;">JASA PELAYANAN MEDIS/PERAWAT SETARA</th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">TAMBAHAN JASA</th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 15px;">TOTAL JASA</th>
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
        <td class="min">{{ $rinc->nama }}</td>
        <td class="min">{{ $rinc->ruang }}</td>
        <td class="min">{{ $rinc->status }}</td>        
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
        <td class="min" align="right">{{ number_format($rinc->alokasi_apotik,2) }}</td>
        <td class="min" align="right">{{ number_format($rinc->total_jasa,2) }}</td>
        <td class="min" align="right">{{ number_format($rinc->jasa_pelayanan,2) }}</td>
        <td class="min" align="right">{{ number_format($rinc->r_jasa_pelayanan,2) }}</td>
        <td class="min" align="right">{{ number_format($rinc->pajak,2) }} %</td>
        <td class="min" align="right">{{ number_format($rinc->nominal_pajak,2) }}</td>
        <td class="min" align="right">{{ number_format($rinc->sisa,2) }}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <th colspan="4" style="text-align: center;">JUMLAH</th>
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
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->alokasi_apotik,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->total_jasa,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->jasa_pelayanan + $remun->penyesuaian,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->r_jasa_pelayanan,2) }}</th>
      <th style="text-align: right; padding-right: 2px;"></th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->nominal_pajak,2) }}</th>
      <th style="text-align: right; padding-right: 2px;">{{ number_format($jumlah->sisa,2) }}</th>
    </tfoot>
  </table>    
@endsection