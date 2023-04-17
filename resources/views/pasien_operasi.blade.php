@extends('layouts.content')
@section('title','Layanan '.$c_ruang->ruang)

@section('content')
<div class="span3">
  <form method="GET" action="{{ route('pasien_operasi') }}" style="margin-bottom: 0px;">
  @csrf
    <select class="form-control" name="id_ruang" onchange="this.form.submit();" style="width: 105%;">
      <option value="" style="font-style: italic;">SEMUA RUANG</option>
      @foreach($ruang as $rng)
        <option value="{{ $rng->id }}" {{ $id_ruang == $rng->id? 'selected' : null }}>{{ $rng->ruang }}</option>
      @endforeach
    </select>

    <input type="text" name="cari" class="form-control" value="{{ $cari }}" placeholder="Cari register/nama pasien" onchange="this.form.submit();" style="margin-top: -10px;">
  </form>      

  <div class="layanan">
    @foreach($pasien as $pas)            
      <form method="GET" action="{{ route('pasien_operasi') }}">
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

@if($pass)
<div class="span9">
  <div class="navbar">
    <div class="navbar-inner">    
      <div class="row-fluid">
        <div class="span12">
          <div class="btn-group">
            <button class="btn btn-primary" title="Layanan Pasien" data-toggle="collapse" href="#pasien_layanan">
              LAYANAN
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="collapse" id="pasien_layanan">
    <div class="content" style="margin-bottom: 5px;">
      <form class="form-horizontal container-fluid fprev" id="pasien_layanan_form" method="POST" action="{{ route('pasien_layanan_operasi') }}">
      @csrf
        <input type="hidden" name="id_pasien_ruang" value="{{ $pass->id_pasien_ruang }}">
        <input type="hidden" name="id_pasien" value="{{ $pass->id }}">
        <input type="hidden" name="id_pasien_jenis" id="id_pasien_jenis" value="{{ $pass->id_pasien_jenis }}">
        <input type="hidden" name="id_pasien_jenis_rawat" id="id_pasien_jenis_rawat" value="{{ $pass->id_pasien_jenis_rawat }}">
        <input type="hidden" name="id_ruang" value="{{ $pass->id_ruang }}">

        <div class="control-group">
          <label class="control-label span2">Jasa Layanan</label>
          <div class="controls span10">
            <select class="form-control" name="id_jasa" id="id_jasa" size="5" required autofocus>
              @foreach($jasa as $jas)
                <option value="{{ $jas->id_jasa }}">{{ strtoupper($jas->jasa) }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label span2">Operator</label>
          <div class="controls span10">
            <select class="form-control" name="id_operator" id="id_operator" size="5" required>
              @foreach($operator as $opr)
                <option value="{{ $opr->id }}">{{ $opr->nama }}</option>
              @endforeach
            </select>
          </div>
        </div>          

        <div id="anastesi">
          <div class="control-group">
            <label class="control-label span2">Anastesi</label>
            <div class="controls span10">
              <select class="form-control" name="id_anastesi" id="id_anastesi" size="5">
                @foreach($anastesi as $dok)
                  <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div id="pendamping">
          <div class="control-group">
            <label class="control-label span2">Spesialis Pend.</label>
            <div class="controls span10">
              <select class="form-control" name="id_pendamping" id="id_pendamping" size="5">
                @foreach($pendamping as $dok)
                  <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label span2">Tarif</label>
          <div class="controls span4">
            <div class="input-prepend">
              <span class="add-on">Rp.</span>
              <input type="text" class="form-control nominal" name="tarif" required>
            </div>
          </div>
        </div>

        <div class="control-group">
          <div class="controls span10 offset2">
            <div class="btn-group">
              <button type="submit" class="btn btn-primary bprev">SIMPAN</button>
              <button type="button" class="btn" data-toggle="collapse" href="#pasien_layanan">BATAL</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
      
  <div class="content">
    <table width="100%" style="text-align: left; font-size: 12px; line-height: 13px; margin-bottom: 5px;">
      <tr>
        <td width="100" valign="top">Nama Pasien</td>
        <td width="10" valign="top">:</td>
        <td width="40%" valign="top">{{ strtoupper($pass->nama) }}</td>
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
        <td valign="top">{{ strtoupper($pass->umur_thn) }} Thn. {{ strtoupper($pass->umur_bln) }} Bln.</td>
      </tr>            
      <tr>
        <td valign="top">No. MR</td>
        <td valign="top">:</td>
        <td valign="top">{{ strtoupper($pass->no_mr) }}</td>
        <td valign="top">Jenis</td>
        <td valign="top">:</td>
        <td valign="top">{{ strtoupper($pass->jenis_pasien) }}</td>
      </tr>            
    </table>
  
    <table width="100%" id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 12px;">
      <thead>
        <th></th>
        <th>WAKTU</th>
        <th>JASA</th>
        <th>OPERATOR</th>
        <th>TARIF</th>
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
          <td>{{ strtoupper($lay->jasa) }}</td>
          <td>{{ $lay->operator }}</td>
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
      document.getElementById('pendamping').style.display = 'none';
      document.getElementById('pendamping').style.visibility = 'hidden';
      document.getElementById('id_pendamping').required = false;
      document.getElementById('anastesi').style.display = 'none';
      document.getElementById('anastesi').style.visibility = 'hidden';
      document.getElementById('id_anastesi').required = false;
    };

    $('#id_jasa').on('change',function(){
      $id_jasa                = $(this).val();
      $id_pasien_jenis        = document.getElementById('id_pasien_jenis').value;
      $id_pasien_jenis_rawat  = document.getElementById('id_pasien_jenis_rawat').value;

      $.ajax({
        type : 'get',
        url : '{{ route("cek_anastesi") }}',
        data: {'id_jasa': $id_jasa, 'id_pasien_jenis': $id_pasien_jenis, 'id_pasien_jenis_rawat': $id_pasien_jenis_rawat},
        success: function(data){
          if(data == 1){
            document.getElementById('anastesi').style.display = 'block';
            document.getElementById('anastesi').style.visibility = 'visible';
            document.getElementById('id_anastesi').required = true;
          } else {
            document.getElementById('anastesi').style.display = 'none';
            document.getElementById('anastesi').style.visibility = 'hidden';
            document.getElementById('id_anastesi').required = false;
          }
        }
      });

      $.ajax({
        type : 'get',
        url : '{{ route("cek_pendamping") }}',
        data: {'id_jasa': $id_jasa, 'id_pasien_jenis': $id_pasien_jenis, 'id_pasien_jenis_rawat': $id_pasien_jenis_rawat},
        success: function(data){
          if(data == 1){
            document.getElementById('pendamping').style.display = 'block';
            document.getElementById('pendamping').style.visibility = 'visible';
            document.getElementById('id_pendamping').required = true;
          } else {
            document.getElementById('pendamping').style.display = 'none';
            document.getElementById('pendamping').style.visibility = 'hidden';
            document.getElementById('id_pendamping').required = false;
          }
        }
      });
    });
  </script>

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
        "sort": false,
      });
    });
  </script>  
@endsection