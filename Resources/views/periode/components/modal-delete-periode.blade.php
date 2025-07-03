<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deletePeriodeModal">
    <i class="nav-icon fas fa-trash "></i>
</button>
<div class="modal fade" id="deletePeriodeModal" tabindex="-1" role="dialog" aria-labelledby="hasilKerjaModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" class="modal-content" action="{{ url('skp/periode/delete/' . $periode->id) }}">
            @csrf
            <div class="modal-body">
                <p>Apakah anda yakin untuk menghapus?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                <button type="submit" class="btn btn-primary">Ya</button>
            </div>
        </form>
    </div>
</div>
