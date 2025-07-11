@extends('adminlte::page')

@section('title', 'SKP Poliwangi')

@section('content_header')
<h1 class="m-0 text-dark">Persetujuan SKP</h1>
@stop
@php
@endphp

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      @include('penilaian::components.set-periode')
    </div>
  </div>
</div>
<div class="row">
  <div class="col-12">

    <div class="card">
      <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif


        <div class="d-flex justify-content-end mb-2">
          {{-- Filter Dropdown --}}
          <form method="GET" action="{{ url('skp/persetujuan/') }}" class="d-flex" style="margin-right: 10px;">
            <select name="filter_status" class="form-control" style="width: 200px;" onchange="this.form.submit()">
              <option value="">-- Filter Status --</option>
              <option value="Belum Buat SKP" {{ request('filter_status') == 'Belum Buat SKP' ? 'selected' : '' }}>Belum Buat SKP</option>
              <option value="Belum Diajukan" {{ request('filter_status') == 'Belum Diajukan' ? 'selected' : '' }}>Belum Diajukan</option>
              <option value="Sudah Disetujui" {{ request('filter_status') == 'Sudah Disetujui' ? 'selected' : '' }}>Sudah Disetujui</option>
              <option value="Belum Disetujui" {{ request('filter_status') == 'Belum Disetujui' ? 'selected' : '' }}>Belum Disetujui</option>
            </select>
          </form>

          {{-- Tombol Aksi --}}
          <form method="POST" id="form-terpilih" action="{{ url('/skp/persetujuan/setujui-terpilih') }}" class="d-flex">
            @csrf
            <input type="hidden" name="rencana_id[]" id="selectedIdsInput">
            <button type="button" onclick="submitFormTerpilih('setujui')" class="btn btn-primary me-2" style="margin-right: 10px;">Setujui Terpilih</button>
            <button type="button" onclick="submitFormTerpilih('tolak')" class="btn btn-danger">Tolak Terpilih</button>
          </form>
        </div>

        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>
                <div class="input-group-prepend">
                  <div class="input-group-text">
                    <input type="checkbox" id="checkAll">
                  </div>
                </div>
              </th>
              <th>No</th>
              <th>Nama</th>
              <th>Jabatan</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($rencana as $index => $rk)
            <tr>
              <td>
                <div class="input-group-prepend">
                  <div class="input-group-text">
                    <input type="checkbox" name="rencana_id[]" value="{{ $rk->id }}" class="checkItem">
                  </div>
                </div>
              </td>
              <td>{{ $index + 1 }}</td>
              <td>{{ $rk->pegawai->nama }}{{ $rk->pegawai->nip }}</td>
              <td>{{ $rk->pegawai->jabatan->jabatan ?? '-' }}</td>
              <td>{{ $rk->status_persetujuan }}</td>
              <td>
                <a href="{{ url('/skp/persetujuan/detail/'. $rk->pegawai_id) }}" class="btn btn-sm btn-info"><i class="fas fa-search"></i></a>
                @if ($rk->status_persetujuan == 'Sudah Disetujui' || $rk->status_persetujuan == 'Belum Disetujui')
                <form action="{{ url('/skp/persetujuan/setujui/' . $rk->pegawai_id) }}" method="POST" style="display:inline">
                  @csrf
                  <button class="btn btn-sm btn-success"><i class="far fa-check-circle"></i></button>
                </form>
                <form action="{{ url('/skp/persetujuan/tolak/' . $rk->pegawai_id) }}" method="POST" style="display:inline">
                  @csrf
                  <button class="btn btn-sm btn-danger"><i class="fas fa-ban"></i></button>
                </form>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

        
      </div>
    </div>
  </div>
</div>
@stop

@section('css')

@stop

@push('js')
<script>
  // const tdStatus = document.querySelector('#td-status')
  // console.log(tdStatus.innerText)

  // Select All Checkbox Logic
  $('#checkAll').click(function() {
    $('.checkItem').prop('checked', this.checked);
  });

  function submitFormTerpilih(action) {
    const form = document.getElementById('form-terpilih');
    const checkboxes = document.querySelectorAll('.checkItem:checked');
    const selectedIdsInput = document.getElementById('selectedIdsInput');

    // Clear input sebelumnya
    selectedIdsInput.value = '';

    // Ambil semua ID terpilih
    const selectedIds = Array.from(checkboxes).map(cb => cb.value);
    if (selectedIds.length === 0) {
      alert("Pilih minimal satu SKP terlebih dahulu.");
      return;
    }

    // Set input value
    selectedIdsInput.name = "rencana_id[]"; // pastikan array
    selectedIdsInput.value = selectedIds;

    // Set action form
    if (action === 'setujui') {
      form.action = "{{ url('/skp/persetujuan/setujui-terpilih') }}";
    } else if (action === 'tolak') {
      form.action = "{{ url('/skp/persetujuan/tolak-terpilih') }}";
    }

    form.submit();
  }
</script>


@endpush