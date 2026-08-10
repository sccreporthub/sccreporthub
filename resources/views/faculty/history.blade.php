@extends('layouts.app')
@section('title', 'History')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="fas fa-clock-rotate-left me-2 text-primary"></i>History</h4>
        <p class="text-muted small mb-0">Your completed and rejected ticket requests</p>
    </div>
</div>

<div class="list-group shadow-sm">
    @forelse($tickets as $ticket)
    <a href="{{ route('faculty.tickets.show', [$ticket, 'from' => 'history']) }}"
       class="list-group-item list-group-item-action px-3 py-3">
        <div class="d-flex justify-content-between align-items-start gap-2">
            <div class="flex-grow-1 min-width-0">
                <div class="fw-semibold small text-truncate">{{ $ticket->title }}</div>
                <code style="font-size:0.72rem;">{{ $ticket->ticket_number }}</code>
                @if($ticket->facility)
                <div class="text-muted" style="font-size:0.72rem;">
                    <i class="fas fa-location-dot me-1"></i>{{ $ticket->facility->full_location }}
                </div>
                @endif
                <div class="text-muted" style="font-size:0.72rem;">
                    {{ $ticket->completed_at?->format('M d, Y') ?? $ticket->updated_at->format('M d, Y') }}
                </div>
            </div>
            <div class="d-flex flex-column align-items-end gap-1 flex-shrink-0">
                <span class="badge bg-{{ $ticket->status_badge }} text-capitalize">{{ $ticket->status }}</span>

            </div>
        </div>
    </a>
    @empty
    <div class="card shadow-sm">
        <div class="card-body text-center text-muted py-5">
            <i class="fas fa-clock-rotate-left fa-3x mb-3 opacity-25"></i>
            <div>No completed requests yet.</div>
        </div>
    </div>
    @endforelse
</div>

@if($tickets->hasPages())
<div class="mt-3">{{ $tickets->links() }}</div>
@endif
@endsection
