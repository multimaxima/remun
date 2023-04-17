@extends('layouts.cetak')
@section('title','Remunerasi')

@section('content')
  <center>
    <span style="font-weight: bold; font-size: 16px; margin-bottom: 20px;">
      <center>
          <label style="font-size: 20px; font-weight: bold;">
            REMUNERASI PASIEN {{ strtoupper($remun->jenis) }}            
            TANGGAL
            {{ strtoupper($remun->tgl_awal) }} - {{ strtoupper($remun->tgl_akhir) }}
          </label>
        </center>
    </span>
  </center>

  <table width="100%" class="table table-bordered" style="font-size: 12px;">
    <thead>
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
        <td style="text-align: right; padding: 0 5px;"></td>
        <td style="text-align: right; padding: 0 5px;"></td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->direksi,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->staf,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->admin,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->indeks,2) }}</td>
        <td style="text-align: right; padding: 0 5px;">{{ number_format($remun->medis_perawat,2) }}</td>
      </tr>
      <tr>
        <td style="padding: 0 5px; font-weight: bold;">KONVERSI</td>
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

  <table width="100%" id="tabel" class="table table-bordered" style="font-size: 12px; margin-top: 10px;">
    <thead>
      <tr>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 0 10px;">NAMA KARYAWAN</th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 0 10px;">RUANG</th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 0 10px;">BAGIAN</th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 0 10px;">STATUS</th>        
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 0 10px;">SCORE</th>
        <th colspan="3" style="text-align: center; vertical-align: middle; padding: 0 10px;">POS REMUN</th>
        <th colspan="2" style="text-align: center; vertical-align: middle; padding: 0 10px;">DIREKSI</th>
        <th colspan="2" style="text-align: center; vertical-align: middle; padding: 0 10px;">STAF DIREKSI</th>
        <th colspan="2" style="text-align: center; vertical-align: middle; padding: 0 10px;">ADMINISTRASI</th>
        <th colspan="2" style="text-align: center; vertical-align: middle; padding: 0 10px;">TOTAL INDEK</th>
        <th colspan="6" style="text-align: center; vertical-align: middle; padding: 0 10px;">MEDIS/PERAWAT SETARA</th>
        <th colspan="2" style="text-align: center; vertical-align: middle; padding: 0 10px;">JASA PELAYANAN</th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 0 10px;">PAJAK</th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 0 10px;">NOMINAL PAJAK</th>
        <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 0 10px;">JP DITERIMA</th>
      </tr>
      <tr>
        <th style="text-align: center; padding: 0 10px; vertical-align: middle;" rowspan="2">PERHITUNGAN</th>
        <th style="text-align: center; padding: 0 10px;" colspan="2">CONVERSI</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px; vertical-align: middle;">PERHITUNGAN</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px; vertical-align: middle;">CONVERSI</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px; vertical-align: middle;">PERHITUNGAN</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px; vertical-align: middle;">CONVERSI</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px; vertical-align: middle;">PERHITUNGAN</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px; vertical-align: middle;">CONVERSI</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px; vertical-align: middle;">PERHITUNGAN</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px; vertical-align: middle;">CONVERSI</th>
        <th colspan="3" style="text-align: center; padding: 0 10px; vertical-align: middle;">PERHITUNGAN</th>
        <th colspan="3" style="text-align: center; padding: 0 10px; vertical-align: middle;">CONVERSI</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px; vertical-align: middle;">PERHITUNGAN</th>
        <th rowspan="2" style="text-align: center; padding: 0 10px; vertical-align: middle;">CONVERSI</th>
      </tr>
      <tr>
        <th style="text-align: center; padding: 0 10px;">TPP</th>
        <th style="text-align: center; padding: 0 10px;">INDEK</th>
        <th style="text-align: center; padding: 0 10px;">JASA</th>
        <th style="text-align: center; padding: 0 10px;">TITIPAN</th>
        <th style="text-align: center; padding: 0 10px;">JUMLAH</th>
        <th style="text-align: center; padding: 0 10px;">JASA</th>
        <th style="text-align: center; padding: 0 10px;">TITIPAN</th>
        <th style="text-align: center; padding: 0 10px;">JUMLAH</th>
      </tr>
    </thead>
    <tbody>
    @foreach($rincian as $rinc)
      <tr>      
        <td class="min" style="padding: 0 5px;">{{ $rinc->nama }}</td>
        <td class="min" style="padding: 0 5px;">{{ strtoupper($rinc->ruang) }}</td>
        <td class="min" style="padding: 0 5px;">{{ strtoupper($rinc->bagian) }}</td>
        <td class="min" style="padding: 0 5px;">{{ $rinc->status }}</td>        
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->score,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->pos_remun,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->tpp,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->r_indek,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->direksi,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->r_direksi,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->staf_direksi,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->r_staf_direksi,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->administrasi,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->r_administrasi,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->total_indek,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->r_total_indek,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->medis,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->titipan,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->jumlah,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->r_medis,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->titipan,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->r_jumlah,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->jasa_pelayanan,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->r_jasa_pelayanan,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->pajak,2) }} %</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->nominal_pajak,2) }}</td>
        <td class="min" style="text-align: right; padding: 0 5px;">{{ number_format($rinc->sisa,2) }}</td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <th colspan="5" style="text-align: center;">JUMLAH</th>
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
@endsection