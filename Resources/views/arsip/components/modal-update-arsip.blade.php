<button title="edit" type="button" class="btn btn-primary" data-toggle="modal" data-target="#update-arsip-{{ $arsip->id }}">
    <i class="nav-icon fas fa-pencil-alt "></i>
</button>
<div class="modal fade" id="update-arsip-{{ $arsip->id }}" tabindex="-1" role="dialog" aria-labelledby="update-arsip-{{ $arsip->id }}Title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" class="modal-content" action="{{ url('/skp/arsip-skp/update/' . $arsip->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">Update Arsip</div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="peran-select">Jenis Arsip</label>
                    <select class="form-control" name="jenis_arsip">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Rencana" {{ $arsip->jenis_arsip == 'Rencana' ? 'selected' : '' }}>Rencana</option>
                        <option value="Evaluasi" {{ $arsip->jenis_arsip == 'Evaluasi' ? 'selected' : '' }}>Evaluasi</option>
                        <option value="Dokumen Evaluasi" {{ $arsip->jenis_arsip == 'Dokumen Evaluasi' ? 'selected' : '' }}>Dokumen Evaluasi</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>File</label>
                    <input type="file" class="form-control" name="file_arsip">
                    <input type="hidden" name="old_file" value="{{ $arsip->file }}">
                    @if ($arsip->file)
                        <small class="form-text text-muted">
                            File saat ini: <a href="{{ asset('storage/arsip/' . $arsip->file) }}" target="_blank">{{ $arsip->file }}</a>
                        </small>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
