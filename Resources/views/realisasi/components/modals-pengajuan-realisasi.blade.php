<button id="proses-umpan-balik-button" type="button" class="btn btn-primary" {{ (!$adaRealisasiUtama && !$adaRealisasiTambahan && !$realisasiPeriodik) ? 'disabled' : '' }} data-toggle="modal" data-target="#pengajuan-realisasi">
    Ajukan Realisasi
</button>

<div class="modal fade" id="pengajuan-realisasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" method="POST" action="{{ url('/skp/realisasi/'. $periode->id . '/ajukan-realisasi/' . $rencana->id) }}">
            @csrf
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Ajukan realisasi sekarang?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                <button type="submit" class="btn btn-primary">Ya</button>
            </div>
        </form>
    </div>
</div>
