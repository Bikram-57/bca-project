@extends('layouts/main')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('content')
    <div class="container-fluid p-lx-3 p-lg-3 p-md-3 pt-3 scroll-main">
        <main class="content">
            <div class="mb-2 p-1">
                <h5>Faculty Dashboard</h5>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4>Welcome {{ Auth::user()->name }}</h4>
                            <p class="card-text text-muted">Last login: {{ \Carbon\Carbon::parse(Auth::user()->last_seen)->format('g:i A, F j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-6 col-12 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <h2>{{ count($courses) }}</h2>
                                    <h6>Assigned Subjects</h6>
                                </div>
                                <div class="col-4">
                                    <i class="bi bi-person-video3 fs-3 text-custom"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-3 col-lg-4 col-md-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <h2>2</h2>
                                    <h6>Total Program</h6>
                                </div>
                                <div class="col-4">
                                    <i class="bi bi-chat-left-text-fill fs-3 text-custom"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-light mt-2">
                <div class="row p-3 mt-3">
                    <div class="mb-1">
                        <h5>Last Uploaded </h5>
                    </div>

                    <div class="col">
                        <div class="table-content">
                            <table class="table table-hover ">
                                <thead>
                                    <th style="width: 70px;">Sl. No</th>
                                    <th>Subject Id</th>
                                    <th>Subject Name</th>
                                    <th>Date</th>
                                </thead>
                                <tbody>
                                    @foreach ($courses as $c)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $c['cid'] }}</td>
                                            <td>{{ $c['cname'] }}</td>
                                            <td>{{ $c['updated_at'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

@endsection
