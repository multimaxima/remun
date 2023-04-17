@extends('layouts.content')
@section('title','Edit Tarif')

@section('judul')
  @if(count($tarif) > 0 && $id_jenis && $id_ruang && $id_ruang_sub || $id_dpjp)
  <div class="btn-group float-right" role="group" aria-label="Basic example">  
    <button class="btn btn-primary" data-toggle="modal" data-target="#kolektif">
      <i class="fa fa-users"></i> UBAH KOLEKTIF
    </button>    
  </div>
  @endif

  <h4 class="page-title"> <i class="dripicons-gear"></i> @yield('title')</h4>
@endsection

@section('content')
<div class="wrapper">
  <div class="col-12" style="padding: 0 50px;">
    <div id="accordion">
      <div class="card">
        <div class="card-header p-3" id="headingOne">
          <h6 class="m-0">
            <a href="#collapseOne" class="text-dark" data-toggle="collapse" aria-expanded="true" aria-controls="collapseOne">
              FILTER DATA
            </a>
          </h6>
        </div>
        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
          <div class="card-body">
            <form class="form-horizontal" method="GET" action="{{ route('edit_tarif') }}">
            @csrf
          
            <div class="form-group row">
              <label class="control-label col-3">Tanggal</label>
              <div class="form-inline col-9">
              <input type="date" class="form-control" name="awal" value="{{ $awal }}" required autofocus>

              <label class="control-label" style="margin: 0 10px;">s/d</label>
              <input type="date" class="form-control" name="akhir" value="{{ $akhir }}" required>
              </div>
            </div>

            <div class="form-group row">
              <label class="control-label col-3">Ruang Perawatan</label>
              <div class="col-6">
                <select class="form-control" name="id_ruang">
                  <option value=""></option>
                  @foreach($ruang as $rng)
                    <option value="{{ $rng->id }}" {{ $id_ruang == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
                  @endforeach
                </select>          
              </div>
            </div>

            <div class="form-group row">
              <label class="control-label col-3">Ruang Tindakan</label>
              <div class="col-6">
                <select class="form-control" name="id_ruang_sub">
                  <option value=""></option>
                  @foreach($ruang as $rng)
                    <option value="{{ $rng->id }}" {{ $id_ruang_sub == $rng->id? 'selected' : null }}>{{ strtoupper($rng->ruang) }}</option>
                  @endforeach
                </select>          
              </div>
            </div>

            <div class="form-group row">
              <label class="control-label col-3">Jenis Pasien</label>
              <div class="col-6">
                <select class="form-control" name="id_jenis">
                  <option value=""></option>
                  @foreach($jenis as $jenis)
                    <option value="{{ $jenis->id }}" {{ $id_jenis == $jenis->id? 'selected' : null }}>{{ strtoupper($jenis->jenis_pasien) }}</option>
                  @endforeach
                </select>          
              </div>
            </div>

            <div class="form-group row">
              <label class="control-label col-3">Nama DPJP</label>
              <div class="col-6">
                <select class="form-control" name="id_dpjp">
                  <option value=""></option>
                  @foreach($dpjp as $dpjp)
                    <option value="{{ $dpjp->id }}" {{ $id_dpjp == $dpjp->id? 'selected' : null }}>{{ strtoupper($dpjp->nama) }}</option>
                  @endforeach
                </select>          
              </div>
            </div>

            <div class="form-group row">
              <div class="col-6 offset-3">
                <button type="submit" class="btn btn-primary btn-sm">TAMPILKAN</button>
              </div>
            </div>
          </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>  

<div class="wrapper" style="margin-top: 10px;">
  <div class="col-12" style="padding: 0 50px;">
    <div class="card m-b-30 konten">
      <div class="card-body">
        <div style="margin-top: 10px; display:none;" id="process" style="display:none;">
          <center>
            <i>Proses perhitungan sedang berjalan....</i>
            <img src="/images/progress.gif" style="height: 80px;">
          </center>
        </div>    

        <table width="100%" id="tabel" class="table table-hover table-striped" style="font-size: 13px;">
          <thead>
            <th></th>
            <th>NAMA PASIEN</th>
            <th>MR</th>
            <th>JENIS PASIEN</th>
            <th>DPJP</th>
            <th>LAYANAN</th>
            <th>RUANG PERAWATAN</th>
            <th>RUANG TINDAKAN</th>
            <th>TARIF</th>
          </thead>
          <tbody>
            @foreach($tarif as $tarif)
<div class="modal fade" id="edit_data{{ $tarif->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Tarif</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal fprev" id="form_edit{{ $tarif->id }}" method="POST" action="{{ route('edit_tarif_simpan') }}">
        @csrf
          <input type="hidden" name="id" value="{{ Crypt::encrypt($tarif->id) }}">          

          <div class="form-group row">
            <label class="control-label col-4">Tarif (Rp.)</label>
            <div class="col-5">
              <input type="number" class="form-control" name="tarif" step="0.01" required autofocus value="{{ $tarif->tarif }}">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">                
        <button type="submit" form="form_edit{{ $tarif->id }}" class="btn btn-primary bprev">
          <i class="fa fa-save"></i> SIMPAN
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fa fa-times"></i> TUTUP
        </button>
      </div>
    </div>
  </div>
</div>
            <tr>
              <td class="min">
                @if($tarif->id_jasa <> 40)
                <a href="#" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#edit_data{{ $tarif->id }}">
                  <i class="fa fa-edit"></i>
                </a>
                @else
                <button class="btn btn-primary btn-xs" disabled>
                  <i class="fa fa-edit"></i>
                </button>
                @endif

                <a href="{{ route('edit_tarif_hapus',Crypt::encrypt($tarif->id)) }}" class="btn btn-danger btn-xs" onclick="return confirm('Hapus tarif ?')">
                  <i class="fa fa-times"></i>
                </a>
              </td>
              <td>
                <a href="#" data-toggle="modal" data-target="#edit_data{{ $tarif->id }}">
                  {{ strtoupper($tarif->nama) }}
                </a>
              </td>
              <td>
                <a href="#" data-toggle="modal" data-target="#edit_data{{ $tarif->id }}">
                  {{ strtoupper($tarif->no_mr) }}
                </a>
              </td>
              <td>
                <a href="#" data-toggle="modal" data-target="#edit_data{{ $tarif->id }}">
                  {{ strtoupper($tarif->jenis_pasien) }}
                </a>
              </td>
              <td>
                <a href="#" data-toggle="modal" data-target="#edit_data{{ $tarif->id }}">
                  {{ strtoupper($tarif->dpjp) }}
                </a>
              </td>
              <td>
                <a href="#" data-toggle="modal" data-target="#edit_data{{ $tarif->id }}">
                  {{ strtoupper($tarif->jasa) }}
                </a>
              </td>
              <td>
                <a href="#" data-toggle="modal" data-target="#edit_data{{ $tarif->id }}">
                  {{ strtoupper($tarif->ruang) }}
                </a>
              </td>
              <td>
                <a href="#" data-toggle="modal" data-target="#edit_data{{ $tarif->id }}">
                  {{ strtoupper($tarif->ruang_sub) }}
                </a>
              </td>
              <td align="right">
                <a href="#" data-toggle="modal" data-target="#edit_data{{ $tarif->id }}">
                  {{ number_format($tarif->tarif,0) }}
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>      
    </div>
  </div>
</div>  

<div class="modal fade" id="kolektif" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <form class="form-horizontal fprev" id="kolektif_simpan">
        @csrf
          <input type="hidden" name="awal" value="{{ $awal }}">
          <input type="hidden" name="akhir" value="{{ $akhir }}">
          <input type="hidden" name="id_ruang" value="{{ $id_ruang }}">
          <input type="hidden" name="id_ruang_sub" value="{{ $id_ruang_sub }}">
          <input type="hidden" name="id_jenis" value="{{ $id_jenis }}">
          <input type="hidden" name="id_dpjp" value="{{ $id_dpjp }}">

          <div class="form-group row">
            <label class="control-label col-4">Perubahan</label>
            <div class="col-4">              
              <div class="input-group">
                <input type="number" class="form-control" name="persen" step="0.01" required autofocus>
                <div class="input-group-append">
                  <span class="input-group-text" id="basic-addon1">%</span>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label class="control-label col-4">Besaran Tarif</label>
            <div class="col-6">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">Rp.</span>
                </div>
                <input type="number" class="form-control" name="dari" step="0.01" required>
              </div>              
            </div>
          </div>

          <div class="form-group row">
            <label class="control-label col-4">s/d</label>
            <div class="col-6">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">Rp.</span>
                </div>
                <input type="number" class="form-control" name="sampai" step="0.01" required>
              </div>              
            </div>
          </div>

          <div class="form-group row" style="margin-top: 10px;">
            <div class="col-8 offset-4">
              <button type="submit" class="btn btn-primary btn-sm bprev">
                <i class="fa fa-check" style="margin-right: 10px;"></i> HITUNG
              </button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>  
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function(){  
      $('#kolektif_simpan').on('submit', function(event){
        event.preventDefault();
        var count_error = 0;

        if(count_error == 0){
          $.ajax({
            url:"{{ route('edit_tarif_kolektif') }}",
            method:"POST",
            data:$(this).serialize(),
            beforeSend:function() {
              $('#kolektif').modal('hide');
              $('#process').css('display', 'block');
            },
            success:function(data){
              $('#process').css('display', 'none');
              location.reload();
            }
          })
        } else {
          return false;
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
          "targets": 0
        }],        
        "order": [[ 1, "asc" ]],
        scrollY:        "400px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,        
      });
    });
  </script>
@endsection