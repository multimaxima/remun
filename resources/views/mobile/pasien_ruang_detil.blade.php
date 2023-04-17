@extends('mobile.layouts.content')

@section('bawah')
  @if($c_ruang->terima_pasien == 1)
  <li>
    <a href="#" data-bs-toggle="modal" data-bs-target="#pasien_baru" title="Pasien Masuk">
      <i class="fa fa-users"></i> Pasien Baru
    </a>
  </li>
  @else
  <li><a href="{{ route('jasa_remun') }}"><i class="fa fa-heart"></i>Remunerasi</a></li>
  @endif

  <li><a href="{{ route('pasien_ruang_data') }}"><i class="fa fa-book"></i>Data Layanan</a></li>
@endsection

@section('content')
<div class="row isi">
  <div class="container user-data-card" style="margin-top: 9vh;">
    <div class="card card-body" style="padding: 1vh 2vw;">
      <table width="100%" style="font-size: 2.5vw; line-height: 2vh;">
        <tr>
          <td width="20%" valign="top">Nama Pasien</td>
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
          <td valign="top">Tagihan</td>
          <td valign="top">:</td>        
          <td valign="top" style="font-weight: bold;">Rp. {{ number_format($total->tarif,0) }}</td>
        </tr>
        <tr>
          <td colspan="3">
            <div class="btn-group btn-group-sm" style="display: inline-flex; margin-top: 1vh;">
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
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>

<div class="row isi" style="margin-top: 1vh;">
  <div class="container user-data-card">
    <a href="{{ route('pasien_ruang') }}" class="btn btn-danger" title="Layanan Pasien" style="width: 15vw; padding: 1vh 0;">
      <i class="fa fa-angle-left" style="font-size: 5vw;"></i><br>
      <span style="font-size: 2vw;">KEMBALI</span>
    </a>
    @if($pass->id_ruang !== Auth::user()->id_ruang)
      <button class="btn btn-secondary" title="Layanan Pasien" data-bs-toggle="collapse" data-bs-target="#layanan_lain" aria-expanded="false" aria-controls="layanan_lain">
        <i class="fa fa-bookmark" style="font-size: 5vw;"></i><br>
        <span style="font-size: 2vw;">LAYANAN</span>
      </button>
    @else
      @if($pass->id_dpjp)
        <button class="btn btn-secondary" title="Layanan Pasien" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" style="width: 15vw; padding: 1vh 0;">
          <i class="fa fa-bookmark" style="font-size: 5vw;"></i><br>
          <span style="font-size: 2vw;">LAYANAN</span>
        </button>

        @if($pass->id_ruang == Auth::user()->id_ruang)
          <button class="btn btn-secondary" title="Pindah Ruang" data-bs-toggle="collapse" data-bs-target="#pindah_ruang" aria-expanded="false" aria-controls="pindah_ruang" style="width: 15vw; padding: 1vh 0;">
            <i class="fa fa-exchange" style="font-size: 5vw;"></i><br>
            <span style="font-size: 2vw;">PINDAH</span>
          </button>

          <button class="btn btn-secondary" title="Ganti DPJP" data-bs-toggle="collapse" data-bs-target="#ganti_dpjp" aria-expanded="false" aria-controls="ganti_dpjp" style="width: 15vw; padding: 1vh 0;">
            <i class="fa fa-user" style="font-size: 5vw;"></i><br>
            <span style="font-size: 2vw;">GANTI DPJP</span>
          </button>

          <button class="btn btn-secondary" title="Ubah Jenis" data-bs-toggle="collapse" data-bs-target="#ubah_status" aria-expanded="false" aria-controls="ubah_status" style="width: 15vw; padding: 1vh 0;">
            <i class="fa fa-flag" style="font-size: 5vw;"></i><br>
            <span style="font-size: 2vw;">UBAH JENIS</span>
          </button>
        @endif
      @else
        @if($pass->id_ruang == Auth::user()->id_ruang)
          <button class="btn btn-secondary" title="Tambah DPJP" data-bs-toggle="collapse" data-bs-target="#pasien_dpjp_baru" aria-expanded="false" aria-controls="pasien_dpjp_baru" style="width: 15vw; padding: 1vh 0;">
            <i class="fa fa-user" style="font-size: 5vw;"></i><br>
            <span style="font-size: 2vw;">DPJP</span>
          </button>
        @endif
      @endif

      @if($pass->id_ruang == Auth::user()->id_ruang)
        <button class="btn btn-secondary" title="Edit Data Pasien" data-bs-toggle="collapse" data-bs-target="#edit_pasien" aria-expanded="false" aria-controls="edit_pasien" style="width: 15vw; padding: 1vh 0;">
          <i class="fa fa-edit" style="font-size: 5vw;"></i><br>
          <span style="font-size: 2vw;">EDIT PASIEN</span>
        </button>
      @endif
    @endif
  </div>
