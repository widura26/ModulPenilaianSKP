<button type="button" class="btn btn-danger btn-sm btn-hapus-indikator" 
    data-id="{{ $indikator->id }}" title="Hapus Indikator" data-toggle="modal" data-target="#modalHapusIndikator">
    <i class="fas fa-trash"></i>
</button>
<div class="modal fade" id="modalHapusIndikator" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="POST" id="formHapusIndikator">
        @csrf
        @method('DELETE')
        <div class="modal-content">
            <div class="modal-header">Konfirmasi Hapus</div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus indikator ini beserta indikator manual yang terkait?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </form>
  </div>
</div>
