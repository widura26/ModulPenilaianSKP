<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#manualIndikatorUtama-{{ $hasilKerja->id }}">
    <i class="nav-icon fas fa-balance-scale"></i>
</button>
<div class="modal fade" id="manualIndikatorUtama-{{ $hasilKerja->id }}" tabindex="-1" role="dialog" aria-labelledby="manualIndikatorUtamaTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" class="modal-content" action="{{ url('/skp/rencana/store-hasil-kerja-utama/' . (is_null($rencana) ? '' : $rencana->id)) }}">
            @csrf
            <div class="modal-header">Tambah Manual Indikator Utama</div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="topik">Topik</label>
                    <select name="topik" id="topik-{{ $hasilKerja->id }}" class="form-control topik-dropdown">
                        <option value="">-- Pilih Topik --</option>
                        @foreach ($definisiOperasional->groupBy('topik') as $topik => $list)
                        <option value="{{ $topik }}">{{ $list[0]['topik'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="sub_topik">Sub Topik</label>
                    <select name="sub_topik" id="sub_topik-{{ $hasilKerja->id }}" class="form-control subtopik-dropdown">
                        <option value="">-- Pilih Sub Topik --</option>
                        @foreach ($definisiOperasional->groupBy('topik') as $topik => $list)
                        <option value="{{ $topik }}">{{ $list[0]['sub_topik'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi / Penjelasan</label>
                    <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
                </div>
                <input type="hidden" id="definisi-operasional-data" value='@json($definisiOperasional)'>
                <!-- <input type="hidden" name="hasil_kerja_id" value="{{ $hasilKerja->id }}"> -->
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