@extends('layouts.support')
@section('title', 'Tickets')
@section('breadcrumb', 'Tickets')
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
            @include('include/error-alert')
            <div class="bg-light mt-2" style="margin-bottom: 7.5rem">
                <div class="row p-3 mt-3">
                    <div class="mb-1">
                        <h5>{{ $label }} Tickets</h5>
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
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($tickets as $ticket)
                                            <tr>
                                                <td>{{ $ticket->id }}</td>
                                                <td>{{ $ticket->user->name }}</td>
                                                <td>{{ $ticket->subject }}</td>
                                                <td>{{ $ticket->user->email }}</td>
                                                <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('d-m-Y') }}
                                                </td>
                                                <form action="{{ route('support-ticket-update-status', ['id' => $ticket->id]) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <td>
                                                        {{-- @if ($ticket->status === 0)
                                                        <span class="text-custom">Open</span>
                                                    @elseif($ticket->status === 1)
                                                        <span class="text-warning">In-progress</span>
                                                    @elseif($ticker->status === 2)
                                                        <span class="text-success">Resolved</span>
                                                    @endif --}}
                                                        <select name="status" class="form-select"
                                                            onchange="this.form.submit()">
                                                            <option value="0"
                                                                {{ $ticket->status === 0 ? 'selected' : '' }}>Open</option>
                                                            <option value="1"
                                                                {{ $ticket->status === 1 ? 'selected' : '' }}>In-progress
                                                            </option>
                                                            <option value="2"
                                                                {{ $ticket->status === 2 ? 'selected' : '' }}>Resolved
                                                            </option>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <a href="{{ route('support-ticket-view', ['id' => $ticket->id]) }}" class="btn btn-primary">View</button>
                                                    </td>
                                                </form>
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
