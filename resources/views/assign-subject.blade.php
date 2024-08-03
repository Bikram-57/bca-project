@extends('layouts/main')
@section('title', 'Assign Subjects')
@section('breadcrumb', 'Assign Subjects')
@section('content')
    <div class="container-fluid scroll-main p-lx-3 p-lg-3 p-md-3 pt-3">
        @include('include/error-alert')
        <div class="card" style="margin-bottom: 7.5rem">
            <div class="card-body">
                <div class="row">
                    <div class="col-xxl-6 col-xl-5 col-6">
                        <h4 class="text-custom">Assigned Subjects</h4>
                    </div>
                    <div class="col-xxl-4 col-xl-4 col-11 order-3 order-xl-0 pt-3 pt-xl-0 m-auto">
                        <div class="input-group ">
                            <input type="search" name="" id="assignedSubjectSearch" placeholder="Search Faculty"
                                class="form-control"><span class="input-group-text bg-transparent"><i
                                    class="bi bi-search"></i></span>
                        </div>
                    </div>
                    <div class="col-xxl-2 col-xl-3 col-6 order-2 order-xl-0 d-flex justify-content-md-end pe-md-4">
                        <div class="btn-color">
                            <button class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#assignSubjectModal">Assign subject</button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped my-4">
                        <thead>
                            <tr>
                                <th>Faculty Name</th>
                                <th>Subjects Assigned</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="assignedFacultyFilter">
                            @include('assigned-subjects-filter-table')
                        </tbody>
                    </table>
                    {{-- <span id="pagination">
                        {{ $facultyDropdown->links() }}
                    </span> --}}
                </div>
            </div>
        </div>
    </div>
    {{-- </div> --}}

    {{-- assign subject modal start --}}
    <div class="modal fade" id="assignSubjectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Assign Subject</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('assign-subject') }}" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            @csrf
                            <div class="col-12">
                                <label for="subject-name" class="form-label">Subject Name</label>
                                <select id="subject-name" name="subject" class="selectpicker form-control"
                                    data-live-search="true">
                                    <option value="" selected disabled>Select subject from list</option>
                                    @foreach ($allCourses as $c)
                                        <option value="{{ $c['cid'] }}">{{ $c['cid'] }} - {{ $c['cname'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mt-2">
                                <label for="faculty-name" class="form-label">Faculty</label>
                                <select name="faculty" class="selectpicker form-control" data-live-search="true">
                                    <option value="" selected disabled>Select faculty from list</option>
                                    @foreach ($faculties as $faculty)
                                        <option value="{{ $faculty['id'] }}">{{ $faculty['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mt-2">
                                <label for="year" class="form-label">Year</label>
                                <select id="year" name="year" class="form-select">
                                    <option value="" selected disabled>Select year from list</option>
                                    <option value="2024">2024</option>
                                    <option value="2023">2023</option>
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
                                    <option value="2020">2020</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- assign subject modal end --}}

    {{-- assign subject edit modal start --}}
    <div class="modal fade" id="assignEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Assigned Subjects</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('edit-assign-subject') }}" id="editForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="faculty-edit-id" class="form-label">Faculty Name</label>
                                <input type="text" id="faculty-name" class="form-control" disabled>
                            </div>
                            <div class="col-12 mt-2">
                                <label for="subject-checkboxes" class="form-label">Assigned Subjects:</label>
                                <div id="subject-checkboxes">
                                    <!-- Assigned subjects checkboxes will be inserted here dynamically -->
                                </div>
                                <span id="alert-message" class="text-danger" style="display: none;">No subejcts are
                                    checked. Please select at least one subject</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" id="removeBtn">Remove Selected</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- assign subject edit modal end --}}
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.editAssignedSubject', function() {
                var facultyId = $(this).data('faculty-id');
                $.ajax({
                    url: '/admin/get_assigned_courses',
                    method: 'GET',
                    data: {
                        faculty_id: facultyId
                    },
                    success: function(response) {
                        console.log(response);
                        $('#faculty-name').val(response[0]['faculty_name']);
                        $('#subject-checkboxes').empty(); // Clear previous checkboxes

                        if (response.length > 0) {
                            response.forEach(function(course) {
                                var checkbox =
                                    '<div class="form-check"><input class="form-check-input" type="checkbox" value="' +
                                    course.id + // Accessing the course ID
                                    '" name="checked_courses[]"><label class="form-check-label">' +
                                    course.year + ' - ' + course.course_id + ' - ' +
                                    course.course_name +
                                    '</label></div>'; // Accessing the course name
                                $('#subject-checkboxes').append(checkbox);
                            });
                        } else {
                            $('#subject-checkboxes').html(
                                '<p>No courses assigned to this faculty.</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            $('#removeBtn').on('click', function() {
                // Check if any checkboxes are checked
                var checkedCheckboxes = $('input[type="checkbox"]:checked');
                if (checkedCheckboxes.length == 0) {
                    $('#alert-message').css('display', 'block');
                } else {
                    $('#alert-message').css('display', 'none');
                }
            });
        });
    </script>

@endsection
