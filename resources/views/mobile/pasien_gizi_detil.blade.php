@extends('mobile.layouts.content')

@section('bawah')  
  <li><a href="{{ route('jasa_remun') }}"><i class="fa fa-heart"></i>Remunerasi</a></li>
  <li><a href="{{ route('pasien_gizi_transaksi') }}"><i class="fa fa-book"></i>Data Layanan</a></li>
@endsection

@section('content')
<div class="row isi">
  <div class="container" style="margin-top: 9vh;">
    <div class="card card-body user-data-card" style="padding: 1vh 2vw;">
      <table width="100%" style="font-size: 3vw; line-height: 2vh;">
        <tr>
          <td width="25%" valign="top">Nama Pasien</td>
          <td width="3%" valign="top">:</td>
          <td valign="top"><b>{{ strtoupper($pass->nama) }}</b> <span>({{ strtoupper($pass->jenis_pasien) }})</span></td>          
        </tr>
        <tr>
          <td valign="top">Alamat</td>
          <td valign="top">:</td>
          <td valign="top">{{ strtoupper($pass->alamat) }}</td>        
        </tr>            
        <tr>
          <td valign="top">Reg. / MR</td>
          <td valign="top">:</td>
          <td valign="top">{{ strtoupper($pass->register) }} / {{ strtoupper($pass->no_mr) }}</td>
        </tr>
        <tr>
          <td valign="top">Umur</td>
          <td valign="top">:</td>
          <td valign="top">{{ strtoupper($pass->umur_thn) }} Thn. {{ strtoupper($pass->umur_bln) }} Bln.</td>
        </tr>            
        <tr>          
          <td valign="top">R. Perawatan</td>
          <td valign="top">:</td>
          <td valign="top">{{ strtoupper($pass->ruang) }}</td>
        </tr>
        <tr>
          <td valign="top">DPJP</td>
          <td valign="top">:</td>
          <td valign="top">{{ $pass->dpjp }}</td>
        </tr>
        <tr>
          <td valign="top">Layanan</td>
          <td valign="top">:</td>
          <td valign="top">
            <form class="form-horizontal fprev" id="pasien_layanan_form" method="POST" action="{{ route('pasien_gizi_layanan') }}" style="margin-bottom: 0;">
          @csrf
            <input type="hidden" name="id_pasien_ruang" value="{{ $pass->id_pasien_ruang }}">
            <input type="hidden" name="id_pasien" value="{{ $pass->id }}">
            <input type="hidden" name="id_pasien_jenis" value="{{ $pass->id_pasien_jenis }}">
            <input type="hidden" name="id_pasien_jenis_rawat" value="{{ $pass->id_pasien_jenis_rawat }}">
            <input type="hidden" name="id_ruang" value="{{ $pass->id_ruang }}">

            <div class="input-group">
              <span class="input-group-text" id="basic-addon1">Rp.</span>
              <input type="text" class="form-control nominal" name="tarif" required class="form-control">
            </div>
            <button type="submit" class="btn btn-primary btn-sm bprev" style="margin-top: 1vh; margin-bottom: 0;">TAMBAHKAN</button>
            <a href="{{ route('pasien_gizi') }}" class="btn btn-primary btn-sm bprev" style="margin-top: 1vh; margin-bottom: 0;">KEMBALI</a>
          </form>
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>

<div class="row isi" style="padding-bottom: 10vh;">
  <div class="container">
    @foreach($layanan as $lay)
    <div class="card card-body user-data-card" style="padding: 1vh 2vw; margin-top: 1vh;">
      <table width="100%" style="font-size: 2.5vw;">
        <tr>
          <td width="20%">Waktu</td>
          <td width="3%">:</td>
          <td>{{ $lay->waktu }}</td>
          <td rowspan="4" valign="top" width="1%">
            <a href="{{ route('pasien_layanan_hapus',Crypt::encrypt($lay->id)) }}" class="btn btn-danger btn-sm" onclick="return confirm('Hapus jasa layanan ?')">
              <i class="fa fa-trash"></i>
            </a>
          </td>
        </tr>
        <tr>
          <td>Petugas</td>
          <td>:</td>
          <td>{{ $lay->nama }}</td>
        </tr>
        <tr>
          <td>R. Perawatan</td>
          <td>:</td>
          <td>{{ $lay->ruang }}</td>
        </tr>
        <tr>
          <td>Tarif</td>
          <td>:</td>
          <td style="font-weight: bold;">Rp. {{ number_format($lay->tarif,0) }}</td>
        </tr>
      </table>
    </div>
    @endforeach
  </div>
</div>
@endsection