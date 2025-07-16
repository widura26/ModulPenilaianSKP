<!-- <button type="button" class="btn btn-warning btn-sm" data-id="{{ $item->id }}" data-toggle="modal" data-target="#editIndikatorhasilKerjaModalUtama">
    <i class="nav-icon fas fa-pen "></i>
</button>
<div class="modal fade" id="editIndikatorhasilKerjaModalUtama" tabindex="-1" role="dialog" aria-labelledby="editIndikatorhasilKerjaModalUtamaTitle">
    <div class="modal-dialog" role="document">
        <form method="POST" class="modal-content" action="{{ url('/skp/rencana/edit-indikator-utama/. $item->id') }}">
            @csrf
            @method('PUT')
            <div class="modal-header">Edit Indikator Hasil Kerja Utama</div>
            <div class="modal-body">
                <input type="hidden" id="edit-utama-id">
                
                <div class="form-group">
                    <label>Indikator Lama</label>
                    <input type="text" class="form-control" value="{{ $item->indikator->first()->deskripsi ?? '-' }}" disabled>
                </div>

                
                <div class="form-group">
                    <label>Indikator Baru</label>
                    <input type="text" class="form-control" name="indikator" value="">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
        </form>
    </div>
</div> -->

<button type="button"
    class="btn btn-warning btn-sm btn-edit-indikator-utama"
    data-id="{{ $item->indikator->first()->id ?? '' }}"
    data-deskripsi="{{ $item->indikator->first()->deskripsi ?? '' }}"
    data-toggle="modal"
    data-target="#editIndikatorhasilKerjaModalUtama">
    <i class="nav-icon fas fa-pen"></i>
</button>

<div class="modal fade" id="editIndikatorhasilKerjaModalUtama" tabindex="-1" role="dialog" aria-labelledby="editIndikatorhasilKerjaModalUtamaTitle">
    <div class="modal-dialog" role="document">
        <form method="POST" class="modal-content" id="formEditIndikatorUtama">
            @csrf
            @method('PUT')
            <div class="modal-header">Edit Indikator Hasil Kerja Utama</div>
            <div class="modal-body">
                <input type="hidden" id="edit-indikator-id" name="id">

                <div class="form-group">
                    <label>Indikator Lama</label>
                    <input type="text" class="form-control" id="indikator-lama" disabled>
                </div>

                <div class="form-group">
                    <label>Indikator Baru</label>
                    <input type="text" class="form-control" name="indikator" id="indikator-baru" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

