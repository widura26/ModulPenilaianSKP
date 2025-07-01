
<!-- Tombol trigger -->
<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambahHasilKerja">
  <i class="fas fa-plus"></i>
</button>

<!-- Modal -->
<div class="modal fade" id="modalTambahHasilKerja" role="dialog" tabindex="-1" aria-labelledby="modalTambahHasilKerjaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form class="modal-content" action="{{ route('hasil-kerja.store', $rencana->id ?? '') }}" method="POST"> <!-- Ubah sesuai route -->
      @csrf
      <div class="modal-header">Tambah Hasil Kerja Utama</div>
      <div class="modal-body">
        <div class="form-group">
          <label for="peran-select">Peran</label>
          <select class="form-control" id="peran-select" name="peran">
            <option value="">-- Pilih Peran --</option>
            @foreach ($pegawai->timKerjaAnggota as $item)
            <option value="{{ $item->pivot->peran == 'Ketua' ? ($item->parentUnit->id ?? $item->id) : $item->id  }}">
              {{ $item->pivot->peran }} {{ $item->unit->nama }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="hasil-kerja-diintervensi">Hasil Kerja yang diintervensi</label>
          <select class="form-control" id="hasil-kerja-diintervensi" name="parent_hasil_kerja_id">
            <option value="">-- Pilih hasil kerja yang diintervensi --</option>
            @if (!is_null($parentHasilKerja))
            @foreach ($parentHasilKerja as $index => $parent)
            <option data-peran-id="{{ optional($parent->rencanakerja->pegawai->timKerjaAnggota->first())->id }}" value="{{ $parent->id }}">{{ $parent->deskripsi }}</option>
            @endforeach
            @endif
          </select>
        </div>

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