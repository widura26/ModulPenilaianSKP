<div class="modal fade" id="template-umpanbalik-perilaku-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Template Umpan Balik Perilaku Kerja</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <select class="custom-select template-perilaku-select" name="">
            <optgroup label="Diatas Ekspektasi">
                <option value="Baik dan sesuai">Baik dan sesuai</option>
                <option value="Baik dan tidak ada kesalahan">Baik dan tidak ada kesalahan</option>
                <option value="Perfecto">Perfecto</option>
            </optgroup>

            <optgroup label="Sesuai Ekspektasi">
                <option value="Baik, namun ada sedikit kesalahan">Baik, namun ada sedikit kesalahan</option>
            </optgroup>

            <optgroup label="Dibawah Ekspektasi">
                <option value="Ada banyak sekali yang harus diperbaiki">Ada banyak sekali yang harus diperbaiki</option>
                <option value="Masih banyak yang perlu diperbaiki">Masih banyak yang perlu diperbaiki</option>
            </optgroup>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary perilaku-kerja" data-dismiss="modal">Terapkan ke semua</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Terapkan</button>
      </div>
    </div>
  </div>
</div>
