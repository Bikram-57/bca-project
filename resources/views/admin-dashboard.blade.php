@extends('layouts/main')
@section('title', 'Admin Dashboard')
@section('content')
    <main class="content p-lx-4 p-lg-4 p-md-4">
        <div class="mb-3 p-1">
            <h5>Admin Dashboard</h5>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4>Welcome Admin</h4>
                        <p class="card-text text-muted">Last updated: 16-02-24</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <h6>Total Faculty</h6>
                                <h2>25</h2>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-person-video3 fs-3 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <h6>Total Program</h6>
                                <h2>2</h2>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-chat-left-text-fill fs-3 text-warning"></i>
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
                                <th>View</th>
                            </thead>
                            <tbody>
                                @for($i=0; $i<count($courses); $i++)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ $courses[$i]['cid'] }}</td>
                                        <td>{{ $courses[$i]['cname'] }}</td>
                                        <td>{{ $courses[$i]['created_at'] }}</td>
                                        <td><a href="#">View</a></td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                        {{$courses->links()}}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
