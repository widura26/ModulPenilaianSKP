@foreach ($rencana->hasilKerja as $hasilKerja)
<!-- Tombol trigger -->
<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahKonsekuensi">
  <i class="fas fa-plus"></i>
</button>

<!-- Modal -->
<div class="modal fade" id="modalTambahKonsekuensi" role="dialog" tabindex="-1" aria-labelledby="modalTambahKonsekuensiLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form class="modal-content" action="{{ url('/skp/rencana/store-lampiran/') }}" method="POST">
      @csrf
      <div class="modal-header">Tambah Lampiran</div>
      <input type="hidden" name="hasil_kerja_id" value="{{ $hasilKerja->id }}">
      <input type="hidden" name="jenis_lampiran" value="Konsekuensi">
      <div class="modal-body">

        <div class="form-group">
          <label for="indikator">Konsekuensi</label>
          <textarea class="form-control" name="deskripsi_lampiran" rows="3"></textarea>
          <small class="form-text text-muted">
            Tips: Gunakan tanda ; untuk menambah lebih dari satu lampiran.
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
@endforeach