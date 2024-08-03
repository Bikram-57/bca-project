{{-- @extends('layouts.main')
@section('title', 'Profile Edit')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="mb-2">
            <h2 class="mt-3 text-custom">Support</h2>
        </div>
        @include('include/error-alert')
        <div class="row">
            <div class="col-12 col-xl-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-custom">Profile Information</h4>

                        <form method="post" action="{{ route('profile.update') }}">
                            @csrf
                            @method('patch')
                            @if (Auth::user()->is_faculty)
                                <div class="form-group mt-2">
                                    <label class="form-label">Faculty Id.</label>
                                    <input type="text" class="form-control" name="text"
                                        value="{{ Auth::user()->regno }}" readonly>
                                </div>
                            @endif
                            <div class="form-group mt-2">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                            </div>
                            <div class="form-group mt-2">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                            </div>
                            <div class="form-group mt-3">
                                <input type="submit" class="btn btn-primary w-25" value="Save">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}



@extends('layouts.main')
@section('title', 'Support')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="mb-2">
            <h4 class="mt-3 text-custom">Support</h4>
        </div>
        @include('include/error-alert')

        <div class="row">
            <!-- Trigger Button for Modal -->
            <div class="col-12 mb-3 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ticketModal">
                    Raise a Support Ticket
                </button>
            </div>

            <!-- Existing Tickets Table -->
            <div class="col-12 col-xl-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-custom">Your Support Tickets</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Ticket ID</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($tickets) > 0)
                                    @foreach ($tickets as $ticket)
                                        <tr>
                                            <td>#CA-TKT-00{{ $ticket->id }}</td>
                                            <td>{{ $ticket->subject }}</td>
                                            <td>
                                                @if ($ticket->status === 0)
                                                    <span class="text-custom">Open</span>
                                                @elseif($ticket->status === 1)
                                                    <span class="text-warning">In-progress</span>
                                                @elseif($ticket->status === 2)
                                                    <span class="text-success">Resolved</span>
                                                @endif
                                            </td>
                                            <td>{{ $ticket->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">
                                            <p class="text-center">No tickets found</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Modal -->
    <div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="ticketModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ticketModalLabel">Raise a Support Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('support.ticket.create') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mt-2">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="subject" required>
                        </div>
                        <div class="form-group mt-2">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" rows="5" required></textarea>
                        </div>
                        <div class="form-group mt-2">
                            <label class="form-label">Attachment - <span class="text-danger">(3 MB Max)</span></label>
                            <input type="file" class="form-control" name="attachment">
                        </div>
                        <div class="form-group mt-3">
                            <input type="submit" class="btn btn-primary w-25" value="Submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
