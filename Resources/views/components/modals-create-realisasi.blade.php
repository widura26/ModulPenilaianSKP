<button {{ in_array($item->rencanakerja->status_realisasi, ['Sudah Dievaluasi', 'Belum Dievaluasi']) ? 'disabled' : '' }} type="button" class="btn btn-primary" data-toggle="modal" data-target="#realisasi-{{ $item->id }}">
    <i class="nav-icon fas fa-pencil-alt "></i>
</button>

<div class="modal fade" id="realisasi-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content needs-validation" novalidate action="{{ url('penilaian/realisasi/update-realisasi/' . $item['id']) }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Isi Realisasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center">
                    <div class="mr-1" style="width:30%">Hasil Kerja</div>
                    <div class="flex-grow" style="width: 100%">
                    <input type="text" class="form-control" id="inputPassword" disabled placeholder="Hasil Kerja" value="{{ $item['deskripsi'] }}">
                    </div>
                </div>
                <div class="d-flex align-items-start mt-2">
                    <div class="mr-1" style="width:30%">Realisasi</div>
                    <div class="" style="width: 100%">
                        <textarea name="realisasi" class="form-control" placeholder="Realisasi" required style="height: 70px; width: 100%; padding: 10px; overflow-y: auto; resize: vertical;"></textarea>
                        <div class="invalid-feedback">Realisasi tidak boleh kosong</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
