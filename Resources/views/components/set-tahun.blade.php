<div class="p-4">
    <form method="POST" action="{{ url('/penilaian/kinerja-organisasi/set-tahun') }}">
        @csrf
        <div class="d-flex align-content-center justify-content-end">
            <select name="tahun" id="periode-range" class="form-control mr-2" style="width: 200px;">
                <option value="">- Pilih Tahun -</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
            </select>
            <button type="submit" class="btn btn-primary">Set</button>
        </div>
    </form>
</div>
