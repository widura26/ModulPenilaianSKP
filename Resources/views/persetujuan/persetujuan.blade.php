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

        <table id="example" class="table table-striped">
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
            <!-- <tr>
              <td>2</td>
              <td>Tiger Nixon</td>
              <td>System Architect</td>
              <td>
                <div class="d-grid gap-2 d-md-block">
                  <button class="btn btn-success" type="button" disabled><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                      <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />
                      <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                    </svg> Sudah Verifikasi</button>
                </div>
              </td>
              <td>
                <div class="d-grid gap-2 d-md-block">
                  <button class="btn btn-primary" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                      <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg></button>
                  <button class="btn btn-secondary" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                      <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                    </svg></button>
                  <button class="btn btn-danger" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ban" viewBox="0 0 16 16">
                      <path d="M15 8a6.97 6.97 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8M2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0" />
                    </svg></button>
                </div>
              </td>
            </tr> -->
            <!-- <tr>
              
              <td>1</td>

              <td>Nur Lailatul Hidayah</td>
              <td>Accountant</td>
              <td>
                <div class="d-grid gap-2 d-md-block">
                  <button class="btn btn-danger" type="button" disabled>Belum Verifikasi</button>
                </div>
              </td>
              <td>
                <div class="d-grid gap-2 d-md-block">
                  <button class="btn btn-primary" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                      <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg>Detail</button>
                  <button class="btn btn-success" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                      <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                    </svg>Setuju</button>
                  <button class="btn btn-danger" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ban" viewBox="0 0 16 16">
                      <path d="M15 8a6.97 6.97 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8M2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0" />
                    </svg>Tolak</button>
                </div>
              </td>
            </tr> -->
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
                <!-- Tombol Setujui & Tolak disembunyikan -->
                <form action="{{ url('/skp/persetujuan/setujui/' . $rk->pegawai_id) }}" method="POST" style="display:inline">
                  @csrf
                  <button class="btn btn-sm btn-success"><i class="far fa-check-circle"></i></button>
                </form>

                <form action="{{ url('/skp/persetujuan/tolak/' . $rk->pegawai_id) }}" method="POST" style="display:inline">
                  @csrf
                  <button class="btn btn-sm btn-danger"><i class="fas fa-ban"></i></button>
                </form>
                @else
                <!-- Tombol aktif jika belum ada aksi -->
                <!-- <button class="btn btn-sm btn-success" ><i class="far fa-check-circle"></i></button>
                <button class="btn btn-sm btn-danger" ><i class="fas fa-ban"></i></button> -->
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
<link rel="stylesheet" href="/assets/css/admin_custom.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css">
@stop

@push('js')
<script>
  $(document).ready(function() {
    $('#example').DataTable({
      responsive: true,
      autoWidth: false
    });
  });

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

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
@endpush