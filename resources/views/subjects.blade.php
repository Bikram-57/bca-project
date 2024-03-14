@extends('layouts/main')
@section('title', 'Manage-Subjects')
@section('breadcrumb', 'Courses / List of All Subjects')
@section('content')
    <style>
        .modal-footer {
            border-top: none;
        }

        .modal-header {
            border-bottom: none;
        }
    </style>
    <div class="container mb-4">
        <div class="row d-flex justify-content-center">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <form action="">
                            <div class="row">
                                <div class="col-8">
                                    <input type="search" name="" id="" placeholder="E.g. CA1603"
                                        class="form-control">
                                </div>
                                <div class="col-4 d-flex justify-content-center">
                                    <button class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <h4 class="text-muted">All Subjects</h4>
                    </div>
                    <div class="col-6  d-flex justify-content-end pe-5">
                        <button class="btn btn-primary"data-bs-toggle="modal" data-bs-target="#addSubjectModal">Add Subject
                            +
                        </button>
                    </div>
                </div>
                <div class="mt-2 table-responsive" id="table">
                    <table class="table table-hover">
                        <thead>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < count($courses); $i++)
                                <tr>
                                    <td>{{ $courses[$i]['cid'] }}</td>
                                    <td>{{ $courses[$i]['cname'] }}</td>
                                    <td>
                                        <div class="more-btn">
                                            <button class="dropdown" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="bi bi-three-dots fs-4"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button class="dropdown-item" type="button" class="btn btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editSubjectModal">Edit</button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item" type="button" class="btn btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteSubjectModal">Delete</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    {{ $courses->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Add-Subject modal start --}}

    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Subject</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row px-3">
                        <form action="{{ route('add-subject') }}" method="POST" class="needs-validation" novalidate>
                            <div class="col-xl-12 col-lg-5 col-md-5 col-6 mt-1 mb-1">
                                <label class="form-label">Subject Code</label>
                                <input type="text" class="form-control" name="cid" placeholder="E.g. CA1603"
                                    required>
                                <div class="invalid-feedback">
                                    Please select year
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-5 col-md-5 col-6 mt-1 mb-1">
                                <label class="form-label">Subject Name</label>
                                <input type="text" class="form-control" name="cname"
                                    placeholder="E.g. Software Engineering" required>
                                <div class="invalid-feedback">
                                    Please select course
                                </div>
                            </div>
                            <div class="modal-footer  my-1 d-flex justify-content-end pe-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Add-Subject modal end --}}

    {{-- Edit-Subject modal start --}}

    <div class="modal fade" id="editSubjectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Subject Name</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="">
                            <div class="col">
                                <span class="title fw-bold">
                                    Subject Code
                                </span>
                                <div class="mt-2">
                                    <input type="text" class="form-control" class=" text-uppercase"
                                        placeholder="E.g. CA1603">
                                </div>
                            </div>
                            <div class="col pt-3">
                                <span class="title fw-bold">
                                    Subject Name
                                </span>
                                <div class="mt-1">
                                    <input type="text" class="form-control" placeholder="E.g. Python">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer  my-1 d-flex justify-content-end pe-5">
                    <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Edit-Subject modal end --}}

    {{-- Delete-Subject modal start --}}

    <div class="modal fade" id="deleteSubjectModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Subject</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row d-flex justify-content-center">
                        <div class="col-6 d-flex justify-content-center">
                            <i class="rounded-circle bi bi-exclamation-triangle-fill text-warning fs-1"></i>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-center">
                        <div class="col-8 d-flex justify-content-center">
                            <h3>Delete Subject</h3>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-center">
                        <div class="col-10 mb-3">
                            <p class="text-danger fs-6 text-center">Are you sure you want to delete this Subject? This
                                action can not be undo.</p>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-center">
                        <div class="col-8 d-flex justify-content-center mb-3">
                            <button type="button" class="btn btn-secondary me-4" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-danger">Yes, Delete !</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Delete-Subject modal end --}}
@endsection
