@extends('mobile.layouts.content')

@section('bawah')
  <li><a href="{{ route('jasa_remun') }}"><i class="fa fa-heart"></i>Remunerasi</a></li>
  <li><a href="{{ route('informasi_software') }}"><i class="fa fa-info"></i>Informasi</a></li>
@endsection

@section('content')
<div class="row isi">
  <div class="container" style="margin-top: 9vh;">
    <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#data_baru">
      TAMBAH
    </button>
  </div>
</div>

@include('mobile.layouts.pesan')

<div class="row isi" style="padding-bottom: 10vh;">  
  <div class="container">
    @foreach($ruang as $rng)
    <div class="card card-body" style="margin-top: 1vh;">
      <label style="font-weight: bold;">{{ strtoupper($rng->ruang) }}</label>
      <table width="100%" class="table table-sm table-bordered" style="font-size: 3vw;">
        <tr>
          <td>Rawat Jalan</td>
          <td align="center" width="50">
            @if($rng->jalan == 1)
              <i class="fa fa-check"></i>                
            @endif
          </td>
        </tr>
        <tr>
          <td>Rawat Inap</td>
          <td align="center">
            @if($rng->inap == 1)
              <i class="fa fa-check"></i>                
            @endif
          </td>
        </tr>
        <tr>
          <td>Terima Pasien</td>
          <td align="center">
            @if($rng->terima_pasien == 1)
              <i class="fa fa-check"></i>                
            @endif
          </td>
        </tr>
        <tr>
          <td>Pasien Luar</td>
          <td align="center">
            @if($rng->nonpasien == 1)
              <i class="fa fa-check"></i>                
            @endif
          </td>
        </tr>                
        <tr>
          <td colspan="2">
            <table width="100%" class="table table-sm table-bordered">
              <tr style="background-color: #f1f1f1;">
                <td colspan="2" align="center" style="font-weight: bold;">INDEKS MEDIS</td>
                <td colspan="2" align="center" style="font-weight: bold;">INDEKS PERAWAT</td>
              </tr>
              <tr>
                <td align="center">GAWAT DARURAT</td>
                <td align="center">RESIKO</td>
                <td align="center">GAWAT DARURAT</td>
                <td align="center">RESIKO</td>
              </tr>              
              <tr>
                <td align="center">{{ $rng->m_gawat_darurat }}</td>
                <td align="center">{{ $rng->m_resiko }}</td>
                <td align="center">{{ $rng->p_gawat_darurat }}</td>
                <td align="center">{{ $rng->p_resiko }}</td>
              </tr>              
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            JENIS PASIEN :<br>            
            @if(count($rng->ruang_jenis) > 0)
              @foreach($rng->ruang_jenis as $jen)
                @if($jen->aktif == 1)
                  <a href="#" class="aktif" title="Non Aktifkan" data-id="{{ $jen->id }}">
                    <input type="checkbox" checked style="margin-top: -3px;">
                  </a>
                
                @else
                  <a href="#" class="aktif" title="Aktifkan" data-id="{{ $jen->id }}">
                    <input type="checkbox" style="margin-top: -3px;">
                  </a>                  
                @endif
                {{ $jen->jenis }}<br>
              @endforeach
            @endif
          </td>
        </tr>
        <tr>
          <td colspan="2">
            LAYANAN :
            <table width="100%">
              @foreach($rng->ruang_jasa as $jas)
              <tr>
                <td class="min">
                  <div class="btn-group btn-group-xs" role="group">
                    <a href="#" class="btn btn-info edit_layanan" title="Edit Layanan" data-toggle="modal" data-id="{{ $jas->id }}">
                      <i class="fa fa-edit"></i>
                    </a>
                    <a href="{{ route('ruang_layanan_hapus',Crypt::encrypt($jas->id)) }}" class="btn btn-info" onclick="return confirm('Hapus jasa ?')">
                      <i class="fa fa-trash"></i>
                    </a>
                  </div>
                </td>
                <td style="padding-left: 2vw;">{{ $jas->jasa }}</td>                
              </tr>
              @endforeach  
            </table>
          </td>
        </tr> 
      </table>

      <div class="row">
        <div class="col">
          <div class="btn-group">
            <a href="#" class="btn btn-success btn-sm btn-edit_ruang" data-toggle="modal" data-id="{{ $rng->id }}" title="Edit">EDIT</a>

            <button class="btn btn-success btn-sm layanan_baru" data-toggle="modal" data-id="{{ $rng->id }}">LAYANAN</button>

            <a href="{{ route('ruang_hapus',Crypt::encrypt($rng->id)) }}" class="btn btn-success btn-sm" title="Hapus" onclick="return confirm('Hapus ruang {{ $rng->ruang }} ?')">HAPUS</a>
          </div>
        </div>
      </div>
    </div>
    @endforeach

    <div style="margin-top: 1vh; font-size: 3vw;">
          <div class="pull-left">
            {{ number_format($ruang->firstItem(),0) }} - {{ number_format($ruang->lastItem(),0) }} dari {{ number_format($ruang->total(),0) }} data
          </div>                               
          <div class="pagination pagination-sm pull-right">
            {!! $ruang->appends(request()->input())->render("pagination::bootstrap-4"); !!}
          </div>
        </div>
  </div>
