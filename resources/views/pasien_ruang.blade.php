@extends('layouts.content')
@section('title','Layanan')

@section('content')
<div class="span3">
  <form method="GET" action="{{ route('pasien_ruang') }}" style="margin-bottom: 0px;">
  @csrf
    <select class="form-control" name="pasienku" onchange="this.form.submit();" style="width: 105%;">
      <option value="0" {{ $pasienku == '0'? 'selected' : null }}>PASIEN {{ strtoupper($c_ruang->ruang) }}</option>
      <option value="1" {{ $pasienku == '1'? 'selected' : null }}>SEMUA PASIEN</option>
    </select>

    <input type="text" name="cari" class="form-control" value="{{ $cari }}" placeholder="Cari pasien" onchange="this.form.submit();" style="margin-top: -10px;">
  </form>      

  <div class="layanan" id="layanan" onscroll="setScrollPosition(this.scrollTop);">
    @foreach($pasien as $pas)            
      <form method="GET" action="{{ route('pasien_ruang') }}">
      @csrf
        <input type="hidden" name="id_pasien" value="{{ Crypt::encrypt($pas->id_pasien) }}">
        <input type="hidden" name="cari" value="{{ $cari }}">
        <input type="hidden" name="pasienku" value="{{ $pasienku }}">

        @if($pass)
          @if($pas->id_pasien == $pass->id)
          <button type="button" class="btn btn-success btn-block" style="padding: 3px 6px; margin-bottom: -17px;">
          @else
          <button type="submit" class="btn btn-block" style="padding: 3px 6px; margin-bottom: -17px;">
          @endif
        @else
          <button type="submit" class="btn btn-block" style="padding: 3px 6px; margin-bottom: -17px;">
        @endif
          
          <table width="100%" style="font-size: 12px; text-align: left; line-height: 15px;">
            <tr>
              <td colspan="3" style="font-weight: bold; font-size: 13px;">
                {{ strtoupper($pas->nama) }} 
                @if($pas->umur_bln)
                  ({{ $pas->umur_thn }} Thn. {{ $pas->umur_bln }} Bln.)
                @else
                  ({{ $pas->umur_thn }} Thn.)
                @endif
              </td>
            </tr>
            <tr>
              <td width="70" valign="top">Reg / MR</td>
              <td width="10" valign="top">:</td>
              <td>{{ strtoupper($pas->register) }} / {{ strtoupper($pas->no_mr) }}</td>
            </tr>                    
            <tr>
              <td valign="top">Jenis</td>
              <td valign="top">:</td>
              <td>
                PASIEN {{ strtoupper($pas->jenis_pasien) }}
              </td>
            </tr>
            <tr>
              <td valign="top">DPJP</td>
              <td valign="top">:</td>
              <td>{{ $pas->dpjp }}</td>
            </tr>
            @if($pas->id_ruang !== Auth::user()->id_ruang)
            <tr>
              <td valign="top">Ruang</td>
              <td valign="top">:</td>
              <td>
                {{ strtoupper($pas->ruang) }}
              </td>
            </tr>
            @endif
          </table>
        </button>
      </form>
    @endforeach
  </div>
</div>      
    