</div>

<div class="collapse" id="edit_pasien" style="margin-top: 1vh;">
  <div class="row isi">
    <div class="container">
      <div class="card">
        <div class="card-body user-data-card" style="font-size: 3vw;">
          <form class="form-horizontal fprev" id="form_edit_pasien" method="POST" action="{{ route('pasien_edit_ruang') }}">
          @csrf
            <input type="hidden" name="id" value="{{ Crypt::encrypt($pass->id) }}">

            <div class="mb-2">
              <div class="title mb-1">Nama Pasien</div>
              <input type="text" name="nama" class="form-control" value="{{ $pass->nama }}">
            </div>

            <div class="mb-2">
              <div class="title mb-1">No. Register</div>
              <input type="text" name="register" class="form-control" value="{{ $pass->register }}" readonly>
            </div>
            
            <div class="mb-2">
              <div class="title mb-1">No. MR</div>
              <input type="text" name="no_mr" class="form-control" value="{{ $pass->no_mr }}">
            </div>

            <div class="mb-2">
              <div class="title mb-1">Alamat</div>
              <input type="text" name="alamat" class="form-control" value="{{ $pass->alamat }}">
            </div>

            <div class="mb-2">
              <div class="title mb-1">Umur</div>
              <div class="row">
                <div class="col-6">
                  <div class="input-group">
                    <input type="number" name="umur_thn" step="1" class="form-control" value="{{ $pass->umur_thn }}" required autocomplete="off">
                    <span class="input-group-text" id="basic-addon1">Thn.</span>
                  </div>
                </div>

                <div class="col-6">
                  <div class="input-group">
                    <input type="number" name="umur_bln" step="1" class="form-control" value="{{ $pass->umur_bln }}" autocomplete="off">
                    <span class="input-group-text" id="basic-addon1">Bln.</span>
                  </div>
                </div>
              </div>
            </div>            

            <div class="mb-2">
              <div class="title mb-1">Kelamin</div>
              <select class="form-control" name="id_kelamin" style="width: 104.5%;">
                <option value="1" {{ $pass->id_kelamin == '1'? 'selected' : null }}>LAKI-LAKI</option>
                <option value="2" {{ $pass->id_kelamin == '2'? 'selected' : null }}>PEREMPUAN</option>
              </select>
            </div>            
          </form>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary btn-sm bprev" form="form_edit_pasien">SIMPAN</button>
          <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="collapse" data-bs-target="#edit_pasien" aria-expanded="false" aria-controls="edit_pasien">BATAL</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="collapse" id="ubah_status" style="margin-top: 1vh;">
  <div class="row isi">
    <div class="container">
      <div class="card">
        <div class="card-body user-data-card" style="font-size: 3vw;">
          <form class="form-horizontal fprev" method="POST" id="form_ubah_status" action="{{ route('pasien_ubah_status') }}" onsubmit="return confirm('Ubah jenis pasien ?')">
          @csrf
            <input type="hidden" name="id" value="{{ $pass->id }}">
        
            <select class="form-control" name="jenis" size="10" style="height: 20vh;">
              @foreach($jenis as $jns)
                @if($pass->id_pasien_jenis !== $jns->id)
                <option value="{{ $jns->id }}" {{ $pass->id_pasien_jenis == $jns->id? 'selected' : null }}>{{ strtoupper($jns->jenis) }}</option>
                @endif
              @endforeach
            </select>
          </form>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary btn-sm bprev" id="form_ubah_status">UBAH</button>
          <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="collapse" data-bs-target="#ubah_status">BATAL</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="collapse" id="ganti_dpjp" style="margin-top: 1vh;">
  <div class="row isi">
    <div class="container">
      <div class="card">
        <div class="card-body user-data-card" style="font-size: 3vw;">
          <form class="form-horizontal fprev" id="form_ganti_dpjp" method="POST" action="{{ route('pasien_ganti_dpjp') }}" onsubmit="return confirm('Ganti DPJP pasien ?')">
          @csrf
            <input type="hidden" name="id_pasien_ruang" value="{{ $pass->id_pasien_ruang }}">
            <input type="hidden" name="id_pasien" value="{{ $pass->id }}">

            <select class="form-control" name="id_dpjp" size="15" style="height: 40vh;">
              @foreach($dpjp as $dok)
                @if($pass->id_dpjp !== $dok->id)
                <option value="{{ $dok->id }}" {{ $pass->id_dpjp == $dok->id? 'selected' : null }}>{{ $dok->nama }}</option>
                @endif
              @endforeach
            </select>
          </form>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary btn-sm bprev" form="form_ganti_dpjp">SIMPAN</button>
          <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="collapse" data-bs-target="#ganti_dpjp">BATAL</button>
        </div>
      </div>
    </div>
  </div>
