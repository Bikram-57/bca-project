@extends('layouts/main')
@section('title', 'Fetch Data')
@section('breadcrumb', 'Fetch Data')
@section('content')
    <div class="container-fluid">
        @include('include/error-alert')
        <div class="card mt-3 mt-xl-0 mt-md-0">
            <div class="card-body">
                <h4 class="text-custom">Fetch Data</h4>
                <form action="{{ route('fetch-data') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6 col-12 my-3">
                            <select name="batch" id="batch" class="form-select" required>
                                <option selected disabled value="">Select batch</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select Batch
                            </div>
                        </div>

                        <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6 col-12 my-3">
                            <select name="course" id="course" class="form-select" required>
                                <option selected disabled value="">Select course</option>
                                <option value="BCA">BCA</option>
                                <option value="MCA">MCA</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select Course
                            </div>
                        </div>

                        <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6 col-12 my-3">
                            <select name="year" id="year" class="form-select" required>
                                <option selected disabled value="">Select year</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select Year
                            </div>
                        </div>

                        <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6 col-12 my-3">
                            <select name="semester" id="semester" class="form-select" required>
                                <option selected disabled value="" disabled>Select semester</option>
                                <option value="odd">Odd</option>
                                <option value="even">Even</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select Semester
                            </div>
                        </div>

                        <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-8 col-12 my-3">
                            <select name="subjectId" class="selectpicker form-control" id="subjectId" data-live-search="true" required>
                                <option value="" selected disabled>Select subject code</option>
                                @foreach ($courses as $c)
                                    <option value="{{ $c['cid'] }}">{{ $c['cid'] }} - {{ $c['cname'] }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select subject
                            </div>
                        </div>

                        <div class=" col-xl-2 col-lg-3 col-md-3 col-12 my-3">
                            <button type="submit" class="btn btn-primary w-100" id="myButton">Fetch</button>
                        </div>

                        {{-- <div class="col-xxl-7 col-xl-7 col-lg-7  col-md-10 col-12 my-3">
                            <div class="row">
                                <div class=" col-xl-9 col-lg-9 col-md-9 col-12">
                                    <input type="file" name="file" id="file" class="form-control"
                                        aria-label="file example" required>
                                    <span class="text-danger" id="file-error"></span>
                                    <div class="invalid-feedback">
                                        Please Choose a file
                                    </div>
                                </div>
                                <div
                                    class=" col-xxl-3 col-xl-3 col-lg-3 col-md-3 col-12 mt-xl-0 mt-lg-0 mt-md-0 mt-3 m-auto ">
                                    <button type="submit" class="btn btn-primary w-100" id="myButton">Upload</button>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    {{-- <div class="row">
                        <h4 class="text-custom">Fetch Data</h4>
                        <div class="col-xl-3 col-lg-3 col-sm-12 col-12 my-3">
                            <select name="batch" id="batch" class="form-select" required>
                                <option selected disabled value="">Select batch</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select Course
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-12 my-3">
                            <select name="course" id="course" class="form-select" required>
                                <option selected disabled value="">Select course</option>
                                <option value="bca">BCA</option>
                                <option value="mca">MCA</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select Course
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-12 my-3">
                            <select name="year" id="years" class="form-select" required>
                                <option selected disabled value="">Select year</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select Year
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-12 my-3">
                            <select name="semester" id="semester" class="form-select" required>
                                <option selected disabled value="" disabled>Select semester</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select Semester
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-3 col-12 my-3">
                            <select name="subject" id="subjectId" class="form-select" required>
                                <option selected disabled value="">Select subject</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select subject
                            </div>
                        </div>

                        <div class=" col-xl-2 col-lg-3 col-12 my-3">
                            <button type="submit" class="btn btn-primary w-100" id="myButton">Fetch</button>
                        </div>
                    </div> --}}
                </form>
            </div>
        </div>
    </div>
@endsection
