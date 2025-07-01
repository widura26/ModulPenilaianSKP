@extends('adminlte::page')

@section('title', 'Definisi Operasional')

@section('content_header')

@stop
@section('content')
<div class="row">
    <!-- @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif -->

    <div class="col-12 mt-5">
        <div class="card p-4">
            <h1 class="m-0 text-dark">Definisi Operasional</h1>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <h4 class="m-0">Manage Manual Indikator Utama</h4>
                @include('penilaian::definisi-operasional.component.modal-create-definisi-operasional')
            </div>
            <table class="table table-bordered mt-2 rounded-lg">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Topik</th>
                        <th scope="col">Sub Topik</th>
                        <th scope="col">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataDefinisi as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->topik }}</td>
                        <td>{{ $item->sub_topik }}</td>
                        <td class="text-center">
                            <!-- Tombol Edit -->
                            @include('penilaian::definisi-operasional.component.modal-edit-definisi-operasional')
                            <!-- <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal-{{ $item->id }}">
                                <i class="fas fa-edit"></i>
                            </button> -->

                            <!-- Tombol Hapus -->
                            <form action="{{ url('/skp/definisi-operasional/delete/'. $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        
    </div>
</div>

@stop

@section('css')

@stop

@push('js')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
    // SweetAlert konfirmasi hapus
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const form = this.closest('form');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

@endpush