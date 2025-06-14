@extends('adminlte::page')

@section('title', 'SKP Poliwangi')

@section('content_header')
<h1 class="m-0 text-dark">Verifikasi SKP</h1>
@stop
@php
@endphp

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <!-- Example single danger button -->
        <!-- <form> -->
        <div class="form-row d-flex justify-content-end">
          <div class="form-group ">
            <select id="inputState" class="form-control">
              <option selected>Tahun</option>
              <option>2023</option>
              <option>2024</option>
              <option>2025</option>
              <option>2026</option>
            </select>
          </div>
          <div class="form-group">
            <select id="inputState" class="form-control">
              <option selected>Periode</option>
              <option>01 Januari 2023 - 31 Desember 2023</option>
              <option>01 Januari 2024 - 31 Desember 2024</option>
              <option>01 Januari 2025 - 31 Desember 2025</option>
              <option>01 Januari 2026 - 31 Desember 2026</option>
            </select>
          </div>
          <div class="form-group col-md-2">
            <input type="text" class="form-control" id="inputAddress" placeholder="Unit Kerja">
          </div>
          <div class="form-group col-md-2">
            <input type="text" class="form-control" id="inputAddress" placeholder="Jabatan">
          </div>
          <div>
            <a href="#" class="btn btn-primary">SET</a>
          </div>
        </div>


      </div>

    </div>
    <div class="card">
      <div class="card-body">
        <table id="example" class="table table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>File Rencana SKP</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>5</td>
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
            </tr>
            <tr>
              <td>4</td>
              <td>Garrett Winters</td>
              <td>Accountant</td>
              <td>
                <div class="d-grid gap-2 d-md-block">
                  <button class="btn btn-secondary" type="button" disabled>Belum Verifikasi</button>
                </div>
              </td>
              <td>
                <div class="d-grid gap-2 d-md-block">
                  <button class="btn btn-primary" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                      <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg></button>
                  <button class="btn btn-success" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                      <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                    </svg></button>
                  <button class="btn btn-danger " type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ban" viewBox="0 0 16 16">
                      <path d="M15 8a6.97 6.97 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8M2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0" />
                    </svg></button>
                </div>
              </td>
            </tr>
            <tr>
              <td>3</td>

              <td>Ashton Cox</td>
              <td>Junior Technical Author</td>
              <td>
                <div class="d-grid gap-2 d-md-block">
                  <button class="btn btn-secondary" type="button" disabled>Belum Verifikasi</button>
                </div>
              </td>
              <td>
                <div class="d-grid gap-2 d-md-block">
                  <button class="btn btn-primary" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                      <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg></button>
                  <button class="btn btn-success" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                      <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                    </svg></button>
                  <button class="btn btn-danger" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ban" viewBox="0 0 16 16">
                      <path d="M15 8a6.97 6.97 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8M2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0" />
                    </svg></button>
                </div>
              </td>
            </tr>
            <tr>
              <td>2</td>

              <td>Cedric Kelly</td>
              <td>Senior Javascript Developer</td>
              <td>
                <div class="d-grid gap-2 d-md-block">
                  <button class="btn btn-secondary" type="button" disabled>Belum Verifikasi</button>
                </div>
              </td>
              <td>
                <div class="d-grid gap-2 d-md-block">
                  <button class="btn btn-primary" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                      <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg></button>
                  <button class="btn btn-success" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                      <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                    </svg></button>
                  <button class="btn btn-danger" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ban" viewBox="0 0 16 16">
                      <path d="M15 8a6.97 6.97 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8M2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0" />
                    </svg></button>
                </div>
              </td>
            </tr>
            <tr>
              <td>1</td>

              <td>Airi Satou</td>
              <td>Accountant</td>
              <td>
                <div class="d-grid gap-2 d-md-block">
                  <button class="btn btn-secondary" type="button" disabled>Belum Verifikasi</button>
                </div>
              </td>
              <td>
                <div class="d-grid gap-2 d-md-block">
                  <button class="btn btn-primary" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                      <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg></button>
                  <button class="btn btn-success" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                      <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
                    </svg></button>
                  <button class="btn btn-danger" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ban" viewBox="0 0 16 16">
                      <path d="M15 8a6.97 6.97 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8M2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0" />
                    </svg></button>
                </div>
              </td>
            </tr>

          </tbody>

        </table>
      </div>
    </div>
  </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="/assets/css/admin_custom.css">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> -->
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
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
</script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
@endpush