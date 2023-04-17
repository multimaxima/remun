@extends('layouts.content')
@section('title','Layanan '.$c_ruang->ruang)

@section('content')
<div class="span3">
  <form method="GET" action="{{ route('pasien_apotik') }}" style="margin-bottom: 0px;">
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
      <form method="GET" action="{{ route('pasien_apotik') }}">
      @csrf
        <input type="hidden" name="id_pasien" value="{{ Crypt::encrypt($pas->id_pasien) }}">               
        <input type="hidden" name="cari" value="{{ $cari }}">
        <input type="hidden" name="id_ruang" value="{{ $id_ruang }}">

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
            <td valign="top">Ruang</td>
            <td valign="top">:</td>
            <td>
              {{ strtoupper($pas->ruang) }}
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
              <button type="button" class="btn btn-primary" title="Tambah Layanan Pasien" data-toggle="collapse" href="#collapseExample">
                LAYANAN
              </button>
            @endif
            <button type="button" class="btn btn-primary" title="Transaksi Non Pasien" data-toggle="modal" data-target="#pasien_layanan_non">
              LAYANAN PASIEN LUAR
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if($pass)
    <div class="collapse" id="collapseExample">
      <div class="content" style="margin-bottom: 5px;">
        <form class="form-horizontal fprev span6" id="pasien_layanan_form" method="POST" action="{{ route('pasien_layanan_apotik') }}">
        @csrf
          <input type="hidden" name="id_pasien_ruang" value="{{ $pass->id_pasien_ruang }}">
          <input type="hidden" name="id_pasien" value="{{ $pass->id }}">
          <input type="hidden" name="id_pasien_jenis" value="{{ $pass->id_pasien_jenis }}">
          <input type="hidden" name="id_pasien_jenis_rawat" value="{{ $pass->id_pasien_jenis_rawat }}">
          <input type="hidden" name="id_ruang" value="{{ $pass->id_ruang }}">

          <table width="100%" class="table table-hover table-striped" style="font-size: 13px;">
            <thead>                
              <th>JASA</th>
              <th width="30%">TARIF (Rp.)</th>
            </thead>
            <tbody>
              @foreach($jasa as $jas)
              <tr>
                <td style="font-size: 14px;">{{ strtoupper($jas->jasa) }}</td>
                <td style="padding-right: 20px;">
                  <input type="hidden" name="id_jasa[]" value="{{ $jas->id }}">
                  <input type="text" class="form-control nominal" name="tarif[]" autocomplete="off">
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

  <div class="content">
    <table width="100%" style="font-size: 12px; line-height: 13px; margin-bottom: 10px;">
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
        <td valign="top">{{ $pass->dpjp }}</td>
        <td valign="top">Ruang</td>
        <td valign="top">:</td>
        <td valign="top">{{ strtoupper($pass->ruang) }}</td>
      </tr>                     
    </table>
  
    <table width="100%" id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
      <thead>
        <th></th>
        <th>WAKTU</th>
        <th>PETUGAS ENTRI</th>
        <th>RUANG PERAWATAN</th>
        <th>JASA</th>
        <th style="text-align: right;">TARIF</th>
      </thead>
      <tbody>
      @foreach($layanan as $lay)
        <tr>
          <td class="min">
            @if($lay->id_ruang_sub == Auth::user()->id_ruang)
            <a href="{{ route('pasien_layanan_hapus',Crypt::encrypt($lay->id)) }}" class="btn btn-info btn-mini" onclick="return confirm('Hapus jasa layanan ?')">
              <i class="icon-trash"></i>
            </a>
            @endif
          </td>
          <td class="min">{{ $lay->waktu }}</td>
          <td>{{ $lay->nama }}</td>
          <td>{{ $lay->ruang }}</td>
          <td>{{ $lay->jasa }}</td>
          <td width="100" style="padding-right: 10px; text-align: right;">{{ number_format($lay->tarif,0) }}</td>
        </tr>
      @endforeach                
      </tbody>
    </table>
  </div>
@endif                             
</div>

<div class="modal hide fade" id="pasien_layanan_non">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Layanan Pasien Luar</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="pasien_layanan_non_form" method="POST" action="{{ route('pasien_apotik_non') }}">
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
        <label class="control-label span2">Kelamin</label>
        <div class="controls span9">
          <select class="form-control" name="id_kelamin" required style="width: 104%;" size="2">
            <option value="1">LAKI-LAKI</option>
            <option value="2">PEREMPUAN</option>
          </select>
        </div>            
      </div>

      <div class="control-group">
        <label class="control-label span2">Layanan</label>
        <div class="controls span9">
          <select class="form-control" name="id_jasa" required style="width: 104%;" size="2">
            @foreach($jasa as $jas)
            <option value="{{ $jas->id }}">{{ strtoupper($jas->jasa) }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span2">Tarif</label>
        <div class="controls span4">
          <div class="input-prepend">
            <span class="add-on">Rp.</span>
            <input type="text" class="form-control nominal" name="tarif" required autocomplete="off">
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="modal-footer">  
    <div class="btn-group">              
      <button type="submit" form="pasien_layanan_non_form" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('#tabel').DataTable( {
        "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": 4
        }],
        "paginate": false,
        "searching": false,
        "info": false,
        "sort":false,
      });
    });
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