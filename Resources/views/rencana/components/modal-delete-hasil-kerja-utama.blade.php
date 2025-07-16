<button type="button" class="btn btn-danger btn-sm btn-hapus-hasil-kerja" 
    data-id="{{ $item->id }}" title="Hapus Hasil Kerja" data-toggle="modal" data-target="#modalHapusHasilKerja">
    <i class="fas fa-trash"></i>
</button>
<div class="modal fade" id="modalHapusHasilKerja" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="POST" id="formHapusHasilKerja">
        @csrf
        @method('DELETE')
        <div class="modal-content">
            <div class="modal-header">Konfirmasi Hapus</div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus hasil kerja utama ini beserta semua indikatornya?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </form>
  </div>
</div>
