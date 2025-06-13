<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah-arsip">
    <i class="nav-icon fas fa-plus "></i> Tambah Arsip
</button>
<div class="modal fade" id="tambah-arsip" tabindex="-1" role="dialog" aria-labelledby="tambah-arsipTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" class="modal-content" action="{{ url('/skp/arsip-skp/store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">Tambah Arsip</div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="peran-select">Jenis Arsip</label>
                    <select class="form-control" name="jenis_arsip">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Rencana">Rencana</option>
                        <option value="Evaluasi">Evaluasi</option>
                        <option value="Dokumen Evaluasi">Dokumen Evaluasi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="hasil-kerja-diintervensi">File</label>
                    <input type="file" class="form-control" name="file_arsip">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
