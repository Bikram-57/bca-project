@extends('layouts.support')
@section('title', 'Ticket Details')
@section('breadcrumb', 'Tickets Details')
@section('content')
    <style>
        /* Custom CSS for additional control if needed */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            /* Smooth scrolling on touch devices */
        }
    </style>
    {{-- <div class="container-fluid scroll-main p-lx-3 p-lg-3 p-md-3 pt-3">
        <main class="content">
            <div class="bg-light mt-2" style="margin-bottom: 7.5rem">
                <div class="row p-3 mt-3">
                    <div class="mb-1">
                        <h5>Tickets Details</h5>
                    </div>

                    <p>{{ $ticket->id }}</p>
                    <p>{{ $ticket->user->name }}</p>
                    <p>{{ $ticket->user->email }}</p>
                    <p>{{ $ticket->subject }}</p>
                    <p>{{ $ticket->status }}</p>
                    <p>{{ $ticket->description }}</p>
                    <p>
                        @if (!is_null($ticket->attachment))
                            <img src="{{ asset('assets/support_files') . '/' . $ticket->attachment }}" alt="attachment">
                        @endif
                    </p>

                </div>
            </div>
        </main>
    </div> --}}

    <div class="container-fluid scroll-main p-lx-3 p-lg-3 p-md-3 pt-3">
        <main class="content">
            <div class="bg-light mt-2 p-4 rounded" style="margin-bottom: 7.5rem">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="row d-flex justify-content-between">
                            <div class="col">
                                <h5>Ticket Details</h5>
                            </div>
                            <div class="col-3 mb-2">
                                <strong>Status:</strong>
                                @if ($ticket->status === 0)
                                    <span class="text-custom">Open</span>
                                @elseif($ticket->status === 1)
                                    <span class="text-warning">In-progress</span>
                                @elseif($ticket->status === 2)
                                    <span class="text-success">Resolved</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-3 mb-2">
                        <strong>Ticket ID:</strong>
                        <p>{{ $ticket->id }}</p>
                    </div>
                    <div class="col-3 mb-2">
                        <strong>User Name:</strong>
                        <p>{{ $ticket->user->name }}</p>
                    </div>
                    <div class="col-3 mb-2">
                        <strong>Email:</strong>
                        <p>{{ $ticket->user->email }}</p>
                    </div>
                    <div class="col-3 mb-2">
                        <strong>Subject:</strong>
                        <p>{{ $ticket->subject }}</p>
                    </div>
                    <div class="col-6 mb-2">
                        <strong>Description:</strong>
                        <p>{{ $ticket->description }}</p>
                    </div>
                    <div class="col-6 mb-2">
                        <strong>Attachment:</strong>
                        <p>
                            @if (!is_null($ticket->attachment))
                                <img src="{{ asset('assets/support_files/' . $ticket->attachment) }}" alt="attachment"
                                    class="img-thumbnail" style="max-width: 200px;">
                            @else
                                No attachment
                            @endif
                        </p>
                    </div>
                    {{-- <div class="col-12">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#replyModal">Reply to
                            Ticket</button>
                    </div> --}}
                </div>
            </div>
        </main>
    </div>

    <!-- Reply Modal -->
    {{-- <div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="replyModalLabel">Reply to Ticket</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('reply-to-ticket', ['id' => $ticket->id]) }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="reply">Your Reply</label>
                        <textarea class="form-control" id="reply" name="reply" rows="10" placeholder="Start typing your message here...." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Reply</button>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
