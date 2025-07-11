<button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editIndikatorhasilKerjaModalUtama{{ $item->id }}">
    <i class="nav-icon fas fa-pen "></i>
</button>
<div class="modal fade" id="editIndikatorhasilKerjaModalUtama{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="editIndikatorhasilKerjaModalUtamaTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" class="modal-content" action="">
            @csrf
            <div class="modal-header">Edit Indikator Hasil Kerja Utama</div>
            <div class="modal-body">
                <input type="hidden" id="edit-utama-id">
                <!-- Indikator LAMA -->
                <div class="form-group">
                    <label>Indikator Lama</label>
                    <input type="text" class="form-control" value="{{ $item->indikator->first()->deskripsi ?? '-' }}" disabled>
                </div>

                <!-- Indikator BARU -->
                <div class="form-group">
                    <label>Indikator Baru</label>
                    <input type="text" class="form-control" name="indikator" value="{{ $item->indikator->first()->deskripsi ?? '' }}">
                </div>

                <!-- <div class="form-group">
                        <label>Deskripsi</label>
                        <input type="text" class="form-control" name="deskripsi" id="edit-utama-deskripsi">
                    </div> -->
                <!-- <div class="form-group">
                        <label>Indikator (pisahkan dengan `;`)</label>
                        <textarea class="form-control" name="indikators" id="edit-utama-indikators" rows="3"></textarea>
                    </div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
        </form>
    </div>
</div>