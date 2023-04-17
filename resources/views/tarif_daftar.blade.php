@extends('layouts.content')
@section('title','Skema Tarif Layanan')

@section('content')
<div class="navbar">
  <div class="navbar-inner">    
    <div class="row-fluid">
      <div class="span12" style="display: inline-flex;">
        <form class="form-inline" id="data" method="GET" action="{{ route('tarif_daftar') }}" style="margin-top: 5px; margin-bottom: 0;">
        @csrf
          <select name="jns" required onchange="this.form.submit();">
            <option value="" style="font-style: italic;">-- Jenis Pasien --</option>
            @foreach($jenis as $jen)
              <option value="{{ $jen->id }}" {{ $jen->id == $jns? 'selected' : null }}>{{ strtoupper($jen->jenis) }}</option>
            @endforeach
          </select>

          <select name="rwt" required onchange="this.form.submit();">
            <option value="" style="font-style: italic;">-- Jenis Perawatan --</option>
            @foreach($rawat as $raw)
              <option value="{{ $raw->id }}" {{ $raw->id == $rwt? 'selected' : null }}>{{ strtoupper($raw->jenis_rawat) }}</option>
            @endforeach
          </select>
        </form>

        <div class="btn-group" style="margin-left: 10px;">
          @if($rwt && $jns)
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#layanan_baru" style="margin-top: 0;" title="Tambah Layanan">
            TAMBAH LAYANAN
          </button>

          @if(count($perhitungan) == 0)
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#layanan_salin" style="margin-top: 0;" title="Salin Tarif">
            SALIN TARIF
          </button>
          @endif
          
          <button type="submit" form="cetak" class="btn btn-primary" title="Cetak">CETAK</button>

          <!--<button type="button" class="btn btn-danger" style="margin-top: 0; margin-left: 2px;" title="Hitung ulang jasa layanan">HITUNG ULANG JASA</button>-->

          <form hidden method="GET" target="_blank" id="cetak" action="{{ route('tarif_cetak') }}">
          @csrf
            <input type="hidden" name="jns" value="{{ $jns }}">
            <input type="hidden" name="rwt" value="{{ $rwt }}">
            
          </form>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

@if($perhitungan && count($perhitungan) > 0)
<div class="content">
  @include('layouts.pesan')
  @foreach($perhitungan as $hitung)
