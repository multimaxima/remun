@extends('layouts.content')
@section('title','Parameter Remunerasi')

@section('content')
<div class="navbar">
  <div class="navbar-inner">
    <ul class="nav">
      <li><a href="{{ route('parameter') }}">Data Rumah Sakit</a></li>
      <li class="active"><a href="#">Parameter Remunerasi</a></li>
    </ul>
    <button type="submit" form="data" class="btn btn-primary pull-right">SIMPAN</button>
  </div>
</div>

<form class="form-horizontal" id="data" method="POST" action="{{ route('parameter_software_simpan') }}">
@csrf
  <input type="hidden" name="id" value="{{ Crypt::encrypt($a_param->id) }}">

  <div class="content">
    @include('layouts.pesan')
    <div class="control-group">
      <label class="control-label span2">Menu Update Histori Kary.</label>
      <div class="controls span3">
        <select name="histori" class="form-control" style="width: 78%;">
          <option value="0" {{ $a_param->histori == '0'? 'selected' : null }}>TIDAK AKTIF</option>
          <option value="1" {{ $a_param->histori == '1'? 'selected' : null }}>AKTIF</option>
        </select>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label span2">Dasar Perhitungan Remun</label>
      <div class="controls span3">
        <select name="dasar_remun" class="form-control" style="width: 78%;">
          <option value="1" {{ $a_param->dasar_remun == '1'? 'selected' : null }}>DATA KARYAWAN TERBARU</option>
          <option value="2" {{ $a_param->dasar_remun == '2'? 'selected' : null }}>HISTORY DATA KARYAWAN</option>
        </select>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label span2">Maks. Jasa Farmasi</label>
      <div class="controls span2">
        <div class="input-prepend">          
          <input class="form-control" type="number" step="any" name="farmasi" value="{{ $a_param->farmasi }}" style="  text-align: right; width: 100px;">
          <span class="add-on" style="width: 150px;">% Dari Total JP</span>
        </div>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label span2">Jasa Dokter Umum</label>
      <div class="controls span2">
        <div class="input-prepend">          
          <input class="form-control" type="number" step="any" name="dokter_umum" value="{{ $a_param->dokter_umum }}" style="  text-align: right; width: 100px;">
          <span class="add-on" style="width: 150px;">% Dari Jasa Farmasi</span>
        </div>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label span2">Jasa Anastesi</label>
      <div class="controls span2">
        <div class="input-prepend">          
          <input class="form-control" type="number" step="any" name="anastesi" value="{{ $a_param->anastesi }}" style="  text-align: right; width: 100px;">
          <span class="add-on" style="width: 150px;">% Dari Jasa Operator</span>
        </div>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label span2">Koreksi Gaji Pokok</label>
      <div class="controls span2">
        <div class="input-prepend">
          <span class="add-on">Rp.</span>
          <input class="form-control nominal" type="text" name="koreksi" value="{{ $a_param->koreksi }}" style="  text-align: right;">
        </div>
      </div>
    </div>

    <div class="control-group">    
      <label class="control-label span2">Pembagian Jasa</label>
      <div class="controls span10">
        <table class="table table-bordered" style="width: 50%;">          
          <tr>
            <td width="40%" style="vertical-align: middle;">Direksi</td>
            <td width="25%">
              <div class="input-append" style="margin-right: 40px;">
                <input type="number" class="form-control" step="any" name="direksi" value="{{ $a_param->direksi }}" style="text-align: right;">
                <span class="add-on">%</span>
              </div>
            </td>
            <td style="text-align: right; vertical-align: middle;">
              <div class="input-append" style="margin-right: 40px;">
                <input type="text" class="form-control" value="{{ ($a_param->direksi / ($total->total_nonpenghasil)) * 100 }}" style="text-align: right;" disabled>
                <span class="add-on">%</span>
              </div>
              
            </td>
          </tr>
          <tr>
            <td style="vertical-align: middle;">Staf Direksi</td>
            <td>
              <div class="input-append" style="margin-right: 40px;">
                <input type="number" class="form-control" step="any" name="staf" value="{{ $a_param->staf }}" style="text-align: right;">
                <span class="add-on">%</span>
              </div>
            </td>
            <td style="text-align: right; vertical-align: middle;">
              <div class="input-append" style="margin-right: 40px;">
                <input type="text" class="form-control" value="{{ ($a_param->staf / ($total->total_nonpenghasil)) * 100 }}" style="text-align: right;" disabled>
                <span class="add-on">%</span>
              </div>
            </td>
          </tr>      
          <tr>            
            <td style="vertical-align: middle;">Pos Remun</td>
            <td>
              <div class="input-append" style="margin-right: 40px;">
                <input type="number" class="form-control" step="any" name="pos_remun" value="{{ $a_param->pos_remun }}" style="text-align: right;">
                <span class="add-on">%</span>
              </div>
            </td>
            <td style="text-align: right; vertical-align: middle;">
              <div class="input-append" style="margin-right: 40px;">
                <input type="text" class="form-control" value="{{ ($a_param->pos_remun / ($total->total_nonpenghasil)) * 100 }}" style="text-align: right;" disabled>
                <span class="add-on">%</span>
              </div>              
            </td>
          </tr>     
          <tr style="background-color: #f2f2f2;">
            <td style="vertical-align: middle; font-weight: bold;">NON PENGHASIL</td>
            <td>
              <div class="input-append" style="margin-right: 40px;">
                <input type="number" class="form-control" value="{{ $total->total_nonpenghasil }}" style="text-align: right;" step="0.01" disabled>
                <span class="add-on">%</span>
              </div>
            </td>        
            <td style="text-align: right; vertical-align: middle;">
              <div class="input-append" style="margin-right: 40px;">
                <input type="text" class="form-control" value="{{ number_format((($a_param->direksi / ($total->total_nonpenghasil)) * 100) + (($a_param->staf / ($total->total_nonpenghasil)) * 100) + (($a_param->pos_remun / ($total->total_nonpenghasil)) * 100),2) }}" style="text-align: right;" disabled>
                <span class="add-on">%</span>
              </div>              
            </td>      
          </tr>          
          <tr>
            <td style="vertical-align: middle;">Administrasi</td>
            <td>
              <div class="input-append" style="margin-right: 40px;">
                <input type="number" class="form-control" step="any" name="admin" value="{{ $a_param->admin }}" style="text-align: right;">
                <span class="add-on">%</span>
              </div>
            </td>
            <td style="text-align: right; vertical-align: middle;">
              <div class="input-append" style="margin-right: 40px;">
                <input type="text" class="form-control" value="{{ number_format(($a_param->admin / ($total->total_penghasil)) * 100,2) }}" style="text-align: right;" disabled>
                <span class="add-on">%</span>
              </div>
            </td>
          </tr>
          <tr>            
            <td style="vertical-align: middle;">Medis / Perawat Setara</td>
            <td>
              <div class="input-append" style="margin-right: 40px;">
                <input type="number" class="form-control" step="any" name="medis_perawat" value="{{ $a_param->medis_perawat }}" style="text-align: right;">
                <span class="add-on">%</span>
              </div>
            </td>
            <td style="text-align: right; vertical-align: middle;">
              <div class="input-append" style="margin-right: 40px;">
                <input type="text" class="form-control" value="{{ number_format(($a_param->medis_perawat / ($total->total_penghasil)) * 100,2) }}" style="text-align: right;" disabled>
                <span class="add-on">%</span>
              </div>
            </td>
          </tr>
          <tr style="background-color: #f2f2f2;">
            <td style="vertical-align: middle; font-weight: bold;">PENGHASIL</td>
            <td>
              <div class="input-append" style="margin-right: 40px;">
                <input type="number" class="form-control" step="any" value="{{ $total->total_penghasil }}" style="text-align: right;" disabled>
                <span class="add-on">%</span>
              </div>
            </td>
            <td style="text-align: right; vertical-align: middle;">
              <div class="input-append" style="margin-right: 40px;">
                <input type="text" class="form-control" value="{{ number_format((($a_param->admin / ($total->total_penghasil)) * 100) + (($a_param->medis_perawat / ($total->total_penghasil)) * 100),2) }}" style="text-align: right;" disabled>
                <span class="add-on">%</span>
              </div>
            </td>
          </tr>          
          <tr style="background-color: #e4e4e4;">
            <td style="vertical-align: middle; font-weight: bold; text-align: center;">TOTAL</td>
            <td>
              <div class="input-append" style="margin-right: 40px;">
                <input type="number" class="form-control" value="{{ number_format($total->total_penghasil + $total->total_nonpenghasil,2) }}" style="text-align: right;" disabled>
                <span class="add-on">%</span>
              </div>
            </td>
            <td></td>
          </tr>          
        </table> 
      </div>
    </div>

    <div class="control-group">
      <label class="control-label span2">Pot. Jasa Untuk Dok. UGD</label>
      <div class="controls span2">
        <div class="input-prepend">
          <span class="add-on" style="width: 150px; text-align: left;">Dokter Spesialis</span>
          <input class="form-control" type="number" name="pot_spesialis" value="{{ $a_param->pot_spesialis }}" style="text-align: right; width: 75px;">
          <span class="add-on">%</span>
        </div>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label span2"></label>
      <div class="controls span2">
        <div class="input-prepend">
          <span class="add-on" style="width: 150px; text-align: left;">Apotik</span>
          <input class="form-control" type="number" name="pot_apotik" value="{{ $a_param->pot_apotik }}" style="text-align: right; width: 75px;">
          <span class="add-on">%</span>
        </div>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label span2"></label>
      <div class="controls span2">
        <div class="input-prepend">
          <span class="add-on" style="width: 150px; text-align: left;">Hermodialisa</span>
          <input class="form-control" type="number" name="pot_hd" value="{{ $a_param->pot_hd }}" style="text-align: right; width: 75px;">
          <span class="add-on">%</span>
        </div>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label span2"></label>
      <div class="controls span2">
        <div class="input-prepend">
          <span class="add-on" style="width: 150px; text-align: left;">Nutrisionis</span>
          <input class="form-control" type="number" name="pot_nutrisionis" value="{{ $a_param->pot_nutrisionis }}" style="text-align: right; width: 75px;">
          <span class="add-on">%</span>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection