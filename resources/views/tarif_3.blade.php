@extends('tarif_layanan')

@section('tarif')
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
              @if($perhitungan->id == $hitung->id)
              <tr style="background-color: yellow;">
              @else
              <tr>
              @endif
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

    <div class="col-3">
      <div class="card m-b-30 konten">
        <div class="card-body">
          <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#perhitungan_1_baru">
            TAMBAH DATA
          </button>
          <table width="100%" id="tabel2" class="table table-striped table-hover">
            <thead>
              <th></th>
              <th>REKENING</th>
              <th>NILAI</th>
            </thead>
            <tbody>
              @foreach($perhitungan_1 as $perhitungan_1)
<div class="modal fade" id="perhitungan_1_edit{{ $perhitungan_1->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Tarif</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal fprev" id="edit_perhitungan_1{{ $perhitungan_1->id }}" method="POST" action="{{ route('tarif_2_edit') }}">
        @csrf
          <input type="hidden" name="id" value="{{ Crypt::encrypt($perhitungan_1->id) }}">

          <div class="form-group row">
            <label class="control-label col-4">Rekening</label>
            <div class="col-7">
              <select class="form-control" name="id_rekening" required autofocus>
                @foreach($rekening as $rek)
                  <option value="{{ $rek->id }}" {{ $perhitungan_1->id_rekening == $rek->id? 'selected' : null }}>{{ $rek->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="control-label col-4">Nilai</label>
            <div class="col-7">
              <input type="number" name="nilai" step="0.01" class="form-control" value="{{ $perhitungan_1->nilai }}" required>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">                
        <button type="submit" form="edit_perhitungan_1{{ $perhitungan_1->id }}" class="btn btn-primary bprev">
          <i class="fa fa-save"></i> SIMPAN
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fa fa-times"></i> TUTUP
        </button>
      </div>
    </div>
  </div>
</div>
              @if($perhitungan_1->id == $hitung_1->id)
                <tr style="background-color: yellow;">
              @else
                <tr>
              @endif
                <td class="min">
                  <button class="btn btn-primary btn-xs" title="Edit" data-toggle="modal" data-target="#perhitungan_1_edit{{ $perhitungan_1->id }}">
                    <i class="fa fa-edit"></i>
                  </button>
                  <a href="{{ route('tarif_2_hapus',Crypt::encrypt($perhitungan_1->id)) }}" class="btn btn-danger btn-xs" title="Hapus" onclick="return confirm('Hapus data {{ $perhitungan_1->nama }}?')">
                    <i class="fa fa-times"></i>
                  </a>
                </td>              
                <td>
                  <a href="{{ route('tarif_2',Crypt::encrypt($perhitungan_1->id)) }}">
                    {{ $perhitungan_1->nama }}
                  </a>
                </td>
                <td align="right">
                  <a href="{{ route('tarif_2',Crypt::encrypt($perhitungan_1->id)) }}">
                    {{ $perhitungan_1->nilai }} %
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>      
      </div>
    </div>

    <div class="col-3">
      <div class="card m-b-30 konten">
        <div class="card-body">
          <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#perhitungan_2_baru">
            TAMBAH DATA
          </button>
          <table width="100%" id="tabel3" class="table table-striped table-hover">
            <thead>
              <th></th>
              <th>REKENING</th>
              <th>NILAI</th>
            </thead>
            <tbody>
              @foreach($perhitungan_2 as $perhitungan_2)
<div class="modal fade" id="perhitungan_2_edit{{ $perhitungan_2->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Tarif</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal fprev" id="edit_perhitungan_2{{ $perhitungan_2->id }}" method="POST" action="{{ route('tarif_3_edit') }}">
        @csrf
          <input type="hidden" name="id" value="{{ Crypt::encrypt($perhitungan_2->id) }}">

          <div class="form-group row">
            <label class="control-label col-4">Rekening</label>
            <div class="col-7">
              <select class="form-control" name="id_rekening" required autofocus>
                @foreach($rekening as $rek)
                  <option value="{{ $rek->id }}" {{ $perhitungan_2->id_rekening == $rek->id? 'selected' : null }}>{{ $rek->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="control-label col-4">Nilai</label>
            <div class="col-7">
              <input type="number" name="nilai" step="0.01" class="form-control" value="{{ $perhitungan_2->nilai }}" required>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">                
        <button type="submit" form="edit_perhitungan_2{{ $perhitungan_2->id }}" class="btn btn-primary bprev">
          <i class="fa fa-save"></i> SIMPAN
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fa fa-times"></i> TUTUP
        </button>
      </div>
    </div>
  </div>
</div>
              @if($perhitungan_2->id == $hitung_2->id)
                <tr style="background-color: yellow;">
              @else
                <tr>
              @endif              
                <td class="min">
                  <button class="btn btn-primary btn-xs" title="Edit" data-toggle="modal" data-target="#perhitungan_2_edit{{ $perhitungan_2->id }}">
                    <i class="fa fa-edit"></i>
                  </button>
                  <a href="{{ route('tarif_3_hapus',Crypt::encrypt($perhitungan_2->id)) }}" class="btn btn-danger btn-xs" title="Hapus" onclick="return confirm('Hapus data ?')">
                    <i class="fa fa-times"></i>
                  </a>
                </td>              
                <td>
                  <a href="{{ route('tarif_3',Crypt::encrypt($perhitungan_2->id)) }}">
                    {{ $perhitungan_2->nama }}
                  </a>
                </td>
                <td align="right">
                  <a href="{{ route('tarif_3',Crypt::encrypt($perhitungan_2->id)) }}">
                    {{ $perhitungan_2->nilai }} %
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>      
      </div>
    </div>

    <div class="col-3">
      <div class="card m-b-30 konten">
        <div class="card-body">
          <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#perhitungan_3_baru">
            TAMBAH DATA
          </button>
          <table width="100%" id="tabel4" class="table table-striped table-hover">
            <thead>
              <th></th>
              <th>REKENING</th>
              <th>NILAI</th>
            </thead>
            <tbody>
              @foreach($perhitungan_3 as $perhitungan_3)
<div class="modal fade" id="perhitungan_3_edit{{ $perhitungan_3->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Tarif</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal fprev" id="edit_perhitungan_3{{ $perhitungan_3->id }}" method="POST" action="{{ route('tarif_4_edit') }}">
        @csrf
          <input type="hidden" name="id" value="{{ Crypt::encrypt($perhitungan_3->id) }}">

          <div class="form-group row">
            <label class="control-label col-4">Rekening</label>
            <div class="col-7">
              <select class="form-control" name="id_rekening" required autofocus>
                @foreach($rekening as $rek)
                  <option value="{{ $rek->id }}" {{ $perhitungan_3->id_rekening == $rek->id? 'selected' : null }}>{{ $rek->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="control-label col-4">Nilai</label>
            <div class="col-7">
              <input type="number" name="nilai" step="0.01" class="form-control" value="{{ $perhitungan_3->nilai }}" required>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">                
        <button type="submit" form="edit_perhitungan_3{{ $perhitungan_3->id }}" class="btn btn-primary bprev">
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
                  <button class="btn btn-primary btn-xs" title="Edit" data-toggle="modal" data-target="#perhitungan_3_edit{{ $perhitungan_3->id }}">
                    <i class="fa fa-edit"></i>
                  </button>
                  <a href="{{ route('tarif_4_hapus',Crypt::encrypt($perhitungan_3->id)) }}" class="btn btn-danger btn-xs" title="Hapus" onclick="return confirm('Hapus data ?')">
                    <i class="fa fa-times"></i>
                  </a>
                </td>              
                <td>
                  <a href="#">
                    {{ $perhitungan_3->nama }}
                  </a>
                </td>
                <td align="right">
                  <a href="#">
                    {{ $perhitungan_3->nilai }} %
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
@endsection