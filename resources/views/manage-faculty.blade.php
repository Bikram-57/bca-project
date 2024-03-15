@extends('layouts/main')
@section('title', 'CO-PO Relation')
@section('breadcrumb', 'Faculty / Manage Faculty')
@section('content')
    <div class="container">
        <div class="card mt-3 mt-xl-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-6 ps-3">
                        <h3>List of Faculty</h3>
                    </div>
                    <div class="col-6 d-flex justify-content-end pe-5">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add Faculty +
                        </button>
                    </div>
                </div>

                <table class="table table-hover my-4">
                    <thead>
                        <tr>
                            <th>Sl.No.</th>
                            <th>Faculty ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($faculty as $f)
                            <tr>
                                <td>1</td>
                                <td>001</td>
                                <td>{{ $f['name'] }}</td>
                                <td>{{ $f['email'] }}</td>
                                <td>
                                    <div class="more-btn">
                                        <button class="dropdown" type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="bi bi-three-dots fs-4"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button class="dropdown-item" type="button" class="btn btn-primary"
                                                    data-bs-toggle="modal" data-bs-target="#modalView">View</button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" type="button" class="btn btn-primary"
                                                    data-bs-toggle="modal" data-bs-target="#modalEdit">Edit</button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" type="button" class="btn btn-primary"
                                                    data-bs-toggle="modal" data-bs-target="#modalDelete">Delete</button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- faculty-view modal start --}}
    <div class="modal fade" id="modalView" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Faculty Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <label for="" class="form-label"></label>
                                Name
                            <div>Bikram Das</div>
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label"></label>
                                Reg.No
                            <div>202116057</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- faculty-view modal end --}}

    {{-- faculty-Edit modal start --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Faculty Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="">
                            <div class="col">
                                <label for="" class="form-label"></label>
                                    Name
                                <div class="mt-1">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col">
                                <label for="" class="form-label"></label>
                                    Reg.No
                                <div class="mt-1">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
    {{-- faculty-Edit modal end --}}

    {{-- faculty-Delete modal start --}}
    <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row d-flex justify-content-center">
                        <div class="col-6 d-flex justify-content-center">
                            <i class="rounded-circle bi bi-exclamation-triangle-fill text-warning fs-1"></i>
                        </div>
                    </div>

                    <h4 class="text-center text-muted">Delete Faculty</h4>
                    <p class="text-danger text-center">Are you sure you want to delete this faculty? <br>This action cannot
                        be undone !
                    </p>

                    <div class="row d-flex justify-content-center">
                        <div class="col-8   d-flex justify-content-center">
                            <button class="btn btn-secondary me-5" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-danger">Yes, Delete !</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- faculty-Delete modal end --}}

    {{-- Add-faculty modal start --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Faculty</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="{{ route('add-faculty') }}" method="POST">
                            @csrf
                            <div class="col mb-2">
                                <label for="" class="form-label">Faculty ID</label>
                                <input type="email" name="" id="" class="form-control">
                            </div>
                            <div class="col mb-2">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col mb-2">
                                <label for="" class="form-label">Email</label>
                                <input type="email" name="" id="" class="form-control">
                            </div>
                            <div class="col mb-3">
                                <label for="" class="form-label">Password</label>
                                <input type="text" name="passsword" value="cadept@1234" class="form-control" disabled>
                            </div>
                            <p class="text-danger">Note: The above given is the default password for the newly created faculty which can be changed by the faculty from their profile section.</p>
                            <button type="button" class="btn btn-primary w-100">Add</button>
                            {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Add-faculty modal end --}}

@endsection
