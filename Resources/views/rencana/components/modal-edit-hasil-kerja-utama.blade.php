<button type="button" class="btn btn-warning btn-sm btn-edit-utama" data-id="{{ $item->id }}" data-toggle="modal" data-target="#editModalUtama">
    <i class="fas fa-pen"></i>
</button>
<div class="modal fade" id="editModalUtama" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ url('/skp/rencana/update-hasil-kerja-utama/. $item->id') }}" id="formEditUtama">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Hasil Kerja Utama</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-utama-id">
                    <!-- DESKRIPSI LAMA -->
                    <div class="form-group">
                        <label>Deskripsi Lama</label>
                        <input type="text" class="form-control" id="edit-utama-deskripsi-lama" disabled>
                    </div>

                    <!-- DESKRIPSI BARU -->
                    <div class="form-group">
                        <label>Deskripsi Baru</label>
                        <input type="text" class="form-control" name="deskripsi" id="edit-utama-deskripsi-baru">
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
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>