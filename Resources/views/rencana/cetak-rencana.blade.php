<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#cetakRencanaSkp">
  <!-- <i class="nav-icon fas fa-plus "></i> -->Cetak Rencana SKP
</button>
<div class="modal fade" id="cetakRencanaSkp" tabindex="-1" role="dialog" aria-labelledby="cetakRencanaSkpTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form method="POST" class="modal-content" action="">
      @csrf
      <div class="modal-header">Pengaturan Halaman</div>
      <div class="modal-body">
        <div class="form-group row">
          <label for="text" class="col-sm-3 col-form-label">Tanggal Cetak</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="text" placeholder="Contoh:Jakarta, 01 Juli 2025">
          </div>
        </div>
        <div class="form-group row">
          <label for="text" class="col-sm-3 col-form-label">Margin Atas</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="text">
          </div>
        </div>
        <div class="form-group row">
          <label for="text" class="col-sm-3 col-form-label">Margin Bawah</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="text">
          </div>
        </div>
        <div class="form-group row">
          <label for="text" class="col-sm-3 col-form-label">Margin Kanan</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="text">
          </div>
        </div>
        <div class="form-group row">
          <label for="text" class="col-sm-3 col-form-label">Margin Kiri</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="text">
          </div>
        </div>
        <fieldset class="form-group row">
          <legend class="col-form-label col-sm-3 float-sm-left pt-0">Orientasi Halaman</legend>
          <div class="col-sm-8">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="option1" >
              <label for="gridRadios1">
                Portrait
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="option2">
              <label for="gridRadios2">
                Landscape
              </label>
            </div>
          </div>
        </fieldset>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Tambah</button>
      </div>
    </form>
  </div>
</div>