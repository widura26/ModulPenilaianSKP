<button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal-{{ $item->id }}">
  <i class="fas fa-edit"></i>
</button>

<!-- Modal Edit -->
<div class="modal fade" id="editModal-{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ url('/skp/definisi-operasional/update/'. $item->id) }}" class="modal-content">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Definisi Operasional</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <label for="topik" class="text-left d-block">Topik</label>
          <input type="text" name="topik" class="form-control" value="{{ $item->topik }}" required>
        </div>

        <div class="form-group">
          <label for="sub_topik" class="text-left d-block">Sub Topik</label>
          <input type="text" name="sub_topik" class="form-control" value="{{ $item->sub_topik }}" required>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>