@extends('layouts.content')
@section('title','Daftar Pasien')

@section('content')
<div class="span3">
  <form method="GET" action="{{ route('pasien_gizi') }}" style="margin-bottom: 0;">
  @csrf
    <select class="form-control" name="id_ruang" style="width: 105%;" onchange="this.form.submit();">
      <option value="" style="font-style: italic;">SEMUA RUANG</option>
      @foreach($ruang as $rng)
        <option value="{{ $rng->id }}" {{ $id_ruang == $rng->id? 'selected' : null }}>{{ $rng->ruang }}</option>
      @endforeach
    </select>

    <input type="text" name="cari" class="form-control" value="{{ $cari }}" placeholder="Cari register/nama pasien" onchange="this.form.submit();" style="margin-top: -10px;">
  </form>            

  <div class="layanan" style="max-height: 73vh; ">
    @foreach($pasien as $pas)            
      <form method="GET" action="{{ route('pasien_gizi') }}">
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
                {{ strtoupper($pas->jenis_pasien) }}
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
  <div class="content">  
    <table width="100%" style="font-size: 13px; line-height: 13px;">
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
        <td valign="top">
          {{ strtoupper($pass->umur_thn) }} Thn.
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
        <td valign="top" style="font-weight: bold;">{{ $pass->dpjp }}</td>
        <td valign="top">Ruang</td>
        <td valign="top">:</td>
        <td valign="top" style="font-weight: bold;">{{ strtoupper($pass->ruang) }}</td>
      </tr>            
      <tr>
        <td valign="top">Tarif Gizi</td>
        <td valign="top">:</td>
        <td valign="top">
          <form class="form-horizontal fprev" id="pasien_layanan_form" method="POST" action="{{ route('pasien_gizi_layanan') }}" style="margin-bottom: 0;">
          @csrf
            <input type="hidden" name="id_pasien_ruang" value="{{ $pass->id_pasien_ruang }}">
            <input type="hidden" name="id_pasien" value="{{ $pass->id }}">

            <div class="input-prepend">
              <span class="add-on">Rp.</span>
              <input type="text" class="nominal" name="tarif" required>
            </div><br>
            <button type="submit" class="btn btn-primary bprev" style="margin-top: 5px; margin-bottom: 0;">TAMBAHKAN</button>
          </form>
        </td>
        <td valign="top"></td>
        <td valign="top"></td>
        <td valign="top"></td>
      </tr>
    </table>
  </div>

  <div class="content">
    <table width="100%" id="tabel" class="table table-hover table-striped table-bordered" style="font-size: 13px;">
      <thead>
        <th></th>
        <th>WAKTU</th>
        <th>PETUGAS</th>
        <th>RUANG PERAWATAN</th>
        <th>TARIF</th>
      </thead>
      <tbody>
      @foreach($layanan as $lay)
        <tr>
          <td class="min">
            <a href="{{ route('pasien_layanan_hapus',Crypt::encrypt($lay->id)) }}" class="btn btn-info btn-mini" onclick="return confirm('Hapus jasa layanan ?')">
              <i class="icon-trash"></i>
            </a>
          </td>
          <td class="min">{{ $lay->waktu }}</td>
          <td>{{ $lay->nama }}</td>
          <td>{{ $lay->ruang }}</td>
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
    $(document).ready(function() {
      $('#tabel').DataTable( {
        "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": 0
        }],
        "paginate": false,
        "searching": false,
        "info": false,
        "sort": false,
      });
    });
  </script>  
@endsection