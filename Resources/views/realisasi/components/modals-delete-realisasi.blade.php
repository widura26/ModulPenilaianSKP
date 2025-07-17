<button {{ in_array($item->rencanakerja->status_realisasi, ['Sudah Dievaluasi', 'Belum Dievaluasi']) ? 'disabled' : '' }} type="button" class="btn btn-danger" data-toggle="modal" data-target="#realisasi-delete-{{ $item->id }}">
    <i class="nav-icon fas fa-ban"></i>
</button>

<div class="modal fade" id="realisasi-delete-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        {{-- <form class="model-content" action="{{ url('/skp/realisasi/delete/' . $item->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <p>Apakah anda ingin menghapus realisasi?</p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger" data-toggle="modal" data-target="#realisasi">
                    <i class="nav-icon fas fa-ban "></i>
                </button>
            </div>
        </form> --}}
        <form class="modal-content" action="{{ url('/skp/realisasi/' . $periode->id .  '/delete/' . $item->id) }}" method="POST">
            @csrf
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah anda ingin menghapus realisasi?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                <button type="submit" class="btn btn-danger">Ya</button>
            </div>
        </form>
    </div>
</div>
