@extends('layouts.support')
@section('title', 'Support Dashboard')
@section('breadcrumb', 'Dashboard')
@section('content')
    <style>
        /* Custom CSS for additional control if needed */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            /* Smooth scrolling on touch devices */
        }
    </style>
    <div class="container-fluid scroll-main p-lx-3 p-lg-3 p-md-3 pt-3">
        <main class="content">
            <div class="mb-3">
                <h5>Support Dashboard</h5>
            </div>

            <div class="row mb-3">
                <div class="col-xl-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="text-custom">Welcome Support</h4>
                            <p class="card-text text-muted">Last login: {{ \Carbon\Carbon::parse(Auth::user()->last_seen)->format('g:i A, F j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-3 col-xxl-3 col-lg-4 col-md-4 col-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <h2>{{ count($tickets) }}</h2>
                                    <h6>Total Tickets</h6>
                                </div>
                                <div class="col-4">
                                    <i class="bi bi-chat-left-text-fill fs-3 text-custom"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-xxl-3 col-lg-4 col-md-4 col-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <h2>{{ $resolved }}</h2>
                                    <h6>Tickets Resolved</h6>
                                </div>
                                <div class="col-4">
                                    <i class="bi bi-check2-circle fs-3 text-custom"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-xxl-3 col-lg-4 col-md-4 col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <h2>{{ $pending }}</h2>
                                    <h6>Tickets Pending</h6>
                                </div>
                                <div class="col-4">
                                    <i class="bi bi-hourglass-split fs-3 text-custom"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-light mt-2" style="margin-bottom: 7.5rem">
                <div class="row p-3 mt-3">
                    <div class="mb-1">
                        <h5>Recent Tickets</h5>
                    </div>

                    <div class="col">
                        <div class="table-responsive">
                            <div class="table-content">
                                <table class="table table-hover ">
                                    <thead>
                                        <th style="width: 70px;">ID</th>
                                        <th>Name</th>
                                        <th>Subject</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($tickets as $ticket)
                                            <tr onclick="window.location='{{ route('support-ticket-view', ['id' => $ticket->id]) }}'"
                                                style="cursor:pointer;">
                                                <td>{{ $ticket->id }}</td>
                                                <td>{{ $ticket->user->name }}</td>
                                                <td>{{ $ticket->subject }}</td>
                                                <td>{{ $ticket->user->email }}</td>
                                                <td>
                                                    @if ($ticket->status === 0)
                                                        <span class="text-custom">Open</span>
                                                    @elseif($ticket->status === 1)
                                                        <span class="text-warning">In-progress</span>
                                                    @elseif($ticket->status === 2)
                                                        <span class="text-success">Resolved</span>
                                                    @endif
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('d-m-Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{-- {{ $uploaded->links() }} --}}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