</div>



<!--Tambah Ruang-->
<div class="modal fade" id="data_baru" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">TAMBAH RUANG</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal fprev" id="form_baru_ruang" method="POST" action="{{ route('ruang_baru') }}">
        @csrf

          <div class="mb-1">
            <div class="title mb-1">Nama Ruang</div>
            <input type="text" class="form-control" name="ruang" required autofocus>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Rawat Jalan</div>
            <select class="form-control" name="jalan">
              <option value="0">TIDAK</option>
              <option value="1">YA</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Rawat Inap</div>
            <select class="form-control" name="inap">
              <option value="0">TIDAK</option>
              <option value="1">YA</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Terima Pasien</div>
            <select class="form-control" name="terima_pasien">
              <option value="0">TIDAK</option>
              <option value="1">YA</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Pasien Luar</div>
            <select class="form-control" name="nonpasien">
              <option value="0">TIDAK</option>
              <option value="1">YA</option>
            </select>
          </div>

          <table width="100%" class="table table-bordered" style="font-size: 3vw;">
            <tr style="background-color: #f1f1f1;">
              <td colspan="2" align="center">INDEKS SCORE MEDIS</td>
              <td colspan="2" align="center">INDEKS SCORE PERAWAT</td>
            </tr>
            <tr>
              <td align="center">Gawat Darurat</td>
              <td align="center">Resiko</td>
              <td align="center">Gawat Darurat</td>
              <td align="center">Resiko</td>
            </tr>
            <tr>
              <td>
                <input type="number" style="text-align: right;" align="right" name="m_gawat_darurat" class="form-control" required>
              </td>
              <td>
                <input type="number" style="text-align: right;" align="right" name="m_resiko" class="form-control" required>
              </td>
              <td>
                <input type="number" style="text-align: right;" align="right" name="p_gawat_darurat" class="form-control" required>
              </td>
              <td>
                <input type="number" style="text-align: right;" align="right" name="p_resiko" class="form-control" required>
              </td>
            </tr>
          </table>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="submit" form="form_baru_ruang" class="btn btn-sm btn-secondary bprev">SIMPAN</button>
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">TUTUP</button>
        </div>
      </div>
    </div>    
  </div>
</div>