<div class="modal hide fade" id="layanan_edit{{ $hitung->id }}">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">EDIT LAYANAN</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_layanan_edit{{ $hitung->id }}" method="POST" action="{{ route('tarif_1_edit') }}">
    @csrf
      <input type="hidden" name="id" value="{{ Crypt::encrypt($hitung->id) }}">

      <select class="form-control" name="id_jasa" required autofocus size="10">
        @foreach($jasa as $jas)
          <option value="{{ $jas->id }}" {{ $hitung->id_jasa == $jas->id? 'selected' : null }}>{{ strtoupper($jas->jasa) }}</option>
        @endforeach
      </select>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_layanan_edit{{ $hitung->id }}" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="perhitungan_1_baru{{ $hitung->id }}">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">TAMBAH TARIF</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="baru_perhitungan_1{{ $hitung->id }}" method="POST" action="{{ route('tarif_2_baru') }}">
    @csrf
      <input type="hidden" name="id_perhitungan" value="{{ $hitung->id }}">

      <div class="control-group">
        <label class="control-label span3">Rekening</label>
        <div class="controls span8">
          <select class="form-control" name="id_rekening" required autofocus>
            <option value=""></option>
            @foreach($rekening as $rek)
              <option value="{{ $rek->id }}">{{ $rek->nama }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Nilai</label>
        <div class="controls span2">
          <div class="input-append">
            <input type="number" class="form-control" name="nilai" style="text-align: right;" step="0.01" required>
            <span class="add-on">%</span>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">      
    <div class="btn-group">          
      <button type="submit" form="baru_perhitungan_1{{ $hitung->id }}" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

  <table width="100%" class="table-bordered table-hover" style="margin-bottom: 10px; font-size: 12px;">
    @if($hitung->nilai_sub == 100)
    <tr>
    @else
    <tr style="background-color: #f8acac;">
    @endif
      <td style="font-weight: bold;" align="center">
        {{ strtoupper($hitung->jasa) }}<br>
        <div class="btn-group">
        <button class="btn btn-primary btn-mini" data-toggle="modal" data-target="#layanan_edit{{ $hitung->id }}">
          EDIT
        </button>
        <a href="{{ route('tarif_1_hapus',Crypt::encrypt($hitung->id)) }}" class="btn btn-primary btn-mini" onclick="return confirm('Hapus data layanan {{ $hitung->jasa }} ?')">
          HAPUS
        </a>
        <button class="btn btn-primary btn-mini" data-toggle="modal" data-target="#perhitungan_1_baru{{ $hitung->id }}">
          TAMBAH SUB
        </button>
        </div>
      </td>
      <td width="80%">
        <table width="100%" border="1" style="font-size: 12px;">
          @foreach($hitung->perhitungan_1 as $hitung_1)
<div class="modal hide fade" id="perhitungan_2_baru{{ $hitung_1->id }}">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">TAMBAH TARIF</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="baru_perhitungan_2{{ $hitung_1->id }}" method="POST" action="{{ route('tarif_3_baru') }}">
    @csrf
      <input type="hidden" name="id_perhitungan" value="{{ $hitung->id }}">
      <input type="hidden" name="id_perhitungan_1" value="{{ $hitung_1->id }}">

      <div class="control-group">
        <label class="control-label span4">Rekening</label>
        <div class="controls span7">
          <select class="form-control" name="id_rekening" required autofocus>
            <option value=""></option>
            @foreach($rekening_1 as $rek_1)
              <option value="{{ $rek_1->id }}">{{ $rek_1->nama }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span4">Nilai</label>
        <div class="controls span2">
          <div class="input-append">
            <input type="number" class="form-control" name="nilai" style="text-align: right;" step="0.01" required>
            <span class="add-on">%</span>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">  
    <div class="btn-group">              
      <button type="submit" form="baru_perhitungan_2{{ $hitung_1->id }}" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<div class="modal hide fade" id="perhitungan_2_edit{{ $hitung_1->id }}">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">EDIT TARIF</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="edit_perhitungan_1{{ $hitung_1->id }}" method="POST" action="{{ route('tarif_2_edit') }}">
    @csrf
      <input type="hidden" name="id" value="{{ Crypt::encrypt($hitung_1->id) }}">

      <div class="control-group">
        <label class="control-label span4">Rekening</label>
        <div class="controls span7">
          <select class="form-control" name="id_rekening" required autofocus>
            @foreach($rekening as $rek)
              <option value="{{ $rek->id }}" {{ $hitung_1->id_rekening == $rek->id? 'selected' : null }}>{{ $rek->nama }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span4">Nilai</label>
        <div class="controls span2">
          <div class="input-append">
            <input type="number" name="nilai" step="0.01" class="form-control" value="{{ $hitung_1->nilai }}" required>
            <span class="add-on">%</span>          
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">   
    <div class="btn-group">             
      <button type="submit" form="edit_perhitungan_1{{ $hitung_1->id }}" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>
          @if($hitung_1->nilai_sub <> 100 && $hitung_1->id_rekening <> 2)
          <tr style="background-color: #f8acac;">
          @else
          <tr>
          @endif
            <td style="font-weight: bold;" align="center">
              {{ strtoupper($hitung_1->nama) }}<br>
              <div class="btn-group">
              <button class="btn btn-warning btn-mini" data-toggle="modal" data-target="#perhitungan_2_edit{{ $hitung_1->id }}">
                EDIT
              </button>
              <a href="{{ route('tarif_2_hapus',Crypt::encrypt($hitung_1->id)) }}" class="btn btn-warning btn-mini" title="Hapus" onclick="return confirm('Hapus data {{ $hitung_1->nama }}?')">
                HAPUS
              </a>
              <button class="btn btn-warning btn-mini" data-toggle="modal" data-target="#perhitungan_2_baru{{ $hitung_1->id }}">
                TAMBAH SUB
              </button>
              </div>
            </td>
                    <td width="6%" align="right">{{ $hitung_1->nilai }} %</td>
                    <td width="75%">
                      <table width="100%" border="1" style="font-size: 12px;">
                        @foreach($hitung_1->perhitungan_2 as $hitung_2)
<div class="modal hide fade" id="perhitungan_3_baru{{ $hitung_2->id }}">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">TAMBAH TARIF</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="baru_perhitungan_3{{ $hitung_2->id }}" method="POST" action="{{ route('tarif_4_baru') }}">
    @csrf
      <input type="hidden" name="id_perhitungan" value="{{ $hitung->id }}">
      <input type="hidden" name="id_perhitungan_1" value="{{ $hitung_1->id }}">
      <input type="hidden" name="id_perhitungan_2" value="{{ $hitung_2->id }}">

      <div class="control-group">
        <label class="control-label span4">Rekening</label>
        <div class="controls span7">
          <select class="form-control" name="id_rekening" required autofocus>
            <option value=""></option>
            @foreach($rekening_2 as $rek_2)
              <option value="{{ $rek_2->id }}">{{ $rek_2->nama }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span4">Nilai</label>
        <div class="controls span2">
          <div class="input-append">
            <input type="number" name="nilai" step="0.01" class="form-control" required>
            <span class="add-on">%</span>          
          </div>          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">                
      <button type="submit" form="baru_perhitungan_3{{ $hitung_2->id }}" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>             

<div class="modal hide fade" id="perhitungan_3_edit{{ $hitung_2->id }}">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">EDIT TARIF</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="edit_perhitungan_2{{ $hitung_2->id }}" method="POST" action="{{ route('tarif_3_edit') }}">
    @csrf
      <input type="hidden" name="id" value="{{ Crypt::encrypt($hitung_2->id) }}">

      <div class="control-group">
        <label class="control-label span4">Rekening</label>
        <div class="controls span7">
          <select class="form-control" name="id_rekening" required autofocus>
            @foreach($rekening_1 as $rek_1)
              <option value="{{ $rek_1->id }}" {{ $hitung_2->id_rekening == $rek_1->id? 'selected' : null }}>{{ $rek_1->nama }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span4">Nilai</label>
        <div class="controls span2">
          <div class="input-append">
            <input type="number" name="nilai" step="0.01" class="form-control" value="{{ $hitung_2->nilai }}" required>
            <span class="add-on">%</span>          
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">     
    <div class="btn-group">           
      <button type="submit" form="edit_perhitungan_2{{ $hitung_2->id }}" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>           
                          @if($hitung_2->nilai_sub == 100.00)
                          <tr>
                          @else
                          <tr style="background-color: #f8acac;">
                          @endif
                            <td style="font-weight: bold; text-align: center;">
                              {{ strtoupper($hitung_2->nama) }}<br>
                              <div class="btn-group">
                              <button class="btn btn-info btn-mini" data-toggle="modal" data-target="#perhitungan_3_edit{{ $hitung_2->id }}">
                                EDIT
                              </button>
                              <a href="{{ route('tarif_3_hapus',Crypt::encrypt($hitung_2->id)) }}" class="btn btn-info btn-mini" title="Hapus" onclick="return confirm('Hapus data {{ $hitung_2->nama }}?')">
                                HAPUS
                              </a>
                              <button class="btn btn-info btn-mini" data-toggle="modal" data-target="#perhitungan_3_baru{{ $hitung_2->id }}">
                                TAMBAH SUB
                              </button>            
                              </div>                  
                            </td>
                            <td width="8%" align="right">{{ $hitung_2->nilai }} %</td>
                            <td width="60%">
                              <table width="100%" style="font-size: 12px;" border="1">
                                @foreach($hitung_2->perhitungan_3 as $hitung_3)
<div class="modal hide fade" id="perhitungan_4_edit{{ $hitung_3->id }}">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">EDIT TARIF</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="edit_perhitungan_4{{ $hitung_3->id }}" method="POST" action="{{ route('tarif_4_edit') }}">
    @csrf
      <input type="hidden" name="id" value="{{ Crypt::encrypt($hitung_3->id) }}">

      <div class="control-group">
        <label class="control-label span4">Rekening</label>
        <div class="controls span7">
          <select class="form-control" name="id_rekening" required autofocus>
            @foreach($rekening_2 as $rek_2)
              <option value="{{ $rek_2->id }}" {{ $hitung_3->id_rekening == $rek_2->id? 'selected' : null }}>{{ $rek_2->nama }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span4">Nilai</label>
        <div class="controls span2">
          <div class="input-append">
            <input type="number" name="nilai" step="0.01" class="form-control" value="{{ $hitung_3->nilai }}" required>
            <span class="add-on">%</span>          
          </div>          
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">     
    <div class="btn-group">           
      <button type="submit" form="edit_perhitungan_4{{ $hitung_3->id }}" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>                                
                                  <tr>
                                    <td class="min">
                                      <div class="btn-group">
                                      <button class="btn btn-success btn-mini" data-toggle="modal" data-target="#perhitungan_4_edit{{ $hitung_3->id }}">
                                        EDIT
                                      </button>
                                      <a href="{{ route('tarif_4_hapus',Crypt::encrypt($hitung_3->id)) }}" class="btn btn-success btn-mini" title="Hapus" onclick="return confirm('Hapus data {{ $hitung_3->nama }}?')">
                                        HAPUS
                                      </a>
                                      </div>
                                    </td>
                                    <td>{{ strtoupper($hitung_3->nama) }}</td>
                                    <td width="15%" align="right">{{ number_format($hitung_3->nilai,2) }} %</td>
                                  </tr>                                  
                                @endforeach
                                <tr style="background-color: #f2f2f2;">
                                  <td style="font-weight: bold;" align="center" colspan="2">TOTAL</td>
                                  <td style="font-weight: bold;" align="right">{{ number_format($hitung_2->nilai_sub,2) }} %</td>
                                </tr>
                              </table>                              
                            </td>
                          </tr>
                        @endforeach
                      </table>
                    </td>
                  </tr>                  
                @endforeach                
              </table>
            </td>
          </tr>
          </table>
          @endforeach        
</div>
@endif

<div class="modal hide fade" id="layanan_baru">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">TAMBAH LAYANAN</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_layanan_baru" method="POST" action="{{ route('tarif_1_baru') }}">
    @csrf
      <input type="hidden" name="id_pasien_jenis" value="{{ $jns }}">
      <input type="hidden" name="id_pasien_jenis_rawat" value="{{ $rwt }}">

      <select class="form-control" name="id_jasa" required autofocus size="10">
        @foreach($jasa as $jas)
          <option value="{{ $jas->id }}">{{ strtoupper($jas->jasa) }}</option>
        @endforeach
      </select>
    </form>
  </div>
  <div class="modal-footer">                
    <div class="btn-group">
      <button type="submit" form="form_layanan_baru" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>    

<div class="modal hide fade" id="layanan_salin">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">SALIN TARIF DARI</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_salin_tarif" method="POST" action="{{ route('tarif_salin') }}">
    @csrf

      <input type="hidden" name="baru_jenis" value="{{ $jns }}">
      <input type="hidden" name="baru_rawat" value="{{ $rwt }}">

      <label>Jenis Pasien</label>
      <select class="form-control" name="jenis" size="5">
        @foreach($jenis as $jns)
          <option value="{{ $jns->id }}">{{ strtoupper($jns->jenis) }}</option>
        @endforeach
      </select>

      <label style="margin-top: 10px;">Jenis Perawatan</label>
      <select class="form-control" name="rawat" required size="5">
        @foreach($rawat as $rwt)
          <option value="{{ $rwt->id }}">{{ strtoupper($rwt->jenis_rawat) }}</option>
        @endforeach
      </select>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="form_salin_tarif" class="btn bprev">SALIN</button>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>    
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('#tabel').DataTable( {        
        "searching": false,
        "info": false,
        "sort": false,
        "paging":  false,
      });

      $('#tabel1').DataTable( {        
        "searching": false,
        "info": false,
        "sort": false,
        "paging":  false,
      });

      $('#tabel2').DataTable( {        
        "searching": false,
        "info": false,
        "sort": false,
        "paging":  false,
      });

      $('#tabel3').DataTable( {        
        "searching": false,
        "info": false,
        "sort": false,
        "paging":  false,
      });
    });
  </script>
@endsection