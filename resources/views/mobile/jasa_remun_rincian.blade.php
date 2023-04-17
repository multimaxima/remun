@extends('mobile.layouts.content')

@section('bawah')
  <li><a href="{{ route('jasa_remun') }}"><i class="fa fa-heart"></i>Remunerasi</a></li>
  <li><a href="{{ route('informasi_software') }}"><i class="fa fa-info"></i>Informasi</a></li>
@endsection

@section('content')
<div style="margin-top: 8vh; padding-bottom: 10vh;">
  <div class="row isi">
    <div class="container-fluid">
      <div class="card" style="margin-top: 1vh;">
        <div class="card-body" style="font-size: 3vw;">
          <table width="100%" style="font-size: 12px; line-height: 15px;">
            <tr>
              <td colspan="3" style="padding-bottom: 1vh;">
                <center>
                @if($detil->foto)
                  <img src="/{{ $detil->foto }}" width="25%">
                @else
                  <img src="/images/noimage.jpg" width="25%">
                @endif
                </center>
              </td>
            </tr>
            <tr>              
              <td width="25%" style="vertical-align: top;">Remunerasi</td>
              <td width="3%" style="vertical-align: top;">:</td>
              <td>{{ strtoupper($detil->tgl_awal) }} - {{ strtoupper($detil->tgl_akhir) }}</td>
            </tr>          
            <tr>
              <td style="vertical-align: top;">Nama</td>
              <td style="vertical-align: top;">:</td>
              <td style="font-weight: bold; font-size: 14px;">{{ $detil->nama }}</td>
            </tr>
            <tr>
              <td>Bagian</td>
              <td>:</td>
              <td>{{ strtoupper($detil->bagian) }}</td>
            </tr>          
            <tr>
              <td>Ruang</td>
              <td>:</td>
              <td>{{ strtoupper($detil->ruang) }}</td>
            </tr>
            <tr>
              <td>Pajak</td>
              <td>:</td>
              <td>{{ number_format($detil->pajak,2) }} %</td>
            </tr>
            <tr>
              <td>Score</td>
              <td>:</td>
              <td>{{ number_format($detil->skore,2) }}</td>
            </tr>              
            <tr>
              <td>Masa Kerja</td>
              <td>:</td>
              <td>{{ $detil->masa_kerja }}</td>
            </tr>              
            <tr>
              <td>Gaji Pokok</td>
              <td>:</td>
              <td>Rp. {{ number_format($detil->gapok,0) }}</td>
            </tr>
            <tr>
              <td>Koreksi Gaji</td>
              <td>:</td>
              <td>Rp. {{ number_format($detil->koreksi,0) }}</td>
            </tr>
            <tr>
              <td colspan="3">
                <a href="{{ route('jasa_remun') }}" class="btn btn-success btn-sm w-100">
                  KEMBALI
                </a>
              </td>
            </tr>
          </table>
        </div>
      </div>

      <div class="card" style="margin-top: 1vh;">
        <div class="card-body" style="font-size: 3vw;">
          <table width="100%" style="font-size: 12px; line-height: 15px;">
            <tr>
              <td>TPP</td>
              <td width="5%">Rp.</td>
              <td align="right" width="25%">{{ number_format($detil->tpp,2) }}</td>
              <td width="5%"></td>
              <td align="right" width="25%"></td>
            </tr>
            <tr>
              <td>Indek</td>
              <td>Rp.</td>
              <td align="right">{{ number_format($detil->r_indek,2) }}</td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>JP Direksi</td>                
              <td>Rp.</td>
              <td align="right">{{ number_format($detil->r_direksi,2) }}</td>
              <td></td>
              <td></td>
            </tr>      
            <tr>
              <td>JP Staf Direksi</td>                
              <td>Rp.</td>
              <td align="right">{{ number_format($detil->r_staf_direksi,2) }}</td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>Administrasi</td>                
              <td>Rp.</td>
              <td align="right">{{ number_format($detil->r_administrasi,2) }}</td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>JP Medis/Perawat Setara</td>                
              <td>Rp.</td>
              <td align="right">{{ number_format($detil->r_medis,2) }}</td>
              <td></td>
              <td></td>
            </tr>
            <tr style="background-color: #d6d5d5;">
              <td colspan="3" style="padding: 2px 5px;">Total</td>                
              <td>Rp.</td>
              <td style="padding: 2px 5px;" align="right">{{ number_format($detil->r_jasa_pelayanan,2) }}</td>
            </tr>
            <tr style="background-color: #d6d5d5;">
              <td colspan="3" style="padding: 2px 5px;">Pajak ({{ number_format($detil->pajak,2) }} %)</td>
              <td>Rp.</td>
              <td style="padding: 2px 5px;" align="right">{{ number_format($detil->nominal_pajak,2) }}</td>
            </tr>
            <tr style="background-color: #9a9a9a;">
              <td colspan="3" style="font-weight: bold; font-size: 13px; color: white; padding: 5px 5px;">JASA DITERIMA</td>
              <td style="font-weight: bold; font-size: 13px; color: white;">Rp.</td>
              <td style="font-weight: bold; font-size: 13px; color: white; padding: 5px 5px;" align="right">{{ number_format($detil->sisa,2) }}</td>
            </tr>
          </table>
        </div>
      </div>

      @if($detil->id_tenaga == 1)
      <div class="card" style="margin-top: 1vh;">
        <div class="card-body" style="font-size: 3vw; overflow-x: auto;">
          <table id="tabel" class="table table-hover table-striped" style="font-size: 12px;">
    <thead style="text-transform: uppercase;">
      <tr>
        <th class="min" rowspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">Tanggal</th>
        <th class="min" rowspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">Nama Pasien</th>
        <th rowspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">Jenis</th>
        <th rowspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">Ruang</th>
        <th rowspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle;">Layanan</th>
        <th colspan="2" style="padding: 0 15px; text-align: center; vertical-align: middle; background-color: #fdeded;">Tarif</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle;">DPJP</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle; background-color: #fdeded;">Pengganti</th>
        <th colspan="5" style="padding: 0 15px; text-align: center; vertical-align: middle;">Operator</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle; background-color: #fdeded;">Anastesi</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle;">Pendamping</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle; background-color: #fdeded;">Konsul</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle;">Laborat</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle; background-color: #fdeded;">Pen. Jwb.</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle;">Radiologi</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle; background-color: #fdeded;">RR</th>
        <th colspan="3" style="padding: 0 15px; text-align: center; vertical-align: middle;">Total Medis</th>
      </tr>
      <tr>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REAL</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px;">REAL</th>
        <th style="text-align: center; padding: 0 15px;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px;">REMUN</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REAL</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REMUN</th>
        <th style="text-align: center; padding: 0 15px;">REAL</th>
        <th style="text-align: center; padding: 0 15px;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px;">DITERIMA</th>
        <th style="text-align: center; padding: 0 15px;">TAMBAHAN</th>
        <th style="text-align: center; padding: 0 15px;">REMUN</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REAL</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REMUN</th>
        <th style="text-align: center; padding: 0 15px;">REAL</th>
        <th style="text-align: center; padding: 0 15px;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px;">REMUN</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REAL</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REMUN</th>
        <th style="text-align: center; padding: 0 15px;">REAL</th>
        <th style="text-align: center; padding: 0 15px;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px;">REMUN</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REAL</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REMUN</th>
        <th style="text-align: center; padding: 0 15px;">REAL</th>
        <th style="text-align: center; padding: 0 15px;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px;">REMUN</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REAL</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px; background-color: #fdeded;">REMUN</th>
        <th style="text-align: center; padding: 0 15px;">REAL</th>
        <th style="text-align: center; padding: 0 15px;">CLAIM</th>
        <th style="text-align: center; padding: 0 15px;">REMUN</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rincian as $rinc)
      @if($rinc->jasa_medis > 0)
      <tr>
        <td class="min">{{ strtoupper($rinc->tanggal) }}</td>
        <td class="min">{{ strtoupper($rinc->nama) }}</td>
        <td class="min">{{ strtoupper($rinc->jenis_pasien) }}</td>
        <td class="min">{{ strtoupper($rinc->ruang) }}</td>
        <td class="min">{{ strtoupper($rinc->jasa) }}</td>              
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->tarif_real,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->tarif_claim,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_dpjp,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_dpjp,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_dpjp,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->real_pengganti,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->claim_pengganti,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->jasa_pengganti,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_operator,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_operator,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_operator_diterima,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->min_operator,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_operator,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->real_anastesi,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->claim_anastesi,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->jasa_anastesi,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_pendamping,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_pendamping,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_pendamping,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->real_konsul,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->claim_konsul,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->jasa_konsul,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_laborat,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_laborat,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_laborat,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->real_tanggung,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->claim_tanggung,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->jasa_tanggung,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->real_radiologi,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->claim_radiologi,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_radiologi,2) }}</td>
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->real_rr,2) }}</td>            
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->claim_rr,2) }}</td>            
        <td class="min" style="text-align: right; background-color: #fdeded;">{{ number_format($rinc->jasa_rr,2) }}</td>            
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_real,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_claim,2) }}</td>
        <td class="min" style="text-align: right;">{{ number_format($rinc->jasa_medis,2) }}</td>
      </tr>
      @endif
      @endforeach
    </tbody>
    <tfoot>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->tarif_real,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->tarif_claim,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->real_dpjp,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->claim_dpjp,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->dpjp,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->real_pengganti,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->claim_pengganti,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->pengganti,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->real_operator,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->claim_operator,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->operator_diterima,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->min_operator,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->operator,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->real_anastesi,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->claim_anastesi,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->anastesi,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->real_pendamping,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->claim_pendamping,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->pendamping,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->real_konsul,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->claim_konsul,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->konsul,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->real_laborat,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->claim_laborat,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->laborat,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->real_tanggung,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->claim_tanggung,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->tanggung,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->real_radiologi,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->claim_radiologi,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">{{ number_format($total->radiologi,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->real_rr,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->claim_rr,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px; background-color: #fdeded;">{{ number_format($total->rr,2) }}</th>
      <th style="text-align: right; padding: 0 5px 0 10px;">
        {{ number_format($total->real_dpjp + $total->real_pengganti + $total->real_operator + $total->real_anastesi + $total->real_pendamping + $total->real_konsul + $total->real_laborat + $total->real_tanggung + $total->real_radiologi + $total->real_rr,2) }}
      </th>
      <th style="text-align: right; padding: 0 5px 0 10px;">
        {{ number_format($total->claim_dpjp + $total->claim_pengganti + $total->claim_operator + $total->claim_anastesi + $total->claim_pendamping + $total->claim_konsul + $total->claim_laborat + $total->claim_tanggung + $total->claim_radiologi + $total->claim_rr,2) }}
      </th>
      <th style="text-align: right; padding: 0 5px 0 10px;">
        {{ number_format($detil->r_medis,2) }}
      </th>
    </tfoot>    
  </table>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('#tabel').DataTable( {
        scrollY:        "70vh",
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