<!--Edit RUang-->
<div class="modal fade" id="modal_edit_ruang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">EDIT RUANG</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal fprev" id="form_edit_ruang" method="POST" action="{{ route('ruang_edit') }}">
        @csrf
          <input type="hidden" name="id" id="edit_id">

          <div class="mb-1">
            <div class="title mb-1">Nama Ruang</div>
            <input type="text" class="form-control" name="ruang" id="edit_ruang" required autofocus>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Rawat Jalan</div>
            <select class="form-control" name="jalan" id="edit_jalan">
              <option value="0">TIDAK</option>
              <option value="1">YA</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Rawat Inap</div>
            <select class="form-control" name="inap" id="edit_inap">
              <option value="0">TIDAK</option>
              <option value="1">YA</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Terima Pasien</div>
            <select class="form-control" name="terima_pasien" id="edit_terima_pasien">
              <option value="0">TIDAK</option>
              <option value="1">YA</option>
            </select>
          </div>

          <div class="mb-1">
            <div class="title mb-1">Pasien Luar</div>
            <select class="form-control" name="nonpasien" id="edit_nonpasien">
              <option value="0">TIDAK</option>
              <option value="1">YA</option>
            </select>
          </div>

          <table width="100%" class="table table-bordered" style="font-size: 3vw;">
            <tr style="background-color: #f1f1f1;">
              <td colspan="2" align="center">INDEKS SCORE MEDIS</td>
              <td colspan="2" align="center">INDEKS SCORE PERAWAT</td>
            </tr>
            <tr>
              <td align="center">Gawat Darurat</td>
              <td align="center">Resiko</td>
              <td align="center">Gawat Darurat</td>
              <td align="center">Resiko</td>
            </tr>
            <tr>
              <td>
                <input type="number" style="text-align: right;" name="m_gawat_darurat" id="edit_m_gawat_darurat" class="form-control" required>
              </td>
              <td>
                <input type="number" style="text-align: right;" name="m_resiko" id="edit_m_resiko" class="form-control" required>
              </td>
              <td>
                <input type="number" style="text-align: right;" name="p_gawat_darurat" id="edit_p_gawat_darurat" class="form-control" required>
              </td>
              <td>
                <input type="number" style="text-align: right;" name="p_resiko" id="edit_p_resiko" class="form-control" required>
              </td>
            </tr>
          </table>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="submit" form="form_edit_ruang" class="btn btn-sm btn-secondary bprev">SIMPAN</button>
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">TUTUP</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_layanan_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">EDIT LAYANAN</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal fprev" id="form_layanan_edit" method="POST" action="{{ route('ruang_layanan_edit') }}">
        @csrf
          <input type="hidden" name="id" id="id_layanan_edit">

          <select class="form-control" name="id_jasa" id="id_layanan_jasa_edit" size="15" required multiple>
            @foreach($layanan as $lay)
            <option value="{{ $lay->id }}">{{ $lay->jasa }}</option>
            @endforeach
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="submit" form="form_layanan_edit" class="btn btn-sm btn-secondary bprev">SIMPAN</button>
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">TUTUP</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_layanan_baru" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">TAMBAH LAYANAN</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body user-data-card">
        <form class="form-horizontal fprev" id="form_layanan_baru" method="POST" action="{{ route('ruang_layanan_baru') }}">
        @csrf
          <input type="hidden" name="id_ruang" id="id_ruang_layanan">

          <select class="form-control" name="id_jasa[]" id="id_jasa_layanan_baru" size="15" required multiple>
            @foreach($layanan as $lay)
            <option value="{{ $lay->id }}">{{ $lay->jasa }}</option>
            @endforeach
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="submit" form="form_layanan_baru" class="btn btn-sm btn-secondary bprev">SIMPAN</button>
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">TUTUP</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function() {
      $('.btn-edit_ruang').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{route('ruang_editing')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#edit_id').val(data.id);
            $('#edit_ruang').val(data.ruang);
            $('#edit_jalan').val(data.jalan);
            $('#edit_inap').val(data.inap);
            $('#edit_terima_pasien').val(data.terima_pasien);
            $('#edit_nonpasien').val(data.nonpasien);
            $('#edit_m_gawat_darurat').val(data.m_gawat_darurat);
            $('#edit_m_resiko').val(data.m_resiko);
            $('#edit_p_gawat_darurat').val(data.p_gawat_darurat);
            $('#edit_p_resiko').val(data.p_resiko);
            $('#modal_edit_ruang').modal('show');
          }
        });
      });
    });

    $(document).ready(function() {
      $('.edit_layanan').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{route('ruang_layanan_editing')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#id_layanan_edit').val(data.id);
            $('#id_layanan_jasa_edit').val(data.id_jasa);
            $('#modal_layanan_edit').modal('show');
          }
        });
      });
    });

    $(document).ready(function() {
      $('.layanan_baru').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{route('ruang_layanan_baru_show')}}?id="+id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            $('#id_ruang_layanan').val(data);
            $('#modal_layanan_baru').modal('show');
          }
        });
      });
    });

    $(document).ready(function() {
      $('.aktif').on("click",function() {
        var id = $(this).attr('data-id');
        $.ajax({
          url : "{{ route('ruang_jenis') }}?id="+id,
          type: "GET",
          dataType: "JSON"          
        });
      });
    });
  </script>
@endsection