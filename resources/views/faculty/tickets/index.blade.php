@extends('layouts.app')
@section('title', 'Request Monitoring')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="fas fa-magnifying-glass-chart me-2 text-primary"></i>Request Monitoring</h4>
        <p class="text-muted small mb-0">View & Track your ticket requests</p>
    </div>
    <a href="{{ route('faculty.tickets.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i>New Request
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="myTicketsTable">
                <thead class="table-light">
                    <tr>
                        <th>Ticket #</th>
                        <th>Title</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td><code class="small">{{ $ticket->ticket_number }}</code></td>
                        <td>
                            <div>{{ Str::limit($ticket->title, 40) }}</div>
                            @if($ticket->facility)
                            <div class="text-muted" style="font-size:0.75rem;"><i class="fas fa-location-dot me-1"></i>{{ $ticket->facility->full_location }}</div>
                            @endif
                            <div class="text-muted" style="font-size:0.75rem;">
                                <i class="fas {{ $ticket->category_icon }} me-1"></i>{{ $ticket->category_label }}
                            </div>
                        </td>
                        <td><span class="badge bg-{{ $ticket->priority_badge }} text-capitalize">{{ $ticket->priority_level }}</span></td>
                        <td><span class="badge bg-{{ $ticket->status_badge }} text-capitalize">{{ $ticket->status }}</span></td>
                        <td class="small text-nowrap">{{ $ticket->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('faculty.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No tickets yet. <a href="{{ route('faculty.tickets.create') }}">Submit your first request</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($tickets->hasPages())
    <div class="card-footer bg-white">{{ $tickets->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>$(document).ready(function() {
    @if($tickets->count())
    $('#myTicketsTable').DataTable({ paging: false, searching: true, info: false, order: [[4,'desc']] });
    @endif
});</script>
@endpush
