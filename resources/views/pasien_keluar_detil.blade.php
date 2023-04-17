@extends('layouts.content')
@section('title','Detil Data Pasien Keluar')

@section('judul')
  <form method="GET" action="{{ route('pasien_keluar') }}">
  @csrf
    <input type="hidden" name="awal" value="{{ $awal }}">
    <input type="hidden" name="akhir" value="{{ $akhir }}">
    <input type="hidden" name="jns" value="{{ $jns }}">
    <button type="submit" class="btn btn-danger float-right">KEMBALI</button>
  </form>
  <h4 class="page-title"> <i class="dripicons-user-id"></i> @yield('title')</h4>
@endsection

@section('style')
  <style type="text/css">
    td {
      padding: 3px 10px;
    }
  </style>
@endsection

@section('content')
<div class="wrapper">
  <div class="col-12" style="padding: 0 50px;">
    <div class="card m-b-30 konten">
      <div class="card-body">
        <table width="100%">
          <tr>
            <td width="100">Nama Pasien</td>
            <td width="10">:</td>
            <td width="40%">{{ $pasien->nama }}</td>
            <td width="100">Umur</td>
            <td width="10">:</td>
            <td>{{ $pasien->umur }}</td>
          </tr>
          <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $pasien->alamat }}</td>
            <td>Jenis</td>
            <td>:</td>
            <td>{{ $pasien->jenis_pasien }}</td>
          </tr>
          <tr>
            <td>No. MR</td>
            <td>:</td>
            <td>{{ $pasien->no_mr }}</td>
            <td>Tagihan</td>
            <td>:</td>
            <td>Rp. {{ number_format($pasien->tagihan,0) }}</td>
          </tr>
          <tr>
            <td>Register</td>
            <td>:</td>
            <td>{{ $pasien->register }}</td>
            <td>DPJP</td>
            <td>:</td>
            <td>{{ $pasien->dpjp }}</td>
          </tr>
        </table>
      </div>      
    </div>
  </div>
</div>

<div class="wrapper" style="margin-top: -20px;">
  <div class="col-12" style="padding: 0 50px;">
    <div class="card m-b-30 konten">
      <div class="card-body">
        <table width="100%" class="table-bordered">
          <thead>
            <th>MASUK</th>
            <th>RUANG</th>
            <th>DPJP</th>
          </thead>
          <tbody>
            @foreach($ruang as $ruang)
              <tr>                
                <td>{{ $ruang->masuk }}</td>
                <td>{{ $ruang->ruang }}</td>
                <td>{{ $ruang->dpjp }}</td>
              </tr>
              <tr>
                <td colspan="3" style="padding-left: 20px;">
              @foreach($layanan as $lay)
                @if($lay->id_pasien_ruang == $ruang->id)
                <table width="100%">
                <tr>
                  <td width="15%">{{ $lay->jasa }}</td>
                  <td align="right" width="100">{{ number_format($lay->tarif,0) }}</td>
                  <td>
                    <table width="100%">
                      @foreach($layanan_1 as $lay_1)
                        @if($lay_1->id_layanan == $lay->id)
                        <tr>
                          <td width="20%">{{ $lay_1->nama }}</td>
                          <td align="right" width="100">{{ number_format($lay_1->nominal,0) }}</td>
                          <td>
                            <table width="100%">
                              @foreach($layanan_2 as $lay_2)
                                @if($lay_2->id_layanan_1 == $lay_1->id)
                                <tr>
                                  <td width="25%">{{ $lay_2->nama }}</td>
                                  <td align="right" width="100">{{ number_format($lay_2->nominal,0) }}</td>
                                </tr>
                                @endif
                              @endforeach
                            </table>
                          </td>
                        </tr>
                        @endif
                      @endforeach
                    </table>
                  </td>
                </tr>
                </table>
                @endif
              @endforeach
              </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>      
    </div>
  </div>
</div>
@endsection