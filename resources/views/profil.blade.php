@extends('layouts.content')
@section('title','Profil')

@section('content')
<div class="navbar" style="margin-bottom:5px;">
  <div class="navbar-inner">
    <ul class="nav">
      <li class="active"><a href="#">Data Profil</a></li>
      <li><a href="{{ route('profil_password_form') }}">Ganti Password</a></li>
    </ul>
    <button type="submit" form="data" class="btn btn-primary pull-right">SIMPAN</button>
  </div>
</div>

<div class="content" style="max-height: 74vh;">
  @include('layouts.pesan')
  <form class="form-horizontal" id="data" method="POST" action="{{ route('profil_simpan') }}" files="true" enctype="multipart/form-data">
  @csrf

    <div class="row-fluid">
      <div class="span2">
        @if(Auth::user()->foto)
          <img src="/{{ Auth::user()->foto }}" width="100%">
        @else
          <img src="/images/noimage.jpg" width="100%">
        @endif
        <input type="file" name="foto" style="margin-top: 10px;" accept=".jpg">
      </div>

      <div class="span10">
        <ul class="nav nav-tabs" id="myTab">
          <li class="active"><a href="#home1" data-toggle="tab">PROFIL SAYA</a></li>
          <li><a href="#home2" data-toggle="tab">INFORMASI PEGAWAI</a></li>
          <li><a href="#home3" data-toggle="tab">INDEKS & JASA KARYAWAN</a></li>
        </ul>
 
        <div class="tab-content">
          <div class="tab-pane active" id="home1">
            <div class="container-fluid">
              <div class="control-group">
                <label for="nama" class="control-label span2">Nama</label>
                <div class="controls span10">
                  <input type="text" id="nama" name="nama" class="form-control" required autofocus value="{{ Auth::user()->nama }}">
                </div>
              </div>

              <div class="control-group">
                <label for="gelar_depan" class="control-label span2">Gelar Depan</label>
                <div class="controls span4">
                  <input type="text" id="gelar_depan" name="gelar_depan" class="form-control" value="{{ Auth::user()->gelar_depan }}">
                </div>

                <label for="gelar_belakang" class="control-label span2">Gelar Belakang</label>
                <div class="controls span4">
                  <input type="text" id="gelar_belakang" name="gelar_belakang" class="form-control" value="{{ Auth::user()->gelar_belakang }}">
                </div>
              </div>

              <div class="control-group">
                <label for="nip" class="control-label span2">NIP</label>
                <div class="controls span4">
                  <input type="text" id="nip" name="nip" class="form-control" value="{{ Auth::user()->nip }}">
                </div>
              </div>              

              <div class="control-group">
                <label for="alamat" class="control-label span2">Alamat</label>
                <div class="controls span10">
                  <input type="text" id="alamat" name="alamat" class="form-control" value="{{ Auth::user()->alamat }}" required>
                </div>
              </div>

              <div class="control-group">
                <label for="dusun" class="control-label span2">Dusun</label>
                <div class="controls span4">
                  <input type="text" id="dusun" name="dusun" class="form-control" value="{{ Auth::user()->dusun }}">
                </div>
              
                <label for="rt" class="control-label span2">RT</label>
                <div class="controls span1">
                  <input type="text" id="rt" name="rt" class="form-control" value="{{ Auth::user()->rt }}">
                </div>

                <label for="rw" class="control-label span1">RW</label>
                <div class="controls span1">
                  <input type="text" id="rw" name="rw" class="form-control" value="{{ Auth::user()->rw }}">
                </div>
              </div>

              <div class="control-group">            
                <label for="no_prop" class="control-label span2">Propinsi</label>
                <div class="controls span4">
                  <select class="form-control" name="no_prop" id="no_prop" style="width: 104.5%;" required></select>
                </div>

                <label for="no_kab" class="control-label span2">Kota</label>
                <div class="controls span4">
                  <select class="form-control" name="no_kab" id="no_kab" style="width: 104.5%;" required></select>
                </div>
              </div>

              <div class="control-group">            
                <label for="no_kec" class="control-label span2">Kecamatan</label>
                <div class="controls span4">
                  <select class="form-control" name="no_kec" id="no_kec" style="width: 104.5%;" required></select>
                </div>

                <label for="no_kel" class="control-label span2">Desa</label>
                <div class="controls span4">
                  <select class="form-control" name="no_kel" id="no_kel" style="width: 104.5%;" required></select>
                </div>
              </div>

              <div class="control-group">
                <label for="temp_lahir" class="control-label span2">Tempat Lahir</label>
                <div class="controls span4">
                  <input type="text" id="temp_lahir" name="temp_lahir" class="form-control" value="{{ Auth::user()->temp_lahir }}">
                </div>

                <label for="tgl_lahir" class="control-label span2">Tanggal Lahir</label>
                <div class="controls span4">
                  <input type="date" id="tgl_lahir" name="tgl_lahir" class="form-control" value="{{ Auth::user()->tgl_lahir }}">
                </div>
              </div>

              <div class="control-group">
                <label for="id_kelamin" class="control-label span2">Jenis Kelamin</label>
                <div class="controls span4">
                  <select class="form-control" id="id_kelamin" name="id_kelamin" style="width: 104.5%;" required >
                    <option value="1" {{ Auth::user()->id_kelamin == '1'? 'selected' : null }}>LAKI-LAKI</option>
                    <option value="2" {{ Auth::user()->id_kelamin == '2'? 'selected' : null }}>PEREMPUAN</option>
                  </select>
                </div>
              </div>

              <div class="control-group">
                <label for="hp" class="control-label span2">HP</label>
                <div class="controls span4">
                  <input type="text" id="hp" name="hp" class="form-control" value="{{ Auth::user()->hp }}" required >
                </div>
            
                <label for="telp" class="control-label span2">Telepon</label>
                <div class="controls span4">
                  <input type="text" id="telp" name="telp" class="form-control" value="{{ Auth::user()->telp }}">
                </div>
              </div>

              <div class="control-group">
                <label for="email" class="control-label span2">Email</label>
                <div class="controls span4">
                  <input type="text" id="email" name="email" class="form-control" value="{{ Auth::user()->email }}">
                </div>

                <label for="npwp" class="control-label span2">NPWP</label>
                <div class="controls span4">
                  <input type="text" id="npwp" name="npwp" class="form-control" value="{{ Auth::user()->npwp }}">
                </div>
              </div>

              <div class="control-group">
                <label for="bank" class="control-label span2">Bank</label>
                <div class="controls span4">
                  <select class="form-control" id="bank" name="bank" style="width: 104.5%;">
                    <option value=""></option>
                    @foreach($bank as $bank)
                      <option value="{{ $bank->bank }}" {{ Auth::user()->bank == $bank->bank? 'selected' : null }}>{{ $bank->bank }}</option>
                    @endforeach
                  </select>
                </div>

                <label for="rekening" class="control-label span2">Nomor Rekening</label>
                <div class="controls span4">
                  <input type="text" id="rekening" name="rekening" class="form-control" value="{{ Auth::user()->rekening }}">
                </div>
              </div>

              <div class="control-group">
                <label for="username" class="control-label span2">Username</label>
                <div class="controls span4">
                  <input type="text" id="username" name="username" class="form-control" value="{{ Auth::user()->username }}" readonly>
                </div>
              

              @if($c_akses->id == 1)             
              <label for="username" class="control-label span2"></label>             
                <div class="controls span4">
                  <a href="#" data-toggle="modal" data-target="#modal_edit_jasa">
                    ..
                  </a>
                </div>                
              @endif
              </div>
            </div>
          </div>

          <div class="tab-pane" id="home2">
            <div class="container-fluid">
              <div class="control-group">
                <label for="mulai_kerja" class="control-label span2">Mulai Kerja</label>
                <div class="controls span4">
                  <input type="date" id="mulai_kerja" name="mulai_kerja" class="form-control" value="{{ Auth::user()->mulai_kerja }}" readonly>
                </div>

                <label for="id_status" class="control-label span2">Status</label>
                <div class="controls span4">
                  <select class="form-control" id="id_status" name="id_status" disabled style="width: 104.5%;">
                    <option value=""></option>
                    @foreach($status as $status)
                      <option value="{{ $status->id }}" {{ Auth::user()->id_status == $status->id? 'selected' : null }}>{{ strtoupper($status->status) }}</option>
                    @endforeach                   
                  </select>
                </div>                
              </div>

              <div class="control-group">                
                <label for="id_tenaga_bagian" class="control-label span2">Bagian</label>
                <div class="controls span4">
                  <select class="form-control" id="id_tenaga_bagian" name="id_tenaga_bagian" disabled style="width: 104.5%;">
                    <option value=""></option>
                    @foreach($bagian as $bagian)
                      <option value="{{ $bagian->id }}" {{ Auth::user()->id_tenaga_bagian == $bagian->id? 'selected' : null }}>{{ strtoupper($bagian->bagian) }}</option>
                    @endforeach                   
                  </select>
                </div>
              </div>

              <div class="control-group">                
                <label for="id_ruang" class="control-label span2">R. Kerja Utama</label>
                <div class="controls span4">
                  <select class="form-control" id="id_ruang" name="id_ruang" disabled style="width: 104.5%;">
                    <option value=""></option>
                    @foreach($ruang as $rng)
                      <option value="{{ $rng->id }}" {{ Auth::user()->id_ruang == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
                    @endforeach                   
                  </select>
                </div>

                <label for="id_ruang_1" class="control-label span2">R. Tambahan 1</label>
                <div class="controls span4">
                  <select class="form-control" id="id_ruang_1" name="id_ruang_1" disabled style="width: 104.5%;">
                    <option value=""></option>
                    @foreach($ruang as $rng)
                      <option value="{{ $rng->id }}" {{ Auth::user()->id_ruang_1 == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
                    @endforeach                   
                  </select>
                </div>
              </div>

              <div class="control-group">                
                <label for="id_ruang_2" class="control-label span2">R. Tambahan 2</label>
                <div class="controls span4">
                  <select class="form-control" id="id_ruang_2" name="id_ruang_2" disabled style="width: 104.5%;">
                    <option value=""></option>
                    @foreach($ruang as $rng)
                      <option value="{{ $rng->id }}" {{ Auth::user()->id_ruang_2 == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
                    @endforeach                   
                  </select>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label span2">Gaji Pokok</label>
                <div class="controls span2">
                  <div class="input-prepend">
                    <span class="add-on">Rp.</span>
                    <input type="text" class="form-control nominal" style="text-align: right;" readonly value="{{ number_format(Auth::user()->gapok,2) }}">
                  </div>
                </div>
                
                <label class="control-label span4">Koreksi</label>
                <div class="controls span2">
                  <div class="input-prepend">
                    <span class="add-on">Rp.</span>
                    <input type="text" class="form-control nominal" style="text-align: right;" readonly value="{{ number_format(Auth::user()->koreksi,2) }}">
                  </div>
                </div>
              </div>

              <div class="control-group">
                <label class="control-label span2">TPP</label>
                <div class="controls span2">
                  <div class="input-prepend">
                    <span class="add-on">Rp.</span>
                    <input type="text" class="form-control nominal" style="text-align: right;" readonly value="{{ number_format(Auth::user()->tpp,2) }}">
                  </div>
                </div>

                <label for="pajak" class="control-label span4">Pajak</label>
                <div class="controls span1">
                  <div class="input-append">                    
                    <input type="text" class="form-control nominal" style="text-align: right;" readonly value="{{ number_format(Auth::user()->pajak,2) }}">
                    <span class="add-on">%</span>
                  </div>
                </div>
              </div>

              <div class="control-group">
                <label for="pendidikan" class="control-label span2">Pendidikan</label>
                <div class="controls span4">
                  <input type="text" id="pendidikan" name="pendidikan" class="form-control" value="{{ Auth::user()->pendidikan }}" readonly>
                </div>

                <label for="temp_tugas" class="control-label span2">Tempat Tugas</label>
                <div class="controls span4">
                  <input type="text" id="temp_tugas" name="temp_tugas" class="form-control" value="{{ Auth::user()->temp_tugas }}" readonly>
                </div>
              </div>

              <div class="control-group">
                <label for="jabatan" class="control-label span2">Jabatan</label>
                <div class="controls span4">
                  <input type="text" id="jabatan" name="jabatan" class="form-control" value="{{ Auth::user()->jabatan }}" readonly>
                </div>

                <label for="golongan" class="control-label span2">Golongan</label>
                <div class="controls span4">
                  <input type="text" id="golongan" name="golongan" class="form-control" value="{{ Auth::user()->golongan }}" readonly>
                </div>
              </div>
            </div>
          </div>

          <div class="tab-pane" id="home3">
            <div class="span6">
              <table width="100%" class="table table-hover table-striped table-bordered">
                <thead>
                  <th>INDEKS KARYAWAN</th>
                  <th width="100" style="text-align: right;">NILAI</th>
                  <th width="100" style="text-align: right;">BOBOT</th>
                </thead>
                <tbody>
                  <tr>
                    <td valign="middle">INDEKS DASAR</td>
                    <td style="text-align: right;">{{ number_format($karyawan->indeks_dasar,2) }}</td>
                    <td style="text-align: right;">{{ number_format(Auth::user()->dasar_bobot,2) }}</td>
                  </tr>
                  <tr>
                    <td valign="middle">MASA KERJA</td>
                    <td style="text-align: right;">{{ number_format($karyawan->masa_kerja_nilai,2) }}</td>
                    <td style="text-align: right;">{{ number_format($karyawan->masa_kerja_bobot,2) }}</td>
                  </tr>
                  <tr>
                    <td valign="middle">PENDIDIKAN</td>
                    <td style="text-align: right;">{{ number_format(Auth::user()->pend_nilai,2) }}</td>
                    <td style="text-align: right;">{{ number_format(Auth::user()->pend_bobot,2) }}</td>
                  </tr>
                  <tr>
                    <td valign="middle">DIKLAT</td>
                    <td style="text-align: right;">{{ number_format(Auth::user()->diklat_nilai,2) }}</td>
                    <td style="text-align: right;">{{ number_format(Auth::user()->diklat_bobot,2) }}</td>
                  </tr>
                  <tr>
                    <td valign="middle">INDEKS RESIKO</td>
                    <td style="text-align: right;">{{ number_format(Auth::user()->resiko_nilai,2) }}</td>
                    <td style="text-align: right;">{{ number_format(Auth::user()->resiko_bobot,2) }}</td>
                  </tr>
                  <tr>
                    <td valign="middle">KEGAWAT DARURATAN</td>
                    <td style="text-align: right;">{{ number_format(Auth::user()->gawat_nilai,2) }}</td>
                    <td style="text-align: right;">{{ number_format(Auth::user()->gawat_bobot,2) }}</td>
                  </tr>
                  <tr>
                    <td valign="middle">INDEKS JABATAN</td>
                    <td style="text-align: right;">{{ number_format(Auth::user()->jab_nilai,2) }}</td>
                    <td style="text-align: right;">{{ number_format(Auth::user()->jab_bobot,2) }}</td>
                  </tr>
                  <tr>
                    <td valign="middle">KEPANITIAAN</td>
                    <td style="text-align: right;">{{ number_format(Auth::user()->panitia_nilai,2) }}</td>
                    <td style="text-align: right;">{{ number_format(Auth::user()->panitia_bobot,2) }}</td>
                  </tr>
                  <tr>
                    <td valign="middle">PERFORMANCE</td>
                    <td style="text-align: right;">{{ number_format(Auth::user()->perform_nilai,2) }}</td>
                    <td style="text-align: right;">{{ number_format(Auth::user()->perform_bobot,2) }}</td>
                  </tr>
                </tbody>
                <tfoot>
                  <th>TOTAL SCORE</th>
                  <th colspan="2" style="text-align: right;">{{ number_format($karyawan->skore,2) }}</th>
                </tfoot>
              </table>
            </div>

            <div class="span6">
              <table width="100%" class="table table-hover table-striped table-bordered">
                <thead>
                  <th>JASA KARYAWAN</th>
                  <th width="100"></th>
                </thead>
                <tbody>
                  <tr>
                    <td>POS REMUNERASI</td>
                    <td style="text-align: center;">
                      @if(Auth::user()->pos_remun == 1)
                        <i class="icon-ok"></i>
                      @endif
                    </td>
                  </tr>                  
                  <tr>
                    <td>DIREKSI</td>
                    <td style="text-align: center;">
                      @if(Auth::user()->direksi == 1)
                        <i class="icon-ok"></i>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td>STAF DIREKSI</td>
                    <td style="text-align: center;">
                      @if(Auth::user()->staf == 1)
                        <i class="icon-ok"></i>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td>JP LANGSUNG ADMINISTRASI</td>
                    <td style="text-align: center;">
                      @if(Auth::user()->jp_admin == 1)
                        <i class="icon-ok"></i>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td>JP LANGSUNG PERAWAT SETARA</td>
                    <td style="text-align: center;">
                      @if(Auth::user()->jp_perawat == 1)
                        <i class="icon-ok"></i>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td>APOTEKER</td>
                    <td style="text-align: center;">
                      @if(Auth::user()->apoteker == 1)
                        <i class="icon-ok"></i>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td>ASISTEN APOTEKER</td>
                    <td style="text-align: center;">
                      @if(Auth::user()->ass_apoteker == 1)
                        <i class="icon-ok"></i>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td>ADMIN FARMASI</td>
                    <td style="text-align: center;">
                      @if(Auth::user()->admin_farmasi == 1)
                        <i class="icon-ok"></i>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td>PENATA ANASTESI</td>
                    <td style="text-align: center;">
                      @if(Auth::user()->pen_anastesi == 1)
                        <i class="icon-ok"></i>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td>PERAWAT ASISTENSI 1</td>
                    <td style="text-align: center;">
                      @if(Auth::user()->per_asisten_1 == 1)
                        <i class="icon-ok"></i>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td>PERAWAT ASISTENSI 2</td>
                    <td style="text-align: center;">
                      @if(Auth::user()->per_asisten_2 == 1)
                        <i class="icon-ok"></i>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td>INSTRUMEN</td>
                    <td style="text-align: center;">
                      @if(Auth::user()->instrumen == 1)
                        <i class="icon-ok"></i>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td>SIRKULER</td>
                    <td style="text-align: center;">
                      @if(Auth::user()->sirkuler == 1)
                        <i class="icon-ok"></i>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td>PERAWAT PENDAMPING 1</td>
                    <td style="text-align: center;">
                      @if(Auth::user()->per_pendamping_1 == 1)
                        <i class="icon-ok"></i>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td>PERAWAT PENDAMPING 2</td>
                    <td style="text-align: center;">
                      @if(Auth::user()->per_pendamping_2 == 1)
                        <i class="icon-ok"></i>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td>JP LANGSUNG FISIOTERAPIS</td>
                    <td style="text-align: center;">
                      @if(Auth::user()->fisioterapi == 1)
                        <i class="icon-ok"></i>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td>JP LANGSUNG PETUGAS JENASAH</td>
                    <td style="text-align: center;">
                      @if(Auth::user()->pemulasaran == 1)
                        <i class="icon-ok"></i>
                      @endif
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>      
      </div>      
    </div>
  </form>
</div>

<div class="modal hide fade" id="modal_edit_jasa">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Aktifasi Menu Edit Jasa</h4>
  </div>
  <div class="modal-body">
    <form class="form-horizontal fprev" id="form_edit_jasa" method="POST" action="{{ route('menu_simpan') }}">
    @csrf
      <input type="hidden" name="id" value="{{ $c_akses->id }}">

      <div class="control-group">
        <label class="control-label span5">Edit Perawat</label>
        <div class="controls span5">
          <select name="rem_perawat">
            <option value="0" {{ $c_akses->rem_perawat == '0'? 'selected' : null }}>TIDAK AKTIF</option>
            <option value="1" {{ $c_akses->rem_perawat == '1'? 'selected' : null }}>AKTIF</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span5">Edit Administrasi</label>
        <div class="controls span5">
          <select name="rem_admin">
            <option value="0" {{ $c_akses->rem_admin == '0'? 'selected' : null }}>TIDAK AKTIF</option>
            <option value="1" {{ $c_akses->rem_admin == '1'? 'selected' : null }}>AKTIF</option>
          </select>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label span5">Edit Komulatif</label>
        <div class="controls span5">
          <select name="komulatif">
            <option value="0" {{ $c_akses->komulatif == '0'? 'selected' : null }}>TIDAK AKTIF</option>
            <option value="1" {{ $c_akses->komulatif == '1'? 'selected' : null }}>AKTIF</option>
          </select>
        </div>
      </div>      
    </form>
  </div>
  <div class="modal-footer">   
    <div class="btn-group">             
      <button type="submit" form="form_edit_jasa" class="btn bprev">SIMPAN</button>
      <button type="button" class="btn" data-dismiss="modal">TUTUP</button>
    </div>
  </div>
</div>

<input type="hidden" name="no_prop_asal" id="no_prop_asal" value="{{ Auth::user()->no_prop }}">
<input type="hidden" name="no_kab_asal" id="no_kab_asal" value="{{ Auth::user()->no_kab }}">
<input type="hidden" name="no_kec_asal" id="no_kec_asal" value="{{ Auth::user()->no_kec }}">
<input type="hidden" name="no_kel_asal" id="no_kel_asal" value="{{ Auth::user()->no_kel }}">
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
      });

      var activeTab = localStorage.getItem('activeTab');
      if(activeTab){
        $('#myTab a[href="' + activeTab + '"]').tab('show');
      }
    });
  </script>

  <script type="text/javascript">
    window.onload=function() {
      $no_prop  = document.getElementById('no_prop_asal').value;
      $no_kab   = document.getElementById('no_kab_asal').value;
      $no_kec   = document.getElementById('no_kec_asal').value;
      $no_kel   = document.getElementById('no_kel_asal').value;

      if($no_prop){
        $.ajax({
          type : 'get',
          url : '{{ route("pilih_propinsi_edit") }}',
          data: {'no_prop':$no_prop},
          success: function(data){
            $('#no_prop').html(data);
          }
        });
      } else {
        $.ajax({
          type : 'get',
          url : '{{ route("pilih_propinsi") }}',
          success: function(data){
            $('#no_prop').html(data);
          }
        });
      }

      if($no_kab){
        $.ajax({
          type : 'get',
          url : '{{ route("pilih_kota_edit") }}',
          data: {'no_kab': $no_kab, 'no_prop':$no_prop},
          success: function(data){
            $('#no_kab').html(data);
          }
        });
      } else {
        $.ajax({
          type : 'get',
          url : '{{ route("pilih_kota") }}',
          data: {'no_kab': $no_kab, 'no_prop':$no_prop},
          success: function(data){
            $('#no_kab').html(data);
          }
        });
      }

      if($no_kec){
        $.ajax({
          type : 'get',
          url : '{{ route("pilih_kecamatan_edit") }}',
          data: {'no_kec': $no_kec, 'no_kab': $no_kab, 'no_prop':$no_prop},
          success: function(data){
            $('#no_kec').html(data);
          }
        });
      } else {
        $.ajax({
          type : 'get',
          url : '{{ route("pilih_kecamatan") }}',
          data: {'no_kec': $no_kec, 'no_kab': $no_kab, 'no_prop':$no_prop},
          success: function(data){
            $('#no_kec').html(data);
          }
        });
      }

      if($no_kel){
        $.ajax({
          type : 'get',
          url : '{{ route("pilih_desa_edit") }}',
          data: {'no_kel': $no_kel, 'no_kec': $no_kec, 'no_kab': $no_kab, 'no_prop':$no_prop},
          success: function(data){
            $('#no_kel').html(data);
          }
        });
      } else {
        $.ajax({
          type : 'get',
          url : '{{ route("pilih_desa") }}',
          data: {'no_kel': $no_kel, 'no_kec': $no_kec, 'no_kab': $no_kab, 'no_prop':$no_prop},
          success: function(data){
            $('#no_kel').html(data);
          }
        });
      }
    };

    $('#no_prop').on('change',function(){
      $no_prop   = $(this).val();

      $.ajax({
        type : 'get',
        url : '{{ route("pilih_kota") }}',
        data: {'no_prop':$no_prop},
        success: function(data){
          $('#no_kab').html(data);
          $('#no_kec').html('');
          $('#no_kel').html('');
        }
      });
    });

    $('#no_kab').on('change',function(){
      $no_kab   = $(this).val();
      $no_prop  = document.getElementById('no_prop').value;

      $.ajax({
        type : 'get',
        url : '{{ route("pilih_kecamatan") }}',
        data: {'no_kab': $no_kab, 'no_prop': $no_prop},
        success: function(data){
          $('#no_kec').html(data);
          $('#no_kel').html('');
        }
      });
    });

    $('#no_kec').on('change',function(){
      $no_kec   = $(this).val();
      $no_kab   = document.getElementById('no_kab').value;
      $no_prop  = document.getElementById('no_prop').value;

      $.ajax({
        type : 'get',
        url : '{{ route("pilih_desa") }}',
        data: {'no_kec': $no_kec, 'no_kab': $no_kab, 'no_prop': $no_prop},
        success: function(data){
          $('#no_kel').html(data);
        }
      });
    });
  </script>
@endsection