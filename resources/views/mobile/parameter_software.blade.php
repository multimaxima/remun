@extends('mobile.layouts.content')

@section('bawah')
  <li><a href="{{ route('jasa_remun') }}"><i class="fa fa-heart"></i>Remunerasi</a></li>
  <li><a href="{{ route('informasi_software') }}"><i class="fa fa-info"></i>Informasi</a></li>
@endsection

@section('content')
<div class="page-content-wrapper">
<div class="container">
  <div class="profile-wrapper-area py-3">
    <form class="form-horizontal fprev" method="POST" action="{{ route('parameter_software_simpan') }}">
    @csrf
      <input type="hidden" name="id" value="{{ Crypt::encrypt($a_param->id) }}">

      <div class="card user-data-card">
        <div class="card card-body">
          <div class="mb-3">
            <div class="title mb-2">Menu Update Histori Kary.</div>
            <select name="histori" class="form-control">
              <option value="0" {{ $a_param->histori == '0'? 'selected' : null }}>TIDAK AKTIF</option>
              <option value="1" {{ $a_param->histori == '1'? 'selected' : null }}>AKTIF</option>
            </select>
          </div>

          <div class="mb-3">
            <div class="title mb-2">Dasar Perhitungan Remun</div>
            <select name="dasar_remun" class="form-control">
              <option value="1" {{ $a_param->dasar_remun == '1'? 'selected' : null }}>DATA KARYAWAN TERBARU</option>
              <option value="2" {{ $a_param->dasar_remun == '2'? 'selected' : null }}>HISTORY DATA KARYAWAN</option>
            </select>
          </div>

          <div class="mb-3">
            <div class="title mb-2">Koreksi Gaji Pokok</div>
            <div class="input-group">
              <span class="input-group-text" id="basic-addon1">Rp.</span>
              <input type="text" name="koreksi" class="form-control nominal" value="{{ $a_param->koreksi }}" style="text-align: right;">
            </div>
          </div>

          <div class="mb-3">
            <div class="title mb-2">Pembagian Jasa</div>
              <table class="table table-bordered" width="100%" style="font-size: 3vw;">          
                <tr>
                  <td width="40%" style="vertical-align: middle;">Direksi</td>
                  <td width="25%">
                    <div class="input-group">
                      <input type="number" class="form-control" step="any" name="direksi" value="{{ $a_param->direksi }}" style="text-align: right;">
                      <span class="input-group-text" id="basic-addon1">%</span>
                    </div>
                  </td>            
                </tr>
                <tr>
                  <td style="vertical-align: middle;">Staf Direksi</td>
                  <td>
                    <div class="input-group">
                      <input type="number" class="form-control" step="any" name="staf" value="{{ $a_param->staf }}" style="text-align: right;">
                      <span class="input-group-text" id="basic-addon1">%</span>
                    </div>
                  </td>            
                </tr>      
                <tr>            
                  <td style="vertical-align: middle;">Pos Remun</td>
                  <td>
                    <div class="input-group">
                      <input type="number" class="form-control" step="any" name="pos_remun" value="{{ $a_param->pos_remun }}" style="text-align: right;">
                      <span class="input-group-text" id="basic-addon1">%</span>
                    </div>
                  </td>            
                </tr>     
                <tr style="background-color: #f2f2f2;">
                  <td style="vertical-align: middle; font-weight: bold;">NON PENGHASIL</td>
                  <td>
                    <div class="input-group">
                      <input type="number" class="form-control" value="{{ $total->total_nonpenghasil }}" style="text-align: right;" step="0.01" disabled>
                    <span class="input-group-text" id="basic-addon1">%</span>
                  </div>
                </td>                    
              </tr>          
              <tr>
                <td style="vertical-align: middle;">Administrasi</td>
                <td>
                  <div class="input-group">
                    <input type="number" class="form-control" step="any" name="admin" value="{{ $a_param->admin }}" style="text-align: right;">
                    <span class="input-group-text" id="basic-addon1">%</span>
                  </div>
                </td>            
              </tr>
              <tr>            
                <td style="vertical-align: middle;">Medis / Perawat Setara</td>
                <td>
                  <div class="input-group">
                    <input type="number" class="form-control" step="any" name="medis_perawat" value="{{ $a_param->medis_perawat }}" style="text-align: right;">
                    <span class="input-group-text" id="basic-addon1">%</span>
                  </div>
                </td>
              </tr>
              <tr style="background-color: #f2f2f2;">
                <td style="vertical-align: middle; font-weight: bold;">PENGHASIL</td>
                <td>
                  <div class="input-group">
                    <input type="number" class="form-control" step="any" value="{{ $total->total_penghasil }}" style="text-align: right;" disabled>
                    <span class="input-group-text" id="basic-addon1">%</span>
                  </div>
                </td>
              </tr>          
              <tr style="background-color: #e4e4e4;">
                <td style="vertical-align: middle; font-weight: bold; text-align: center;">TOTAL</td>
                <td>
                  <div class="input-group">
                    <input type="number" class="form-control" value="{{ number_format($total->total_penghasil + $total->total_nonpenghasil,2) }}" style="text-align: right;" disabled>
                    <span class="input-group-text" id="basic-addon1">%</span>
                  </div>
                </td>
              </tr>          
            </table> 
          </div>
          <button class="btn btn-success w-100" type="submit">SIMPAN</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
@endsection