</div>
      
<div class="collapse" id="pindah_ruang" style="margin-top: 1vh;">
  <div class="row isi">
    <div class="container">
      <div class="card">
        <div class="card-body user-data-card" style="font-size: 3vw;">
          <form class="form-horizontal fprev" id="form_pindah_ruang" method="POST" action="{{ route('pasien_pindah') }}" onsubmit="return confirm('Pindahkan pasien ?')">
          @csrf
            <input type="hidden" name="id_pasien_ruang" value="{{ $pass->id_pasien_ruang }}">
            <input type="hidden" name="id_pasien" value="{{ $pass->id }}">
            <input type="hidden" name="id_jenis" value="{{ $pass->id_pasien_jenis }}">

            <select class="form-control" name="id_ruang" size="15" required autofocus style="height: 40vh;">
              @foreach($d_ruang as $run)
                <option value="{{ $run->id }}">{{ strtoupper($run->ruang) }}</option>
              @endforeach
            </select>                
          </form>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary btn-sm bprev" form="form_pindah_ruang">PINDAH</button>
          <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="collapse" data-bs-target="#pindah_ruang">BATAL</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="collapse" id="collapseExample" style="margin-top: 1vh;">
  <div class="row isi">
    <div class="container">
      <div class="card">
        <div class="card-body user-data-card" style="font-size: 3vw;">
          <form class="form-horizontal fprev" method="POST" id="form_collapseExample" action="{{ route('pasien_layanan_multi') }}">
          @csrf
            <input type="hidden" name="id_pasien_ruang" value="{{ $pass->id_pasien_ruang }}">
            <input type="hidden" name="id_pasien" value="{{ $pass->id }}">
            <input type="hidden" name="id_pasien_jenis" value="{{ $pass->id_pasien_jenis }}">
            <input type="hidden" name="id_pasien_jenis_rawat" value="{{ $pass->id_pasien_jenis_rawat }}">
            
            <table width="100%" class="table table-hover table-striped" style="font-size: 2.5vw;">
              <thead>                
                <th>JASA</th>
                <th width="25%">TARIF (Rp.)</th>
              </thead>
              <tbody>
              @foreach($jasa as $jas)
                <tr>
                  <td>
                    {{ strtoupper($jas->jasa) }}
                    @if($jas->id_jasa == 2 || $jas->id_jasa == 3 || $jas->id_jasa == 4)
                    <br>
                    <select class="form-control select2" name="id_dpjp[]">
                      <option value=""></option>
                      @foreach($dpjp_lain as $dok)
                        <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
                      @endforeach
                    </select>
                    @else
                    <input type="hidden" name="id_dpjp[]" value="{{ $pass->id_dpjp }}">
                    @endif
                  </td>
                  <td style="padding: .5vh 0;">
                    <input type="hidden" name="id_jasa[]" value="{{ $jas->id_jasa }}">
                    <input type="text" style="text-align: right;" class="form-control nominal" name="tarif[]" autocomplete="off">
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>  
          </form> 
        </div>
        <div class="card-footer">         
          <button type="submit" class="btn btn-primary btn-sm bprev" form="form_collapseExample">SIMPAN</button>
          <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="collapse" data-bs-target="#collapseExample">BATAL</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="collapse" id="layanan_lain" style="margin-top: 1vh;">
  <div class="row isi">
    <div class="container">
      <div class="card">
        <div class="card-body user-data-card" style="font-size: 3vw;">
          <form class="form-horizontal fprev" id="form_layanan_lain" method="POST" action="{{ route('pasien_layanan_multi_lain') }}">
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

            <div class="mb-2">
              <div class="title mb-1">NAMA DPJP</div>
              <select class="form-control" name="id_dpjp_real" required autofocus>
                <option value=""></option>
                @foreach($dpjp as $dok)
                  <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
                @endforeach
              </select>
            </div>
            
            <table width="100%" class="table table-hover table-striped" style="margin-top: 1vh; font-size: 2.5vw;">
              <thead>                
                <th>JASA</th>
                <th width="25%">TARIF (Rp.)</th>
              </thead>
              <tbody>
              @foreach($jasa as $jas)
                <tr>
                  <td>
                    {{ strtoupper($jas->jasa) }}
                    @if($jas->id_jasa == 2 || $jas->id_jasa == 3 || $jas->id_jasa == 4)
                    <br>
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
                  <td style="padding: .5vh 0;">
                    <input type="hidden" name="id_jasa[]" value="{{ $jas->id_jasa }}">
                    <input type="text" class="form-control nominal" name="tarif[]" style="text-align: right;" autocomplete="off">
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </form>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary btn-sm bprev" form="form_layanan_lain">SIMPAN</button>
          <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="collapse" data-bs-target="#layanan_lain">BATAL</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row isi">
  <div class="container user-data-card" style="padding-bottom: 9vh;">
    @foreach($ruang as $rng)
    <div class="card card-body" style="padding: 1vh 2vw; margin-top: 1vh; line-height: 2vh;">
      <table width="100%" style="font-size: 2.5vw;">
        <tr>
          <td width="20%">Waktu</td>
          <td width="3%">:</td>
          <td>{{ $rng->waktu }}</td>
          <td rowspan="6" width="1%" valign="top">
            @if($rng->id_ruang_sub == Auth::user()->id_ruang)
            <a href="{{ route('pasien_layanan_hapus',Crypt::encrypt($rng->id)) }}" class="btn btn-danger btn-sm" onclick="return confirm('Hapus layanan ?')" title="Hapus Layanan" style="margin-top: 1vh;">
              <i class="fa fa-trash"></i>
            </a>
            @endif
          </td>
        </tr>
        <tr>
          <td>R. Perawatan</td>
          <td>:</td>
          <td>{{ strtoupper($rng->ruang) }}</td>
        </tr>
        <tr>
          <td>R. Tindakan</td>
          <td>:</td>
          <td>{{ strtoupper($rng->ruang_sub) }}</td>
        </tr>
        <tr>
          <td>Nama Dokter</td>
          <td>:</td>
          <td>{{ $rng->nama }}</td>
        </tr>
        <tr>
          <td>Jasa</td>
          <td>:</td>
          <td>
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
        </tr>
        <tr>
          <td>Tarif</td>
          <td>:</td>
          <td style="font-weight: bold;">Rp. {{ number_format($rng->tarif,0) }}</td>
        </tr>
      </table>      
    </div>
    @endforeach
  </div>
