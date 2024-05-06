@extends('layouts/main')
@section('title', 'Direct Attainment')
@section('breadcrumb', 'Direct Attainment')
@section('loader')

    <!-- Progress bar container -->
    <div id="progressContainer" class="progress-container" style="display: none;">
        <div class="youtube-progress">
            <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                aria-valuemax="100"></div>
        </div>
    </div>

    {{-- <div class="progress" role="progressbar" aria-label="Example 1px high" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 3.5px">
    <div class="progress-bar" style="width: 25%"></div>
  </div> --}}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h4 class="text-custom">Direct Attainment</h4>
                <form action="{{ route('get-direct-attainment') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-12 my-3">
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
                        <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-12 my-3">
                            <select name="course" id="course" class="form-select" required>
                                <option selected disabled value="">Select course</option>
                                <option value="BCA">BCA</option>
                                <option value="MCA">MCA</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select Course
                            </div>
                        </div>
                        <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6 col-12 my-3">
                            <a href={{ route('direct-attainment') }}>
                                <button type="submit" class="btn btn-primary" id="myButton">Submit
                                </button>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
