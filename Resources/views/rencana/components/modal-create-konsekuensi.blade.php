<!-- Tombol trigger -->
<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahKonsekuensi">
  <i class="fas fa-plus"></i>
</button>

<!-- Modal -->
<div class="modal fade" id="modalTambahKonsekuensi" role="dialog" tabindex="-1" aria-labelledby="modalTambahKonsekuensiLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form class="modal-content" action="{{ route('hasil-kerja.store', $rencana->id ?? '') }}" method="POST"> <!-- Ubah sesuai route -->
      @csrf
      <div class="modal-header">Tambah Lampiran</div>
      <div class="modal-body">

        <div class="form-group">
          <label for="indikator">Konsekuensi</label>
          <textarea class="form-control" id="indikator" rows="3" name="indikators"></textarea>
          <small id="passwordHelpBlock" class="form-text text-muted">
            Tips: Untuk menambah lebih dari satu lampiran, gunakan tanda ; sebagai pemisah
          </small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Tambah</button>
      </div>
    </form>
  </div>
</div>