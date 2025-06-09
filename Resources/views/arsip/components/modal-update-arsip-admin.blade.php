<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">Update Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="updateForm">
          <input type="hidden" name="id" id="dataId">
          <!-- Tambahkan field lain sesuai kebutuhan -->
          <div class="mb-3">
            <label for="fieldName" class="form-label">Nama</label>
            <input type="text" class="form-control" id="fieldName" name="name">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="updateForm" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>
