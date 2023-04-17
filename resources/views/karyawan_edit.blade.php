@extends('layouts.content')
@section('title',$karyawan->nama)

@section('content')
<div class="content" style="max-height: 81vh;">
  <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="myTab">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#home1" role="tab">PROFIL KARYAWAN</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#profile1" role="tab">INDEKS & INDEKS KARYAWAN</a>
    </li>    
  </ul>

  <form hidden method="GET" action="{{ route('karyawan') }}" id="kembali">
  @csrf
    <input type="text" name="tampil" value="{{ $tampil }}">
    <input type="text" name="cari" value="{{ $cari }}">
    <input type="text" name="id_ruang" value="{{ $id_ruang }}">
    <input type="text" name="id_bagian" value="{{ $id_bagian }}">
  </form>

  <div class="tab-content" style="padding: 0 10px; margin-top: -15px;">
    <div class="tab-pane active p-3" id="home1" role="tabpanel">
      <div class="navbar" style="margin-bottom:5px;">
        <div class="navbar-inner">    
          <div class="row-fluid">
            <div class="span12" style="display: inline-flex;">
              <div class="btn-group">
                <button type="submit" form="kembali" class="btn btn-primary" title="Kembali">KEMBALI</button>
                <button type="submit" form="data" class="btn btn-primary bprev">SIMPAN</button>
                <a href="{{ route('karyawan_reset',Crypt::encrypt($karyawan->id)) }}" class="btn btn-primary" title="Reset Password Karyawan" onclick="return confirm('Reset password {{ $karyawan->nama }} ?')">RESET PASSWORD</a>
              </div>
            </div>
          </div>
        </div>
      </div>     

      <form class="form-horizontal fprev" method="POST" action="{{ route('karyawan_edit_simpan') }}" files="true" enctype="multipart/form-data" id="data" style="margin-top: 10px;">
      @csrf
        <input type="hidden" name="id" value="{{ Crypt::encrypt($karyawan->id) }}">

        <div class="container-fluid">
          <div class="span2">
            @if($karyawan->foto)
              <img src="/{{ $karyawan->foto }}" width="100%">
            @else
              <img src="/images/noimage.jpg" width="100%">
            @endif
            <input type="file" name="foto" style="margin-top: 10px;" accept=".jpg">
          </div>

          <div class="span10">          
            <div class="control-group">
              <label for="nama" class="control-label span2">Nama</label>
              <div class="controls span4">
                <input type="text" id="nama" name="nama" class="form-control" required autofocus value="{{ $karyawan->nama }}">
              </div>

              <label for="nip" class="control-label span2">NIP</label>
              <div class="controls span4">
                <input type="text" id="nip" name="nip" class="form-control" value="{{ $karyawan->nip }}">
              </div>
            </div>

            <div class="control-group">
              <label for="gelar_depan" class="control-label span2">Gelar Depan</label>
              <div class="controls span4">
                <input type="text" id="gelar_depan" name="gelar_depan" class="form-control" value="{{ $karyawan->gelar_depan }}">
              </div>

              <label for="gelar_belakang" class="control-label span2">Gelar Belakang</label>
              <div class="controls span4">
                <input type="text" id="gelar_belakang" name="gelar_belakang" class="form-control" value="{{ $karyawan->gelar_belakang }}">
              </div>
            </div>

            <div class="control-group">
              <label for="alamat" class="control-label span2">Alamat</label>
              <div class="controls span10">
                <input type="text" id="alamat" name="alamat" class="form-control" value="{{ $karyawan->alamat }}">
              </div>
            </div>

            <div class="control-group">
              <label for="dusun" class="control-label span2">Dusun</label>
              <div class="controls span4">
                <input type="text" id="dusun" name="dusun" class="form-control" value="{{ $karyawan->dusun }}">
              </div>
              
              <label for="rt" class="control-label span2">RT</label>
              <div class="controls span1">
                <input type="text" id="rt" name="rt" class="form-control" value="{{ $karyawan->rt }}">
              </div>

              <label for="rw" class="control-label span1">RW</label>
              <div class="controls span1">
                <input type="text" id="rw" name="rw" class="form-control" value="{{ $karyawan->rw }}">
              </div>
            </div>

            <div class="control-group">
              <label class="control-label span2">Propinsi</label>
              <div class="controls span4">
                <select class="form-control" name="no_prop" id="no_prop" style="width: 104.5%"></select>
              </div>

              <label class="control-label span2">Kota</label>
              <div class="controls span4">
                <select class="form-control" name="no_kab" id="no_kab" style="width: 104.5%"></select>
              </div>
            </div>

            <div class="control-group">
              <label class="control-label span2">Kecamatan</label>
              <div class="controls span4">
                <select class="form-control" name="no_kec" id="no_kec" style="width: 104.5%"></select>
              </div>

              <label class="control-label span2">Desa</label>
              <div class="controls span4">
                <select class="form-control" name="no_kel" id="no_kel" style="width: 104.5%"></select>
              </div>
            </div>

            <div class="control-group">
              <label for="temp_lahir" class="control-label span2">Tempat Lahir</label>
              <div class="controls span4">
                <input type="text" id="temp_lahir" name="temp_lahir" class="form-control" value="{{ $karyawan->temp_lahir }}">
              </div>

              <label for="tgl_lahir" class="control-label span2">Tanggal Lahir</label>
              <div class="controls span4">
                <input type="date" id="tgl_lahir" name="tgl_lahir" class="form-control" value="{{ $karyawan->tgl_lahir }}">
              </div>
            </div>

            <div class="control-group">
              <label for="id_kelamin" class="control-label span2">Jenis Kelamin</label>
              <div class="controls span4">
                <select class="form-control" id="id_kelamin" name="id_kelamin" style="width: 105%;" required>
                  <option value="1" {{ $karyawan->id_kelamin == '1'? 'selected' : null }}>LAKI-LAKI</option>
                  <option value="2" {{ $karyawan->id_kelamin == '2'? 'selected' : null }}>PEREMPUAN</option>
                </select>
              </div>

              <label for="email" class="control-label span2">Email</label>
              <div class="controls span4">
                <input type="text" id="email" name="email" class="form-control" value="{{ $karyawan->email }}">
              </div>                
            </div>

            <div class="control-group">
              <label for="telp" class="control-label span2">Telepon</label>
              <div class="controls span4">
                <input type="text" id="telp" name="telp" class="form-control" value="{{ $karyawan->telp }}">
              </div>

              <label for="hp" class="control-label span2">HP</label>
              <div class="controls span4">
                <input type="text" id="hp" name="hp" class="form-control" value="{{ $karyawan->hp }}">
              </div>
            </div>

            <div class="control-group">
              <label for="mulai_kerja" class="control-label span2">Mulai Kerja</label>
              <div class="controls span4">
                <input type="date" id="mulai_kerja" name="mulai_kerja" class="form-control" value="{{ $karyawan->mulai_kerja }}" required>
              </div>

              <label for="id_status" class="control-label span2">Status</label>
              <div class="controls span4">
                <select class="form-control" id="id_status" name="id_status" required style="width: 105%;">
                  <option value=""></option>
                  @foreach($status as $status)
                    <option value="{{ $status->id }}" {{ $karyawan->id_status == $status->id? 'selected' : null }}>{{ strtoupper($status->status) }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="control-group">
              <label for="keluar" class="control-label span2">Keluar/Pensiun</label>
              <div class="controls span4">
                <input type="date" id="keluar" name="keluar" class="form-control" value="{{ $karyawan->keluar }}">
              </div>
            </div>

            <div class="control-group">
              <label for="id_tenaga_bagian" class="control-label span2">Bagian</label>
              <div class="controls span4">
                <select class="form-control" id="id_tenaga_bagian" name="id_tenaga_bagian" style="width: 105%;" required>
                  <option value=""></option>
                  @foreach($bagian as $bagian)
                    <option value="{{ $bagian->id }}" {{ $karyawan->id_tenaga_bagian == $bagian->id? 'selected' : null }}>{{ strtoupper($bagian->bagian) }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div style="background-color: #e8e8e8; padding: 10px 0;">
            <div class="control-group">
              <label for="id_ruang" class="control-label span2">R. Kerja Utama</label>
              <div class="controls span9">
                <select class="form-control" id="id_ruang" name="id_ruang" style="width: 105%;" required>
                  <option value=""></option>
                  @foreach($ruang as $rng)
                    <option value="{{ $rng->id }}" {{ $karyawan->id_ruang == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="control-group">
              <label for="id_ruang_1" class="control-label span2">Tambahan 1</label>
              <div class="controls span9">
                <select class="form-control" id="id_ruang_1" name="id_ruang_1" style="width: 105%;">
                  <option value=""></option>
                  @foreach($ruang as $rng)
                    <option value="{{ $rng->id }}" {{ $karyawan->id_ruang_1 == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
                  @endforeach                   
                </select>
              </div>
            </div>              

            <div class="control-group">
              <label for="id_ruang_2" class="control-label span2">Tambahan 2</label>
              <div class="controls span9">
                <select class="form-control" id="id_ruang_2" name="id_ruang_2" style="width: 105%;">
                  <option value=""></option>
                  @foreach($ruang as $rng)
                    <option value="{{ $rng->id }}" {{ $karyawan->id_ruang_2 == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
                  @endforeach                   
                </select>
              </div>
            </div>
          </div>

            <div class="control-group" style="margin-top: 10px;">
              <label for="pendidikan" class="control-label span2">Pendidikan</label>
              <div class="controls span4">
                <input type="text" id="pendidikan" name="pendidikan" class="form-control" value="{{ $karyawan->pendidikan }}">
              </div>

              <label for="temp_tugas" class="control-label span2">Tempat Tugas</label>
              <div class="controls span4">
                <input type="text" id="temp_tugas" name="temp_tugas" class="form-control" value="{{ $karyawan->temp_tugas }}">
              </div>
            </div>

            <div class="control-group">
              <label for="jabatan" class="control-label span2">Jabatan</label>
              <div class="controls span4">
                <input type="text" id="jabatan" name="jabatan" class="form-control" value="{{ $karyawan->jabatan }}">
              </div>

              <label for="golongan" class="control-label span2">Golongan</label>
              <div class="controls span4">
                <input type="text" id="golongan" name="golongan" class="form-control" value="{{ $karyawan->golongan }}">
              </div>
            </div>

            <div class="control-group">
              <label for="npwp" class="control-label span2">NPWP</label>
              <div class="controls span4">
                <input type="text" id="npwp" name="npwp" class="form-control" value="{{ $karyawan->npwp }}">
              </div>
            </div>

            <div class="control-group">
              <label for="bank" class="control-label span2">Bank</label>
              <div class="controls span4">
                <select class="form-control" id="bank" name="bank" style="width: 105%;">
                  <option value=""></option>
                  @foreach($bank as $bank)
                    <option value="{{ $bank->bank }}" {{ $karyawan->bank == $bank->bank? 'selected' : null }}>{{ $bank->bank }}</option>
                  @endforeach
                </select>
              </div>

              <label for="rekening" class="control-label span2">Nomor Rekening</label>
              <div class="controls span4">
                <input type="text" id="rekening" name="rekening" class="form-control" value="{{ $karyawan->rekening }}">
              </div>
            </div>

            <div class="control-group">
              <label for="username" class="control-label span2">Username</label>
              <div class="controls span4">
                <input type="text" id="username" name="username" class="form-control" value="{{ $karyawan->username }}" readonly>
                    </div>

              <label for="id_akses" class="control-label span2">Hak Akses</label>
              <div class="controls span4">
                <select class="form-control" name="id_akses" style="width: 105%;" required>
                  @foreach($akses as $akses)
                    <option value="{{ $akses->id }}" {{ $karyawan->id_akses == $akses->id? 'selected' : null }}>{{ strtoupper($akses->akses) }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="control-group">
              <label for="gapok" class="control-label span2">Gaji Pokok</label>
              <div class="controls span2">
                <div class="input-prepend">
                  <span class="add-on">Rp.</span>
                  <input type="text" class="form-control nominal" style="text-align: right;" id="gapok" name="gapok" required value="{{ $karyawan->gapok }}">
                </div>
              </div>

              <label for="koreksi" class="control-label span4">Koreksi</label>
              <div class="controls span2">
                <div class="input-prepend">
                  <span class="add-on">Rp.</span>
                  <input type="text" class="form-control nominal" style="text-align: right;" id="koreksi" name="koreksi" required value="{{ $karyawan->koreksi }}">
                </div>
              </div>
            </div>

            <div class="control-group">
              <label for="tpp" class="control-label span2">TPP</label>
              <div class="controls span2">
                <div class="input-prepend">
                  <span class="add-on">Rp.</span>
                  <input type="text" class="form-control nominal" style="text-align: right;" id="tpp" name="tpp" required value="{{ $karyawan->tpp }}">
                </div>
              </div>
              
              <label for="pajak" class="control-label span4">Pajak</label>
              <div class="controls span1">
                <div class="input-append">
                  <input type="text" class="form-control nominal" id="pajak" name="pajak" required value="{{ $karyawan->pajak }}">
                  <span class="add-on">%</span>
                </div>
              </div>
            </div>

            <div class="control-group">
              <label for="status" class="control-label span2">Status</label>
              <div class="controls span4">
                <select class="form-control form-control-sm" name="status">
                  <option value="0" {{ $karyawan->hapus == 0? 'selected' : null }}>AKTIF</option>
                  <option value="1" {{ $karyawan->hapus == 1? 'selected' : null }}>TIDAK AKTIF</option>
                </select>
              </div>
            </div>
          </div>            
        </div>
      </form>
    </div>

    <div class="tab-pane p-3" id="profile1" role="tabpanel">
      <div class="navbar" style="margin-bottom:5px;">
        <div class="navbar-inner">    
          <div class="row-fluid">
            <div class="span12">
              <div class="btn-group">
                <button type="submit" form="kembali" class="btn btn-primary" title="Kembali">KEMBALI</button>
                <button type="submit" form="jasa_edit" class="btn btn-primary bprev">SIMPAN</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <form class="form-horizontal fprev" id="jasa_edit" method="POST" action="{{ route('karyawan_jasa_indek_simpan') }}" style="margin-top: 10px;">
      @csrf
        <input type="hidden" name="id" value="{{ Crypt::encrypt($karyawan->id) }}">

        <div class="container-fluid">
          <div class="span6">
            <table width="100%" class="table table-hover table-striped">
              <thead>
                <th>INDEKS KARYAWAN</th>
                <th width="100">NILAI</th>
                <th width="100">BOBOT</th>
              </thead>
              <tbody>
                <tr>
                  <td valign="middle">INDEKS DASAR</td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" class="form-control" required value="{{ $karyawan->indeks_dasar }}" readonly>
                  </td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" id="dasar_bobot" name="dasar_bobot" class="form-control" required value="{{ $karyawan->dasar_bobot }}">
                  </td>
                </tr>
                <tr>
                  <td valign="middle">MASA KERJA</td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" id="perform_nilai" class="form-control" required value="{{ $karyawan->masa_kerja_nilai }}" readonly>
                  </td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" id="masa_kerja_bobot" name="masa_kerja_bobot" class="form-control" required value="{{ $karyawan->masa_kerja_bobot }}">
                  </td>
                </tr>
                <tr>
                  <td valign="middle">PENDIDIKAN</td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" id="pend_nilai" name="pend_nilai" class="form-control" required value="{{ $karyawan->pend_nilai }}">
                  </td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" id="pend_bobot" name="pend_bobot" class="form-control" required value="{{ $karyawan->pend_bobot }}">
                  </td>
                </tr>
                <tr>
                  <td valign="middle">DIKLAT</td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" id="diklat_nilai" name="diklat_nilai" class="form-control" required value="{{ $karyawan->diklat_nilai }}">
                  </td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" id="diklat_bobot" name="diklat_bobot" class="form-control" required value="{{ $karyawan->diklat_bobot }}">
                  </td>
                </tr>
                <tr>
                  <td valign="middle">INDEKS RESIKO</td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" class="form-control" required value="{{ $karyawan->resiko_nilai }}" readonly>
                  </td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" id="resiko_bobot" name="resiko_bobot" class="form-control" required value="{{ $karyawan->resiko_bobot }}">
                  </td>
                </tr>
                <tr>
                  <td valign="middle">KEGAWAT DARURATAN</td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" class="form-control" required value="{{ $karyawan->gawat_nilai }}" readonly>
                  </td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" id="gawat_bobot" name="gawat_bobot" class="form-control" required value="{{ $karyawan->gawat_bobot }}">
                  </td>
                </tr>
                <tr>
                  <td valign="middle">INDEKS JABATAN</td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" id="jab_nilai" name="jab_nilai" class="form-control" required value="{{ $karyawan->jab_nilai }}">
                  </td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" id="jab_bobot" name="jab_bobot" class="form-control" required value="{{ $karyawan->jab_bobot }}">
                  </td>
                </tr>
                <tr>
                  <td valign="middle">KEPANITIAAN</td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" id="panitia_nilai" name="panitia_nilai" class="form-control" required value="{{ $karyawan->panitia_nilai }}">
                  </td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" id="panitia_bobot" name="panitia_bobot" class="form-control" required value="{{ $karyawan->panitia_bobot }}">
                  </td>
                </tr>
                <tr>
                  <td valign="middle">PERFORMANCE</td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" id="perform_nilai" name="perform_nilai" class="form-control" required value="{{ $karyawan->perform_nilai }}">
                  </td>
                  <td style="padding: 3px 20px;">
                    <input type="number" style="text-align: right;" id="perform_bobot" name="perform_bobot" class="form-control" required value="{{ $karyawan->perform_bobot }}">
                  </td>
                </tr>
                <tr>
                  <td style="font-weight: bold;">TOTAL SCORE</td>
                  <td colspan="2" style="text-align: right; font-weight: bold;">{{ number_format($karyawan->skore,2) }}</td>
                </tr>
              </tbody>    
            </table>
          </div>

          <div class="span6">
            <table width="100%" class="table table-hover table-striped">
              <thead>
                <th>JASA KARYAWAN</th>
                <th width="100"></th>
              </thead>
              <tbody>
                <tr>
                  <td>POS REMUNERASI</td>
                  <td>
                    <select class="form-control form-control-sm" id="pos_remun" name="pos_remun">
                      <option value="0" {{ $karyawan->pos_remun == 0? 'selected' : null }}>TIDAK</option>
                      <option value="1" {{ $karyawan->pos_remun == 1? 'selected' : null }}>YA</option>
                    </select>
                  </td>
                </tr>                
                <tr>
                  <td>DIREKSI</td>
                  <td>
                    <select class="form-control form-control-sm" id="direksi" name="direksi">
                      <option value="0" {{ $karyawan->direksi == 0? 'selected' : null }}>TIDAK</option>
                      <option value="1" {{ $karyawan->direksi == 1? 'selected' : null }}>YA</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>STAF DIREKSI</td>
                  <td>
                    <select class="form-control form-control-sm" id="staf" name="staf">
                      <option value="0" {{ $karyawan->staf == 0? 'selected' : null }}>TIDAK</option>
                      <option value="1" {{ $karyawan->staf == 1? 'selected' : null }}>YA</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>JP LANGSUNG ADMINISTRASI</td>
                  <td>
                    <select class="form-control form-control-sm" id="jp_admin" name="jp_admin">
                      <option value="0" {{ $karyawan->jp_admin == 0? 'selected' : null }}>TIDAK</option>
                      <option value="1" {{ $karyawan->jp_admin == 1? 'selected' : null }}>YA</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>JP LANGSUNG PERAWAT SETARA</td>
                  <td>
                    <select class="form-control form-control-sm" id="jp_perawat" name="jp_perawat">
                      <option value="0" {{ $karyawan->jp_perawat == 0? 'selected' : null }}>TIDAK</option>
                      <option value="1" {{ $karyawan->jp_perawat == 1? 'selected' : null }}>YA</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>APOTEKER</td>
                  <td>
                    <select class="form-control form-control-sm" id="apoteker" name="apoteker">
                      <option value="0" {{ $karyawan->apoteker == 0? 'selected' : null }}>TIDAK</option>
                      <option value="1" {{ $karyawan->apoteker == 1? 'selected' : null }}>YA</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>ASISTEN APOTEKER</td>
                  <td>
                    <select class="form-control form-control-sm" id="ass_apoteker" name="ass_apoteker">
                      <option value="0" {{ $karyawan->ass_apoteker == 0? 'selected' : null }}>TIDAK</option>
                      <option value="1" {{ $karyawan->ass_apoteker == 1? 'selected' : null }}>YA</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>ADMIN FARMASI</td>
                  <td>
                    <select class="form-control form-control-sm" id="admin_farmasi" name="admin_farmasi">
                      <option value="0" {{ $karyawan->admin_farmasi == 0? 'selected' : null }}>TIDAK</option>
                      <option value="1" {{ $karyawan->admin_farmasi == 1? 'selected' : null }}>YA</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>PENATA ANASTESI</td>
                  <td>
                    <select class="form-control form-control-sm" id="pen_anastesi" name="pen_anastesi">
                      <option value="0" {{ $karyawan->pen_anastesi == 0? 'selected' : null }}>TIDAK</option>
                      <option value="1" {{ $karyawan->pen_anastesi == 1? 'selected' : null }}>YA</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>PERAWAT ASISTENSI 1</td>
                  <td>
                    <select class="form-control form-control-sm" id="per_asisten_1" name="per_asisten_1">
                    <option value="0" {{ $karyawan->per_asisten_1 == 0? 'selected' : null }}>TIDAK</option>
                    <option value="1" {{ $karyawan->per_asisten_1 == 1? 'selected' : null }}>YA</option>
                  </select>
                  </td>
                </tr>
                <tr>
                  <td>PERAWAT ASISTENSI 2</td>
                  <td>
                    <select class="form-control form-control-sm" id="per_asisten_2" name="per_asisten_2">
                    <option value="0" {{ $karyawan->per_asisten_2 == 0? 'selected' : null }}>TIDAK</option>
                    <option value="1" {{ $karyawan->per_asisten_2 == 1? 'selected' : null }}>YA</option>
                  </select>
                  </td>
                </tr>
                <tr>
                  <td>INSTRUMEN</td>
                  <td>
                    <select class="form-control form-control-sm" id="instrumen" name="instrumen">
                    <option value="0" {{ $karyawan->instrumen == 0? 'selected' : null }}>TIDAK</option>
                    <option value="1" {{ $karyawan->instrumen == 1? 'selected' : null }}>YA</option>
                  </select>
                  </td>
                </tr>
                <tr>
                  <td>SIRKULER</td>
                  <td>
                    <select class="form-control form-control-sm" id="sirkuler" name="sirkuler">
                    <option value="0" {{ $karyawan->sirkuler == 0? 'selected' : null }}>TIDAK</option>
                    <option value="1" {{ $karyawan->sirkuler == 1? 'selected' : null }}>YA</option>
                  </select>
                  </td>
                </tr>
                <tr>
                  <td>PERAWAT PENDAMPING 1</td>
                  <td>
                    <select class="form-control form-control-sm" id="per_pendamping_1" name="per_pendamping_1">
                      <option value="0" {{ $karyawan->per_pendamping_1 == 0? 'selected' : null }}>TIDAK</option>
                      <option value="1" {{ $karyawan->per_pendamping_1 == 1? 'selected' : null }}>YA</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>PERAWAT PENDAMPING 2</td>
                  <td>
                    <select class="form-control form-control-sm" id="per_pendamping_2" name="per_pendamping_2">
                      <option value="0" {{ $karyawan->per_pendamping_2 == 0? 'selected' : null }}>TIDAK</option>
                      <option value="1" {{ $karyawan->per_pendamping_2 == 1? 'selected' : null }}>YA</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>JP LANGSUNG FISIOTERAPIS</td>
                  <td>
                    <select class="form-control form-control-sm" id="fisioterapis" name="fisioterapis">
                      <option value="0" {{ $karyawan->fisioterapis == 0? 'selected' : null }}>TIDAK</option>
                      <option value="1" {{ $karyawan->fisioterapis == 1? 'selected' : null }}>YA</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>JP LANGSUNG PETUGAS JENAZAH</td>
                  <td>
                    <select class="form-control form-control-sm" id="pemulasaran" name="pemulasaran">
                      <option value="0" {{ $karyawan->pemulasaran == 0? 'selected' : null }}>TIDAK</option>
                      <option value="1" {{ $karyawan->pemulasaran == 1? 'selected' : null }}>YA</option>
                    </select>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </div>             
  </div>          
</div>      

<input type="hidden" name="no_prop_asal" id="no_prop_asal" value="{{ $karyawan->no_prop }}">
<input type="hidden" name="no_kab_asal" id="no_kab_asal" value="{{ $karyawan->no_kab }}">
<input type="hidden" name="no_kec_asal" id="no_kec_asal" value="{{ $karyawan->no_kec }}">
<input type="hidden" name="no_kel_asal" id="no_kel_asal" value="{{ $karyawan->no_kel }}">
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('.edit').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{route('cuti_edit_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#edit_id').val(data.id);
            $('#edit_awal').val(data.awal);
            $('#edit_akhir').val(data.akhir);
            $('#edit_keterangan').val(data.keterangan);
            $('#data_edit').modal('show');
          }
        });
      });
    });

    $(document).ready(function(){
      $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
      });

      var activeTab = localStorage.getItem('activeTab');
      if(activeTab){
        $('#myTab a[href="' + activeTab + '"]').tab('show');
      }
    
      $('#tabel').DataTable( {        
        "columnDefs": [{
          "searchable": false,
          "orderable": false,
          "targets": 0
        }],
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        "order": [[ 1, "asc" ]],
      });      
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