</div>

<div class="modal fade" id="pasien_baru" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <label class="modal-title" style="font-size: 3.5vw; font-weight: bold;">PASIEN BARU</label>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal container-fluid fprev" id="pasien_baru_form" method="POST" action="{{ route('pasien_baru') }}">
        @csrf
          @if($c_ruang->jalan == 1)
            <input type="hidden" name="id_pasien_jenis_rawat" value="1">
          @endif

          @if($c_ruang->inap == 1)
            <input type="hidden" name="id_pasien_jenis_rawat" value="2">
          @endif

          <div class="mb-1">
            <div class="title mb-1">Nama Pasien</div>
            <input type="text" class="form-control" name="nama" required autofocus autocomplete="off">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Alamat</div>
            <input type="text" class="form-control" name="alamat">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Umur</div>
            <div class="row">
              <div class="col-6">
                <div class="input-group">
                  <input type="number" name="umur_thn" step="1" class="form-control" required autocomplete="off">
                  <span class="input-group-text" id="basic-addon1">Thn.</span>
                </div>
              </div>

              <div class="col-6">
                <div class="input-group">
                  <input type="number" name="umur_bln" step="1" class="form-control" autocomplete="off">
                  <span class="input-group-text" id="basic-addon1">Bln.</span>
                </div>
              </div>
            </div>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Kelamin</div>
            <select class="form-control" name="id_kelamin" required>
              <option value="1">LAKI-LAKI</option>
              <option value="2">PEREMPUAN</option>
            </select>
          </div>            

          <div class="mb-1">
            <div class="title mb-1">Nomor MR</div>
            <input type="text" class="form-control" name="no_mr" required autocomplete="off">
          </div>

          <div class="mb-1">
            <div class="title mb-1">Jenis Pasien</div>
            <select class="form-control" name="id_pasien_jenis" required>
              <option value=""></option>
              @foreach($jenis as $jns)
                <option value="{{ $jns->id }}">{{ strtoupper($jns->jenis) }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">DPJP</div>
            <select class="form-control" size="5" name="id_dpjp" required style="height: 15vh;">
              @foreach($dpjp as $dok)
                <option value="{{ $dok->id }}">{{ $dok->nama }}</option>
              @endforeach
            </select>
          </div>

          <div class="span12" style="margin-top: 5px;">
            <div class="alert alert-danger bg-danger text-white" role="alert" style="padding: 10px; font-size: 2.5vw;">
              * <b><i>Pastikan</i></b> bahwa data pasien yang Anda entri sudah benar !!!
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">    
        <div class="btn-group btn-group-sm pull-right">
          <button type="submit" form="pasien_baru_form" class="btn btn-secondary bprev">SIMPAN</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">BATAL</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    function myFunction() {
      document.getElementById("myDropdown").classList.toggle("show");
    }

    window.onclick = function(event) {
      if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
      }
    }
  </script>
@endsection