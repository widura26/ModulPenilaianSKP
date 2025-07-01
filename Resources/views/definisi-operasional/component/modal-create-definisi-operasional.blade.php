<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#definisiOperasionalModal">Tambah Data</button>

<!-- Modal -->
<div class="modal fade" id="definisiOperasionalModal" tabindex="-1" aria-labelledby="definisiOperasionalModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ url('/skp/definisi-operasional/store/') }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="definisiOperasionalModalLabel">Tambah Manual Indikator Utama</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <label for="topik">Topik</label>
          <input type="text" name="topik" class="form-control" required>
        </div>

        <div class="form-group">
          <label for="sub_topik">Sub Topik</label>
          <input type="text" name="sub_topik" class="form-control" required>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>