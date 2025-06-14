@extends('adminlte::page')

@section('title', 'SKP Poliwangi')

@section('content_header')
<h1 class="m-0 text-dark">Unggah SKP</h1>
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
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-2">
          <button class="btn btn-primary me-md-2" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Unggah SKP</button>
        </div>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th scope="col">No</th>
              <th scope="col">File Rencana SKP</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <!--  -->
        </table>

        <!-- Modal Unggah SKP -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
          aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Unggah SKP</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                  <div class="mb-3">
                    <label for="skp_file" class="form-label">Pilih File SKP</label>
                    <input class="form-control" type="file" id="skp_file" name="skp_file" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-primary">Unggah</button>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="/assets/css/admin_custom.css">
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
</script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script> -->
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
<!-- <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script> -->
@endpush