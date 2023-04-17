@extends('tarif_layanan')

@section('tarif')
@if($jns)
<div class="wrapper" style="margin-top: -20px;">
  <div class="row" style="padding: 0 50px;">
    <div class="col-3">
      <div class="card m-b-30 konten">
        <div class="card-body">
          <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#perhitungan_baru">
            TAMBAH DATA
          </button>
          <table width="100%" id="tabel1" class="table table-striped table-hover">
            <thead>
              <th></th>
              <th>JASA LAYANAN</th>
            </thead>
            <tbody>
              @foreach($perhitungan as $perhitungan)
<div class="modal fade" id="perhitungan_edit{{ $perhitungan->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Tarif</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal fprev" id="edit_perhitungan{{ $perhitungan->id }}" method="POST" action="{{ route('tarif_1_edit') }}">
        @csrf
          <input type="hidden" name="id" value="{{ Crypt::encrypt($perhitungan->id) }}">

          <div class="form-group row">
            <label class="control-label col-4">Jasa</label>
            <div class="col-7">
              <select class="form-control" name="id_jasa">
                @foreach($jasa as $jas)
                  <option value="{{ $jas->id }}" {{ $perhitungan->id_jasa == $jas->id? 'selected' : null }}>{{ $jas->jasa }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">                
        <button type="submit" form="edit_perhitungan{{ $perhitungan->id }}" class="btn btn-primary bprev">
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
                  <button class="btn btn-primary btn-xs" title="Edit" data-toggle="modal" data-target="#perhitungan_edit{{ $perhitungan->id }}">
                    <i class="fa fa-edit"></i>
                  </button>
                  <a href="{{ route('tarif_1_hapus',Crypt::encrypt($perhitungan->id)) }}" class="btn btn-danger btn-xs" title="Hapus" onclick="return confirm('Hapus data {{ $perhitungan->jasa }} ?')">
                    <i class="fa fa-times"></i>
                  </a>
                </td>              
                <td>
                  <a href="{{ route('tarif_1',Crypt::encrypt($perhitungan->id)) }}">
                    {{ $perhitungan->jasa }}
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
</div>
@endif     
@endsection