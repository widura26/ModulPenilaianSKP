<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#manualIndikatorUtama-{{ $indikator->id }}">
    <i class="nav-icon fas fa-balance-scale"></i>
</button>
<div class="modal fade" id="manualIndikatorUtama-{{ $indikator->id }}" tabindex="-1" role="dialog" aria-labelledby="manualIndikatorUtamaTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" class="modal-content" action="{{ url('/skp/rencana/store-manual-indikator-utama/' . $indikator->id) }}">
            @csrf
            <div class="modal-header">Tambah Manual Indikator Utama</div>
            <input type="hidden" name="indikator_id" value="{{ $indikator->id }}">
            <div class="modal-body">
                <div class="form-group">
                    <label for="topik">Topik</label>
                    <select name="topik" class="form-control">
                        <option value="">-- Pilih Topik --</option>
                        @foreach ($dataUnik->unique('topik') as $item)
                        <option value="{{ $item->topik }}">{{ $item->topik }}</option>
                        @endforeach
                    </select>

                    
                </div>

                <div class="form-group">
                    <label for="sub_topik">Sub Topik</label>
                    <select name="sub_topik" class="form-control">
                        <option value="">-- Pilih Sub Topik --</option>
                        @foreach ($subTopikUnik as $item)
                        <option value="{{ $item->sub_topik }}">{{ $item->sub_topik }}</option>
                        @endforeach
                    </select>

                    
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi / Penjelasan</label>
                    <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
                </div>
                <input type="hidden" id="definisi-operasional-data" value='@json($definisiOperasional)'>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
        </form>
    </div>
</div>

<script>
    const definisiList = JSON.parse(document.getElementById('definisi-operasional-data').value);
    window.definisiList = definisiList; // biar tetap bisa dipakai global
</script>