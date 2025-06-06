<button type="button" title="Delete" class="btn btn-danger" data-toggle="modal" data-target="#delete-arsip-{{ $arsip->id }}">
    <i class="nav-icon fas fa-trash "></i>
</button>
<div class="modal fade" id="delete-arsip-{{ $arsip->id }}" tabindex="-1" role="dialog" aria-labelledby="delete-arsip-{{ $arsip->id }}Title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" class="modal-content" action="{{ url('/penilaian/arsip-skp/delete/' . $arsip->id) }}">
            @csrf
            <div class="modal-body">
                Apakah anda yakin untuk menghapus arsip?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Ya</button>
            </div>
        </form>
    </div>
</div>
