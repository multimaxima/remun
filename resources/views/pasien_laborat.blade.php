@extends('layouts.content')
@section('title',$c_ruang->ruang)

@section('content')
<div class="span3">
  <form method="GET" action="{{ route('pasien_laborat') }}" style="margin-bottom: 0;">
  @csrf
    <select class="form-control" name="id_ruang" style="width: 105%;" onchange="this.form.submit();">
      <option value="" style="font-style: italic;">== SEMUA RUANG ==</option>
      @foreach($ruang as $rng)
        <option value="{{ $rng->id }}" {{ $rng->id == $id_ruang? 'selected' : null }}>{{ $rng->ruang }}</option>
      @endforeach
    </select>    

    <input type="text" name="cari" class="form-control" value="{{ $cari }}" placeholder="Cari register/nama pasien" onchange="this.form.submit();" style="margin-top: -10px;">
  </form>      

  <div class="layanan">
    @foreach($pasien as $pas)            
      <form method="GET" action="{{ route('pasien_laborat') }}">
      @csrf
        <input type="hidden" name="id_pasien" value="{{ Crypt::encrypt($pas->id_pasien) }}">
        <input type="hidden" name="cari" value="{{ $cari }}">
        <input type="hidden" name="id_ruang" value="{{ $id_ruang }}">

        @if($pass)
          @if($pas->id_pasien == $pass->id)
            <button type="submit" class="btn btn-warning btn-block" style="padding: 3px 6px; margin-bottom: -17px;">
          @else
            <button type="submit" class="btn btn-block" style="padding: 3px 6px; margin-bottom: -17px;">
          @endif
        @else
          <button type="submit" class="btn btn-block" style="padding: 3px 6px; margin-bottom: -17px;">
        @endif
            <table width="100%" style="font-size: 12px; text-align: left; line-height: 13px;">
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
                <td>
                  {{ $pas->dpjp }}
                </td>
              </tr>
              <tr>
                <td valign="top">Ruang</td>
                <td valign="top">:</td>
                <td>
                  {{ $pas->ruang }}
                </td>
              </tr>
            </table>
        </button>
      </form>
    @endforeach
  </div>
</div>      
    
<div class="span9">
  <div class="navbar">
    <div class="navbar-inner">    
      <div class="row-fluid">
        <div class="span12">
          <div class="btn-group">
            @if($pass)
              <button type="button" class="btn btn-primary" title="Tambah Layanan Pasien" data-toggle="collapse" href="#tambah_layanan">
                LAYANAN
              </button>
            @endif

            @if($c_ruang->terima_pasien == 1)
              <button type="button" class="btn btn-primary" title="Layanan Pasien Luar" data-toggle="collapse" href="#pasien_luar">
                LAYANAN PASIEN LUAR
              </button>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>  

