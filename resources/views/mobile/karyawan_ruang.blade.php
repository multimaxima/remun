@extends('mobile.layouts.content')

@section('bawah')
  <li><a href="{{ route('jasa_remun') }}"><i class="fa fa-heart"></i>Remunerasi</a></li>
  <li><a href="{{ route('informasi_software') }}"><i class="fa fa-info"></i>Informasi</a></li>
@endsection

@section('content')
<div class="row isi">
  <div class="container" style="margin-top: 9vh; text-align: center;">
    <label style="font-weight: bold; font-size: 3.5vw;">ABSENSI KARYAWAN {{ strtoupper($tanggal->tanggal) }}</label>
  </div>
</div>

@include('mobile.layouts.pesan')

<div class="row isi" style="padding-bottom: 10vh;">
  <div class="container">
    @foreach($karyawan as $kary)
    <div class="card" style="margin-top: 1vh;">
      <div class="card-body" style="font-size: 3vw;">
        <table width="100%" class="table table-sm">
          <tr>
            <td width="30%">Nama</td>
            <td width="5%">:</td>
            <td>{{ $kary->nama }}</td>
          </tr>
          <tr>
            <td>Bagian</td>
            <td>:</td>
            <td>{{ strtoupper($kary->bagian) }}</td>
          </tr>
          <tr>
            <td>Ruang</td>
            <td>:</td>
            <td>{{ strtoupper($kary->ruang) }}</td>
          </tr>
          <tr>
            <td>R. Tambahan 1</td>
            <td>:</td>
            <td>{{ strtoupper($kary->ruang_1) }}</td>
          </tr>
          <tr>
            <td>R. Tambahan 2</td>
            <td>:</td>
            <td>{{ strtoupper($kary->ruang_2) }}</td>                 
          </tr>
          <tr>
            <td>Kehadiran</td>
            <td>:</td>
            <td>
              @if($kary->cuti == 0)
              <select class="hadir" data-id="{{ $kary->id }}">
                @foreach($absen as $abs)
                <option value="{{ $abs->id }}" {{ $kary->hadir == $abs->id? 'selected' : null }}>{{ $abs->absen }}</option>
                @endforeach
              </select>
              @else
              <select disabled>
                @foreach($absen as $abs)
                <option value="{{ $abs->id }}" {{ $kary->hadir == $abs->id? 'selected' : null }}>{{ $abs->absen }}</option>
                @endforeach
              </select>
              @endif
            </td>
          </tr>
        </table>
        <a href="#" class="btn btn-info btn-xs edit" style="font-size: 12px;" data-id="{{ $kary->id }}">
          PINDAH RUANG
        </a>
      </div>
    </div>
    @endforeach
  </div>
</div>     

<div class="modal fade" id="modal_karyawan_pindah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <label style="font-size: 3.5vw; font-weight: bold;" class="modal-title" id="edit_judul"></label>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form method="POST" id="pindah_ruang" action="{{ route('karyawan_pindah_ruang') }}">
        @csrf
          <input type="hidden" name="id" id="edit_id">

          <select name="id_ruang" id="edit_id_ruang" class="form-control" size="15" style="height: 35vh;">
            @foreach($ruang as $rng)
            <option value="{{ $rng->id }}">{{ $rng->ruang }}</option>
            @endforeach
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-group-xs">             
          <button type="submit" form="pindah_ruang" class="btn btn-secondary btn-xs bprev">SIMPAN</button>
          <button type="button" class="btn btn-secondary btn-xs" data-dismiss="modal">TUTUP</button>
        </div>
      </div>
    </div>
  </div>
</div>  
@endsection

@section('script')
  <script type="text/javascript">
    window.onload=function() {
      $id_ruang = document.getElementById('id_ruang').value;
      document.getElementById('c_id_ruang').value = $id_ruang;
    }
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('.hadir').on("change",function() {
        $id    = $(this).attr('data-id');
        $hadir = $(this).val();

        $.ajax({
          url : "{{ route('karyawan_hadir') }}",
          type: "GET",
          data: {'id': $id, 'hadir': $hadir},         
        });
      });      
    });

    $(document).ready(function() {
      $('.edit').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{route('karyawan_pindah_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#edit_id').val(data.id);
            $('#edit_id_ruang').val(data.id_ruang);
            $('#edit_judul').html(data.nama);
            $('#modal_karyawan_pindah').modal('show');
          }
        });
      });
    });

    $(document).ready(function() {
      var box = document.querySelector('.content');
      var tinggi = box.clientHeight-(0.11*box.clientHeight);

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
@endsection