<div class="span9">
  <div class="navbar" style="margin-bottom:5px;">
    <div class="navbar-inner">
      <div class="btn-group" style="float: left;">
        @if($c_ruang->terima_pasien == 1)
          <a href="#" class="btn btn-info" data-toggle="modal" title="Pasien Masuk" data-target="#pasien_baru">
            PASIEN BARU
          </a>
        @endif

        @if($pass)
          @if($pass->id_ruang !== Auth::user()->id_ruang)
            <button class="btn btn-primary" title="Layanan Pasien" data-toggle="collapse" href="#layanan_lain">
              TAMBAH LAYANAN
            </button>
          @else
            @if($pass->id_dpjp)
              <button class="btn btn-primary" title="Layanan Pasien" data-toggle="collapse" href="#collapseExample">
                TAMBAH LAYANAN
              </button>

              @if($pass->id_ruang == Auth::user()->id_ruang)
                <button class="btn btn-primary" title="Pindah Ruang" data-toggle="collapse" href="#pindah_ruang">
                  PINDAH RUANG
                </button>

                <button class="btn btn-primary" title="Ganti DPJP" data-toggle="collapse" href="#ganti_dpjp">
                  GANTI DPJP
                </button>

                <button class="btn btn-primary" title="Ubah Jenis" data-toggle="collapse" href="#ubah_status">
                  UBAH JENIS
                </button>
              @endif
            @else
              @if($pass->id_ruang == Auth::user()->id_ruang)
                <button class="btn btn-primary" title="Tambah DPJP" data-toggle="modal" data-target="#pasien_dpjp_baru">
                  DPJP
                </button>
              @endif
            @endif

            @if($pass->id_ruang == Auth::user()->id_ruang)
              <button class="btn btn-primary" title="Edit Data Pasien" data-toggle="collapse" href="#edit_pasien">
                EDIT PASIEN
              </button>
            @endif
          @endif
        @endif
      </div>
      <div class="btn-group" style="float: right;">
        @if($pass && $pass->id_ruang == Auth::user()->id_ruang)
          <a href="{{ route('pasien_batal',Crypt::encrypt($pass->id)) }}" class="btn btn-danger" title="Batalkan Pasien" onclick="return confirm('Batalkan penerimaan pasien ?')">
            BATAL
          </a>      
        @endif

        @if($pass && $pass->id_ruang == Auth::user()->id_ruang && $pass->id_dpjp)
          <a href="{{ route('pasien_pulang',Crypt::encrypt($pass->id)) }}" class="btn btn-danger" title="Pasien Keluar" onclick="return confirm('Apakah pasien akan keluar ?')">
            KELUAR
          </a>
        @endif
      </div>      
    </div>
  </div>

  @if($pass)
  <div class="collapse" id="edit_pasien">
    <div class="content" style="margin-bottom: 5px;">
      <form class="form-horizontal container-fluid fprev" method="POST" action="{{ route('pasien_edit_ruang') }}">
      @csrf
        <input type="hidden" name="id" value="{{ Crypt::encrypt($pass->id) }}">

        <div class="control-group">              
          <label class="control-label span2">Nama Pasien</label>
          <div class="controls span10">
            <input type="text" name="nama" class="form-control" value="{{ $pass->nama }}">
          </div>
        </div>

        <div class="control-group">              
          <label class="control-label span2">No. Register</label>
          <div class="controls span4">
            <input type="text" name="register" class="form-control" value="{{ $pass->register }}" readonly>
          </div>
            
          <label class="control-label span2">No. MR</label>
          <div class="controls span4">
            <input type="text" name="no_mr" class="form-control" value="{{ $pass->no_mr }}">
          </div>
        </div>

        <div class="control-group">              
          <label class="control-label span2">Alamat</label>
          <div class="controls span10">
            <input type="text" name="alamat" class="form-control" value="{{ $pass->alamat }}">
          </div>
        </div>

        <div class="control-group">
          <label class="control-label span2">Umur</label>
          <div class="controls span1">
            <div class="input-append">
              <input type="number" name="umur_thn" step="1" class="form-control" required value="{{ $pass->umur_thn }}">
              <span class="add-on">Thn.</span>
            </div>
          </div>            
          <div class="controls span1 offset1">
            <div class="input-append">
              <input type="number" name="umur_bln" step="1" class="form-control" value="{{ $pass->umur_bln }}">
              <span class="add-on">Bln.</span>
            </div>
          </div>

          <label class="control-label span3">Kelamin</label>
          <div class="controls span4">
            <select class="form-control" name="id_kelamin" style="width: 104.5%;">
              <option value="1" {{ $pass->id_kelamin == '1'? 'selected' : null }}>LAKI-LAKI</option>
              <option value="2" {{ $pass->id_kelamin == '2'? 'selected' : null }}>PEREMPUAN</option>
            </select>
          </div>
        </div>

        <div class="control-group">
          <div class="span10 offset2">
            <div class="btn-group">
              <button type="submit" class="btn btn-primary bprev">SIMPAN</button>
              <button type="button" class="btn" data-toggle="collapse" href="#edit_pasien" aria-expanded="false" aria-controls="edit_pasien">BATAL</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="collapse" id="ubah_status">
    <div class="content" style="margin-bottom: 5px;">
      <form class="form-horizontal fprev" method="POST" action="{{ route('pasien_ubah_status') }}" onsubmit="return confirm('Ubah jenis pasien ?')">
      @csrf
        <input type="hidden" name="id" value="{{ $pass->id }}">

        <div class="control-group">              
          <div class="controls span12">
            <select class="form-control" name="jenis" size="10">
              @foreach($jenis as $jns)
                @if($pass->id_pasien_jenis !== $jns->id)
                <option value="{{ $jns->id }}" {{ $pass->id_pasien_jenis == $jns->id? 'selected' : null }}>{{ strtoupper($jns->jenis) }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>

        <div class="control-group">
          <div class="span12">
            <div class="btn-group">
              <button type="submit" class="btn btn-primary bprev">UBAH</button>
              <button type="button" class="btn" data-toggle="collapse" href="#ubah_status" aria-expanded="false" aria-controls="ubah_status">BATAL</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="collapse" id="ganti_dpjp">
    <div class="content" style="margin-bottom: 5px;">
      <form class="form-horizontal fprev" method="POST" action="{{ route('pasien_ganti_dpjp') }}" onsubmit="return confirm('Ganti DPJP pasien ?')">
      @csrf
        <input type="hidden" name="id_pasien_ruang" value="{{ $pass->id_pasien_ruang }}">
        <input type="hidden" name="id_pasien" value="{{ $pass->id }}">

        <div class="control-group">              
          <div class="controls span12">
            <select class="form-control" name="id_dpjp" size="10">
              @foreach($dpjp as $dok)
                @if($pass->id_dpjp !== $dok->id)
                <option value="{{ $dok->id }}" {{ $pass->id_dpjp == $dok->id? 'selected' : null }}>{{ $dok->nama }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>

        <div class="btn-group">
          <button type="submit" class="btn btn-primary bprev">SIMPAN</button>
          <button type="button" class="btn" data-toggle="collapse" href="#ganti_dpjp">BATAL</button>
        </div>
      </form>
    </div>
  </div>
      
  <div class="collapse" id="pindah_ruang">
    <div class="content" style="margin-bottom: 5px;">
      <form class="form-horizontal fprev" method="POST" action="{{ route('pasien_pindah') }}" onsubmit="return confirm('Pindahkan pasien ?')">
      @csrf
        <input type="hidden" name="id_pasien_ruang" value="{{ $pass->id_pasien_ruang }}">
        <input type="hidden" name="id_pasien" value="{{ $pass->id }}">
        <input type="hidden" name="id_jenis" value="{{ $pass->id_pasien_jenis }}">

        <div class="control-group">              
          <div class="span12">
            <select class="form-control" name="id_ruang" size="10" required autofocus>
              @foreach($d_ruang as $run)
                <option value="{{ $run->id }}">{{ strtoupper($run->ruang) }}</option>
              @endforeach
            </select>                
          </div>
        </div>
        <div class="btn-group">
          <button type="submit" class="btn btn-primary bprev">PINDAH</button>
          <button type="button" class="btn" data-toggle="collapse" href="#pindah_ruang">BATAL</button>
        </div>
      </form>
    </div>
  </div>

  <div class="collapse" id="collapseExample">
    <div class="content" style="margin-bottom: 5px;">          
      <form class="form-horizontal fprev" method="POST" action="{{ route('pasien_layanan_multi') }}">
      @csrf
        <input type="hidden" name="id_pasien_ruang" value="{{ $pass->id_pasien_ruang }}">
        <input type="hidden" name="id_pasien" value="{{ $pass->id }}">
        <input type="hidden" name="id_pasien_jenis" value="{{ $pass->id_pasien_jenis }}">
        <input type="hidden" name="id_pasien_jenis_rawat" value="{{ $pass->id_pasien_jenis_rawat }}">
            
        <table width="100%" class="table table-hover table-striped" style="font-size: 12px;">
          <thead>                
            <th>JASA</th>
            <th width="40%">NAMA DOKTER</th>
            <th width="15%">TARIF (Rp.)</th>
          </thead>
          <tbody>
          @foreach($jasa as $jas)
            <tr>
              <td>{{ strtoupper($jas->jasa) }}</td>
              <td style="padding-top: 4px; padding-bottom: 0;">
                @if($jas->id_jasa == 2 || $jas->id_jasa == 3 || $jas->id_jasa == 4)
                <select class="form-control select2" name="id_dpjp[]">
                  <option value=""></option>
                  @foreach($dpjp_lain as $dok)
                    <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
                  @endforeach
                </select>
                @else
                  @if($jas->id_jasa == 50)
                  <select class="form-control select2" name="id_dpjp[]">
                    <option value=""></option>
                    @foreach($dpjp_anastesi as $anastesi)
                      <option value="{{ $anastesi->id }}">{{ $anastesi->nama }}</option>
                    @endforeach
                  </select>
                  @else
                  <input type="hidden" name="id_dpjp[]" value="{{ $pass->id_dpjp }}">
                  @endif                
                @endif
              </td>
              <td style="padding-top: 4px; padding-bottom: 0; padding-right: 20px;">
                <input type="hidden" name="id_jasa[]" value="{{ $jas->id_jasa }}">
                <input type="text" style="text-align: right;" class="form-control nominal" name="tarif[]" autocomplete="off">
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>   

        <div class="btn-group">         
          <button type="submit" class="btn btn-primary bprev">SIMPAN</button>
          <button type="button" class="btn" data-toggle="collapse" href="#collapseExample">BATAL</button>
        </div>
      </form>
    </div>
  </div>

  <div class="collapse" id="layanan_lain">
    <div class="content" style="margin-bottom: 5px;">          
      <form class="form-horizontal fprev" method="POST" action="{{ route('pasien_layanan_multi_lain') }}">
      @csrf
        <input type="hidden" name="id_pasien_ruang" value="{{ $pass->id_pasien_ruang }}">
        <input type="hidden" name="id_pasien" value="{{ $pass->id }}">
        <input type="hidden" name="id_pasien_jenis" value="{{ $pass->id_pasien_jenis }}">
        <input type="hidden" name="jalan" value="{{ $c_ruang->jalan }}">
        <input type="hidden" name="id_ruang" value="{{ $pass->id_ruang }}">

        @if($c_ruang->jalan == 1)
          <input type="hidden" name="id_pasien_jenis_rawat" value="1">
        @else
        @if($c_ruang->inap == 1)
          <input type="hidden" name="id_pasien_jenis_rawat" value="2">
        @else
          <input type="hidden" name="id_pasien_jenis_rawat" value="{{ $pass->id_pasien_jenis_rawat }}">
        @endif
        @endif

        <div class="control-group">
          <label class="span2" style="font-weight: bold;">NAMA DPJP</label>
          <div class="controls span10">
            <select class="form-control select2" name="id_dpjp_real" required autofocus>
              <option value=""></option>
              @foreach($dpjp as $dok)
                <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
              @endforeach
            </select>
          </div>
        </div>
            
        <table width="100%" class="table table-hover table-striped" style="margin-top: 10px; font-size: 12px;">
          <thead>                
            <th>JASA</th>
            <th width="40%">NAMA DOKTER</th>
            <th width="15%">TARIF (Rp.)</th>
          </thead>
          <tbody>
          @foreach($jasa as $jas)
            <tr>
              <td>{{ strtoupper($jas->jasa) }}</td>
              <td style="padding-top: 4px; padding-bottom: 0;">
                @if($jas->id_jasa == 2 || $jas->id_jasa == 3 || $jas->id_jasa == 4)
                <select class="form-control select2" name="id_dpjp[]">
                  <option value=""></option>
                  @foreach($dpjp_lain as $dok)
                    <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
                  @endforeach
                </select>
                @else
                <input type="hidden" name="id_dpjp[]" value="">
                @endif
              </td>
              <td style="padding-top: 4px; padding-bottom: 0; padding-right: 20px;">
                <input type="hidden" name="id_jasa[]" value="{{ $jas->id_jasa }}">
                <input type="text" class="form-control nominal" name="tarif[]" style="text-align: right;" autocomplete="off">
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
        <div class="btn-group">
          <button type="submit" class="btn btn-primary bprev">SIMPAN</button>
          <button type="button" class="btn" data-toggle="collapse" href="#layanan_lain" aria-expanded="false" aria-controls="layanan_lain">BATAL</button>
        </div>
      </form>
    </div>
  </div>

    <div class="content" style="margin-bottom: 5px;">
      <table width="100%" style="font-size: 12px; line-height: 13px;">
      <tr>
        <td width="100" valign="top">Nama Pasien</td>
        <td width="10" valign="top">:</td>
        <td width="30%" valign="top"><b>{{ strtoupper($pass->nama) }}</b> <span>({{ strtoupper($pass->jenis_pasien) }})</span></td>
        <td width="50" valign="top">Alamat</td>
        <td width="10" valign="top">:</td>
        <td valign="top">{{ strtoupper($pass->alamat) }}</td>
        <td rowspan="3" style="text-align: right; font-weight: bold; font-size: 20px; color: #6884bc;">
          Rp. {{ number_format($total->tarif,0) }}
        </td>
      </tr>            
      <tr>
        <td valign="top">Reg. / MR</td>
        <td valign="top">:</td>
        <td valign="top">{{ strtoupper($pass->register) }} / {{ strtoupper($pass->no_mr) }}</td>
        <td valign="top">Umur</td>
        <td valign="top">:</td>
        <td valign="top">{{ strtoupper($pass->umur_thn) }} Thn. {{ strtoupper($pass->umur_bln) }} Bln.</td>
      </tr>            
      <tr>          
        <td valign="top">Ruang Perawatan</td>
        <td valign="top">:</td>
        <td valign="top">{{ strtoupper($pass->ruang) }}</td>
        <td valign="top">DPJP</td>
        <td valign="top">:</td>
        <td valign="top">{{ $pass->dpjp }}</td>
      </tr>            
    </table>
  </div>

  <div class="content" id="jasa">
      <table width="100%" id="tabel" class="table table-hover table-striped" style="font-size: 12px;">
        <thead>
          <th></th>
          <th style="text-align: center; padding: 0 10px;">WAKTU</th>
          <th style="text-align: center; padding: 0 10px;">R. PERAWATAN</th>
          <th style="text-align: center; padding: 0 10px;">R. TINDAKAN</th>              
          <th style="text-align: center; padding: 0 10px;">NAMA DOKTER</th>
          <th style="text-align: center; padding: 0 10px;">JASA</th>
          <th style="text-align: center; padding: 0 10px;">TARIF</th>
        </thead>
        <tbody>
          @foreach($ruang as $rng)
          <tr>
            <td class="min" style="vertical-align: middle;">
              @if($rng->id_ruang_sub == Auth::user()->id_ruang)
              <a href="{{ route('pasien_layanan_hapus',Crypt::encrypt($rng->id)) }}" class="btn btn-info btn-mini btn-block" onclick="return confirm('Hapus layanan ?')" title="Hapus Layanan">
                <i class="icon-trash"></i>
              </a>
              @endif
            </td>
            <td class="min" style="vertical-align: middle;">{{ $rng->waktu }}</td>
            <td class="min" style="vertical-align: middle;">{{ strtoupper($rng->ruang) }}</td>
            <td class="min" style="vertical-align: middle;">{{ strtoupper($rng->ruang_sub) }}</td>                
            <td class="min" style="vertical-align: middle;">{{ $rng->nama }}</td>
            <td style="vertical-align: middle;">
              @if($rng->id_jasa == 2)
                {{ $rng->jasa }} {{ $rng->dpjp_real }}
              @endif

              @if($rng->id_jasa == 3)
                {{ $rng->jasa }} {{ $rng->konsul }}
              @endif

              @if($rng->id_jasa == 4)
                {{ $rng->jasa }} {{ $rng->pengganti }}
              @endif

              @if($rng->id_jasa !== 2 && $rng->id_jasa !== 3 && $rng->id_jasa !== 4)
                {{ $rng->jasa }}
              @endif
            </td>
            <td style="vertical-align: middle; text-align: right;">{{ number_format($rng->tarif,0) }}</td>
          </tr>            
          @endforeach
        </tbody>
      </table>
    @endif                              
  </div>
</div>

@if($pass)
<div class="modal hide fade" id="pasien_dpjp_baru">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">PILIH DPJP</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="pasien_dpjp_baru_form" method="POST" action="{{ route('pasien_dpjp_baru') }}">
    @csrf
      <input type="hidden" name="id_pasien_ruang" value="{{ $pass->id_pasien_ruang }}">
      <input type="hidden" name="id_pasien" value="{{ $pass->id }}">
      <input type="hidden" name="id_jenis" value="{{ $pass->id_pasien_jenis }}">

      <div class="control-group">
        <div class="span12">
          <select class="form-control" name="id_dpjp" size="15" required autofocus>
            @foreach($dpjp as $dok)
              @if($pass->id_dpjp)
                <option value="{{ $dok->id }}" {{ $pass->id_dpjp == $dok->id? 'selected' : null }}>{{ $dok->nama }}</option>
              @else
                <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
              @endif
            @endforeach
          </select>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">
    <div class="btn-group">
      <button type="submit" form="pasien_dpjp_baru_form" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>
@endif

<div class="modal hide fade" id="pasien_baru">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">PASIEN BARU</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal container-fluid fprev" id="pasien_baru_form" method="POST" action="{{ route('pasien_baru') }}">
    @csrf
      @if($c_ruang->jalan == 1)
        <input type="hidden" name="id_pasien_jenis_rawat" value="1">
      @endif

      @if($c_ruang->inap == 1)
        <input type="hidden" name="id_pasien_jenis_rawat" value="2">
      @endif

      <div class="control-group">
        <label class="control-label span3">Nama Pasien</label>
        <div class="controls span9">
          <input type="text" class="form-control" name="nama" required autofocus autocomplete="off">
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Alamat</label>
        <div class="controls span9">
          <input type="text" class="form-control" name="alamat">
        </div>
      </div>          

      <div class="control-group">
        <label class="control-label span3">Umur</label>
        <div class="controls span2">
          <div class="input-append">
            <input type="number" name="umur_thn" step="1" class="form-control" required autocomplete="off">
            <span class="add-on">Thn.</span>
          </div>
        </div>            
        <div class="controls span2 offset2">
          <div class="input-append">
            <input type="number" name="umur_bln" step="1" class="form-control" autocomplete="off">
            <span class="add-on">Bln.</span>
          </div>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">Kelamin</label>
        <div class="controls span9">
          <select class="form-control" name="id_kelamin" required style="width: 104%;">
            <option value="1">LAKI-LAKI</option>
            <option value="2">PEREMPUAN</option>
          </select>
        </div>            
      </div>

      <div class="control-group">
        <label class="control-label span3">Nomor MR</label>
        <div class="controls span9">
          <input type="text" class="form-control" name="no_mr" required autocomplete="off">
        </div>            
      </div>

      <div class="control-group">
        <label class="control-label span3">Jenis Pasien</label>
        <div class="controls span9">
          <select class="form-control" name="id_pasien_jenis" required style="width: 104%;">
            <option value=""></option>
            @foreach($jenis as $jns)
              <option value="{{ $jns->id }}">{{ strtoupper($jns->jenis) }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span3">DPJP</label>
        <div class="controls span9">
          <select class="form-control" size="5" name="id_dpjp" required style="width: 104%;">
            @foreach($dpjp as $dok)
              <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="span12" style="margin-top: 5px;">
        <div class="alert alert-danger bg-danger text-white" role="alert" style="padding: 10px;">
          * <b><i>Pastikan</i></b> bahwa data pasien yang Anda entri sudah benar !!!
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">    
    <div class="btn-group pull-right">
      <button type="submit" form="pasien_baru_form" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      var box = document.querySelector('#jasa');
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

  <script>
    $(document).ready(function() {
      $('.select2').select2();     
    });

    //$('#tabel').margetable({
      //type: 2,
      //colindex: [0, 1, 2]
    //});
  </script>

  <script type="text/javascript">
    window.onload=function() {
      document.getElementById('dokter').style.display = 'none';
      document.getElementById('dokter').style.visibility = 'hidden';
      document.getElementById('id_dpjp').required = false;
    };    

    $('#id_jasa').on('change',function(){
      $id_jasa = $(this).val();

      if($id_jasa == 3 || $id_jasa == 4){
        document.getElementById('dokter').style.display = 'block';
        document.getElementById('dokter').style.visibility = 'visible';
        document.getElementById('id_dpjp').required = true;
      } else {
        document.getElementById('dokter').style.display = 'none';
        document.getElementById('dokter').style.visibility = 'hidden';
        document.getElementById('id_dpjp').required = false;
      }
    });
  </script>
@endsection