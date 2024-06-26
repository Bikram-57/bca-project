@extends('layouts/main')
@section('title', 'Manage Subjects')
@section('breadcrumb', 'Manage Subjects')
@section('content')
    <div class="container-fluid mb-2">
        @include('include/error-alert')
    </div>

    <div class="container-fluid scroll-main p-lx-3 p-lg-3 p-md-3 pt-3">
        {{-- <h3 class="text-muted mb-4 mt-1">All Subjects</h3> --}}
        <div class="card" style="margin-bottom: 120px">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-3 col-6 ">
                        <h4 class="text-custom">All Subjects</h4>
                    </div>
                    <div class="col-xl-7 col-md-12 col-12 order-3 order-xl-0 mt-3 mt-xl-0">
                        <div class="row d-flex justify-content-xl-end">
                            <div class="col-xl-8 col-12">
                                <div class="input-group">
                                    <input type="search" name="" id="searchInput"
                                        placeholder="Search by subject code or name" class="form-control">
                                    <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-6 d-flex justify-content-end justify-content-xl-start">
                        <button class="btn btn-primary"data-bs-toggle="modal" data-bs-target="#addSubjectModal"></i>Add
                            Subject
                        </button>
                    </div>
                </div>
                <div class="mt-4 table-responsive" id="table">
                    <table class="table table-hover">
                        <thead>
                            {{-- <th>Sl.no</th> --}}
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Course</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($courses as $index => $c)
                                <tr>
                                    {{-- <td>{{ ($currentPage - 1) * $perPage + $index + 1 }}</td> --}}
                                    <td class="courseId">{{ $c['cid'] }}</td>
                                    <td>{{ $c['cname'] }}</td>
                                    <td>{{ $c['course'] }}</td>
                                    <td>
                                        <div class="more-btn">
                                            <button class="dropdown" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="bi bi-three-dots fs-4"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button class="dropdown-item editButton" type="button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editSubjectModal">Edit</button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item deleteBtn" type="button"
                                                        data-bs-toggle="modal" data-bs-target="#deleteSubjectModal"
                                                        data-course-id="{{ $c['cid'] }}">Delete</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <span id="pagination">
                        {{ $courses->links() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    {{-- Add-Subject modal start --}}
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-custom" id="exampleModalLabel">Add Subject</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="{{ route('add-subject') }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            <div class="col-12 mb-2">
                                <label class="form-label">Subject Code</label>
                                <input type="text" class="form-control capitalize" name="cid"
                                    placeholder="E.g. CA1603" required>
                                <div class="invalid-feedback">
                                    Please select year
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-2">
                                <label class="form-label">Subject Name</label>
                                <input type="text" class="form-control" name="cname"
                                    placeholder="E.g. Software Engineering" required>
                                <div class="invalid-feedback">
                                    Please select course
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-2">
                                <label class="form-label">Course Name</label>
                                <select name="course" class="form-select" id="" required>
                                    <option value="" selected disabled>Select course from list</option>
                                    <option value="MCA">MCA</option>
                                    <option value="BCA">BCA</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select course
                                </div>
                            </div>
                            <div class="modal-footer Custom_Footer my-1 d-flex justify-content-end pe-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                            <hr>
                            <div class="">
                                <p style="color: red">Note: Once a subject is created, Subject Code cannot be changed.</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Add-Subject modal end --}}

    {{-- Edit-Subject modal start --}}
    <div class="modal fade" id="editSubjectModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-custom" id="exampleModalLabel">Edit Subject Info</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('update.subject.info') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <span class="title fw-bold">
                                    Subject Code
                                </span>
                                <div class="mt-2">
                                    <input type="text" id="edit-modal-subject-code"
                                        class="form-control text-uppercase" name="subjectId" readonly>
                                </div>
                            </div>
                            <div class="col-12 pt-3">
                                <span class="title fw-bold">
                                    Subject Name
                                </span>
                                <div class="mt-1">
                                    <input type="text" id="edit-modal-subject-name" name="subject_name"
                                        class="form-control" placeholder="E.g. Python">
                                </div>
                            </div>
                            <div class="col-12 mt-2 mb-2">
                                <label class="form-label">Subject Name</label>
                                <select name="course" class="form-select" id="edit-modal-subject-course" required>
                                    <option value="" selected disabled>Select course from list</option>
                                    <option value="MCA">MCA</option>
                                    <option value="BCA">BCA</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select course
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer  my-1 d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Edit-Subject modal end --}}

    {{-- Delete-Subject modal start --}}
    <div class="modal fade" id="deleteSubjectModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row d-flex justify-content-center">
                        <div class="col-6 d-flex justify-content-center">
                            <i class="rounded-circle bi bi-exclamation-triangle-fill text-warning fs-1"></i>
                        </div>
                    </div>

                    <h4 class="text-center text-custom">Delete Subject</h4>

                    <p class="text-danger fs-6 text-center">Are you sure you want to delete this Subject? <br>This
                        action cannot be undone</p>

                    <form action="{{ route('delete.subject') }}" method="POST">
                        @csrf
                        @method('delete')
                        <input type="text" id="cid" name="cid" hidden>
                        <div class="row d-flex justify-content-center">
                            <div class="col-8 d-flex justify-content-center mb-3">
                                <button type="button" class="btn btn-secondary me-4"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-danger">Yes, Delete !</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Delete-Subject modal end --}}
@endsection
@section('scripts')
    <script>
        $(document).on('click', '.deleteBtn', function() {
            $('#cid').val($(this).data('course-id'));
            $('#deleteSubjectModal').show();
        });
    </script>
@endsection