@if($pass)
  <div class="collapse" id="tambah_layanan">
    <div class="content" style="margin-bottom: 5px;">
      <form class="form-horizontal fprev" id="pasien_layanan_form" method="POST" action="{{ route('pasien_layanan_laborat') }}">
      @csrf
        <input type="hidden" name="id_pasien_ruang" value="{{ $pass->id_pasien_ruang }}">
        <input type="hidden" name="id_pasien" value="{{ $pass->id }}">
        <input type="hidden" name="id_pasien_jenis" id="id_pasien_jenis" value="{{ $pass->id_pasien_jenis }}">
        <input type="hidden" name="id_pasien_jenis_rawat" id="id_pasien_jenis_rawat" value="{{ $pass->id_pasien_jenis_rawat }}">
        <input type="hidden" name="id_ruang" value="{{ $pass->id_ruang }}">

        <div class="control-group">
          <label class="control-label span2">Jasa Layanan</label>
          <div class="controls span9">
            <select class="form-control" name="id_jasa" id="id_jasa" size="5" required autofocus>
              @foreach($jasa as $jas)
                <option value="{{ $jas->id_jasa }}">{{ strtoupper($jas->jasa) }}</option>
              @endforeach
            </select>
          </div>
        </div>

        @if(Auth::user()->id_ruang <> 52)
        <div class="control-group">
          <label class="control-label span2">DPJP</label>
          <div class="controls span9">
            <select class="form-control" name="id_dpjp" id="id_dpjp" size="5" required>
              @foreach($dpjp as $dok)
                <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
              @endforeach
            </select>
          </div>
        </div>          
        @endif

        <div id="tanggung">
          <div class="control-group">
            <label class="control-label span2">Penanggung Jawab</label>
            <div class="controls span9">
              <select class="form-control" name="id_tanggung" id="id_tanggung">
                <option value=""></option>
                @foreach($dpjp as $dok)
                  <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>          
        </div>

        <div class="control-group">
          <label class="control-label span2">Tarif</label>
          <div class="controls span2">
            <div class="input-prepend">
              <span class="add-on">Rp.</span>
              <input type="text" class="form-control nominal" name="tarif" required autocomplete="off">
            </div>
          </div>
        </div>

        <div class="control-group">
          <div class="span10 offset2">
            <div class="btn-group">
              <button type="submit" class="btn btn-primary bprev">SIMPAN</button>
              <button type="button" class="btn" data-toggle="collapse" href="#tambah_layanan" aria-expanded="false" aria-controls="tambah_layanan">BATAL</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>  
  @endif

  <div class="collapse" id="pasien_luar">
    <div class="content" style="margin-bottom: 5px;">
      <form class="form-horizontal fprev" method="POST" action="{{ route('pasien_luar_laborat') }}">
      @csrf

        <div class="control-group">
          <label class="control-label span2">Nama</label>
          <div class="controls span9">
            <input type="text" name="nama" class="form-control" required autofocus autocomplete="off">
          </div>
        </div>          

        <div class="control-group">
          <label class="control-label span2">Alamat</label>
          <div class="controls span9">
            <input type="text" name="alamat" class="form-control" autocomplete="off">
          </div>
        </div>          

        <div class="control-group">
          <label class="control-label span2">Umur</label>
          <div class="controls span1">
            <div class="input-append">
              <input type="number" name="umur_thn" step="1" class="form-control" required autocomplete="off">
              <span class="add-on">Thn.</span>
            </div>
          </div>            
          <div class="controls span1 offset1">
            <div class="input-append">
              <input type="number" name="umur_bln" step="1" class="form-control" autocomplete="off">
              <span class="add-on">Bln.</span>
            </div>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label span2">Kelamin</label>
          <div class="controls span3">
            <select class="form-control" name="id_kelamin" required size="2">
              <option value="1">LAKI-LAKI</option>
              <option value="2">PEREMPUAN</option>
            </select>
          </div>            
        </div>

        <div class="control-group">
          <label class="control-label span2">Jasa Layanan</label>
          <div class="controls span9">
            <select class="form-control" name="id_jasa" id="id_jasa_baru" required style="width: 102%;" size="4">
              @foreach($jasa as $jas)
                <option value="{{ $jas->id_jasa }}">{{ strtoupper($jas->jasa) }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label span2">DPJP</label>
          <div class="controls span9">
            <select class="form-control" name="id_dpjp" id="id_dpjp" required style="width: 102%;" size="3">
              @foreach($dpjp as $dok)
                <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
              @endforeach
            </select>
          </div>
        </div>          

        <div id="tanggung_baru">
          <div class="control-group">
            <label class="control-label span2">Penanggung Jawab</label>
            <div class="controls span9">
              <select class="form-control" name="id_tanggung" id="id_tanggung_baru" style="width: 102%;" size="5">
                @foreach($dpjp as $dok)
                  <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>          
        </div>

        <div class="control-group">
          <label class="control-label span2">Tarif</label>
          <div class="controls span2">
            <div class="input-prepend">
              <span class="add-on">Rp.</span>
              <input type="text" class="form-control nominal" name="tarif" required autocomplete="off">
            </div>
          </div>
        </div>

        <div class="control-group">
          <div class="span10 offset2">
            <div class="btn-group">
              <button type="submit" class="btn btn-primary bprev">SIMPAN</button>
              <button type="button" class="btn" data-toggle="collapse" href="#pasien_luar" aria-expanded="false" aria-controls="pasien_luar">BATAL</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  @if($pass)
  <div class="content">
    <table width="100%" style="font-size: 12px; line-height: 13px; margin-bottom: 5px;">
      <tr>
        <td width="100" valign="top">Nama Pasien</td>
        <td width="10" valign="top">:</td>
        <td width="40%" valign="top" style="font-weight: bold;">{{ strtoupper($pass->nama) }}</td>
        <td width="70" valign="top">Alamat</td>
        <td width="10" valign="top">:</td>
        <td valign="top">{{ strtoupper($pass->alamat) }}</td>
      </tr>            
      <tr>
        <td valign="top">No. Register</td>
        <td valign="top">:</td>
        <td valign="top">{{ strtoupper($pass->register) }}</td>
        <td valign="top">Umur</td>
        <td valign="top">:</td>
        <td valign="top">{{ strtoupper($pass->umur_thn) }} Thn.
          @if($pass->umur_bln)
            {{ strtoupper($pass->umur_bln) }} Bln.
          @endif
        </td>
      </tr>            
      <tr>
        <td valign="top">No. MR</td>
        <td valign="top">:</td>
        <td valign="top">{{ strtoupper($pass->no_mr) }}</td>
        <td valign="top">Jenis</td>
        <td valign="top">:</td>
        <td valign="top">PASIEN {{ strtoupper($pass->jenis_pasien) }}</td>
      </tr>            
      <tr>
        <td valign="top">DPJP</td>
        <td valign="top">:</td>
        <td valign="top">{{ strtoupper($pass->dpjp) }}</td>
        <td valign="top">Ruang</td>
        <td valign="top">:</td>
        <td valign="top">{{ strtoupper($pass->ruang) }}</td>
      </tr>            
    </table>
  
    <table width="100%" id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
      <thead>
        <th></th>
        <th style="text-align: center;">WAKTU</th>
        <th style="text-align: center;">PETUGAS</th>
        <th style="text-align: center;">MEDIS</th>
        <th style="text-align: center;">JASA</th>
        <th style="text-align: center;">TARIF</th>
      </thead>
      <tbody>
      @foreach($layanan as $lay)                    
        <tr>
          <td class="min">
            @if($lay->id_ruang_sub == Auth::user()->id_ruang)
            <a href="{{ route('pasien_layanan_hapus',Crypt::encrypt($lay->id)) }}" class="btn btn-info btn-mini" onclick="return confirm('Hapus layanan ?')">
              <i class="icon-trash"></i>
            </a>
            @endif
          </td>
          <td class="min" align="center">{{ $lay->waktu }}</td>
          <td>{{ $lay->petugas }}</td>
          <td>{{ $lay->dpjp }}</td>
          <td>{{ strtoupper($lay->jasa) }}</td>
          <td width="100" style="padding-right: 10px; text-align: right;">{{ number_format($lay->tarif,0) }}</td>
        </tr>
      @endforeach                
      </tbody>
    </table>
  </div>
</div>
@endif
@endsection

@section('script')
  <script type="text/javascript">
    window.onload=function() {
      document.getElementById('tanggung').style.display = 'none';
      document.getElementById('tanggung').style.visibility = 'hidden';
      document.getElementById('id_tanggung').required = false;

      document.getElementById('tanggung_baru').style.display = 'none';
      document.getElementById('tanggung_baru').style.visibility = 'hidden';
      document.getElementById('id_tanggung_baru').required = false;
    };

    $('#id_jasa').on('change',function(){
      $id_jasa                = $(this).val();
      $id_pasien_jenis        = document.getElementById('id_pasien_jenis').value;
      $id_pasien_jenis_rawat  = document.getElementById('id_pasien_jenis_rawat').value;

      $.ajax({
        type : 'get',
        url : '{{ route("cek_tanggung") }}',
        data: {'id_jasa': $id_jasa, 'id_pasien_jenis': $id_pasien_jenis, 'id_pasien_jenis_rawat': $id_pasien_jenis_rawat},
        success: function(data){
          if(data == 1){
            document.getElementById('tanggung').style.display = 'block';
            document.getElementById('tanggung').style.visibility = 'visible';
            document.getElementById('id_tanggung').required = true;
          } else {
            document.getElementById('tanggung').style.display = 'none';
            document.getElementById('tanggung').style.visibility = 'hidden';
            document.getElementById('id_tanggung').required = false;
          }
        }
      });
    });

    $('#id_jasa_baru').on('change',function(){
      $id_jasa = $(this).val();

      $.ajax({
        type : 'get',
        url : '{{ route("cek_tanggung") }}',
        data: {'id_jasa': $id_jasa,' id_pasien_jenis': 1, 'id_pasien_jenis_rawat': 1},
        success: function(data){
          if(data == 1){
            document.getElementById('tanggung_baru').style.display = 'block';
            document.getElementById('tanggung_baru').style.visibility = 'visible';
            document.getElementById('id_tanggung_baru').required = true;
          } else {
            document.getElementById('tanggung_baru').style.display = 'none';
            document.getElementById('tanggung_baru').style.visibility = 'hidden';
            document.getElementById('id_tanggung_baru').required = false;
          }
        }
      });
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#tabel').DataTable( {
        "paginate": false,
        "searching": false,
        "info": false,
        "sort":false,
      });
    });
  </script>  
@endsection