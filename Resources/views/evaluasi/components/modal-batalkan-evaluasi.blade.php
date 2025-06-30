<button data-toggle="modal" data-target="#batalkan-evaluasi-modal" id="batalkan-evaluasi-button" class="btn btn-primary ml-1 {{ $rencana->predikat_akhir == null ? 'd-none' : '' }}">Batalkan Evaluasi</button>

<div class="modal fade" id="batalkan-evaluasi-modal" tabindex="-1" role="dialog" aria-labelledby="hasilKerjaModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" action="{{ url('/skp/evaluasi/batalkan-evaluasi/' . $pegawai->username) }}" method="POST">
            @csrf
            <div class="modal-body">
                <p>Apakah Anda yakin ingin membatalkan evaluasi?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                <button type="submit" class="btn btn-primary">Ya</button>
            </div>
        </form>
    </div>
</div>
