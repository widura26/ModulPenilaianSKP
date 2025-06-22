<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edithasilKerjaTambahanModal">
    <i class="nav-icon fas fa-plus "></i>
</button>
<div class="modal fade" id="edithasilKerjaTambahanModal" tabindex="-1" role="dialog" aria-labelledby="edithasilKerjaTambahanModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" class="modal-content" action="{{ url('/skp/rencana/store-hasil-kerja-tambahan/' . (is_null($rencana) ? '' : $rencana->id)) }}">
            @csrf
            <div class="modal-header">Tambah Hasil Kerja Tambahan</div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="hasil-kerja">Hasil Kerja</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="nav-icon fas fa-copy "></i>
                            </button>
                        </div>
                        <input name="deskripsi" type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon1">
                    </div>
                </div>

                <div class="form-group">
                  <label for="indikator">Indikator</label>
                  <textarea class="form-control" id="indikator" rows="3" name="indikators"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
        </form>
    </div>
</div>
