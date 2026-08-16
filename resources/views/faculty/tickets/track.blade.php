@extends('layouts.app')
@section('title', 'Request Monitoring')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="fas fa-magnifying-glass-chart me-2 text-primary"></i>Request Monitoring</h4>
        <p class="text-muted small mb-0">View & Track your ticket requests</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('faculty.tickets.track') }}">
                    <label class="form-label fw-semibold">Enter Ticket Number</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-ticket-alt text-muted"></i></span>
                        <input type="text" name="ticket_number" class="form-control form-control-lg @error('ticket_number') is-invalid @enderror"
                               placeholder="e.g. TKT-2627-0001" value="{{ request('ticket_number') }}" required>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-search me-2"></i>Track
                        </button>
                        @error('ticket_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </form>
            </div>
        </div>

        @if(isset($ticket))
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Ticket: <code>{{ $ticket->ticket_number }}</code></span>
                <div class="d-flex gap-2">
                    <span class="badge bg-{{ $ticket->priority_badge }} text-capitalize">{{ $ticket->priority_level }}</span>
                    <span class="badge bg-{{ $ticket->status_badge }} text-capitalize fs-6">{{ $ticket->status }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="text-muted small">Title</div>
                        <div class="fw-semibold">{{ $ticket->title }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Location</div>
                        <div>{{ $ticket->facility?->full_location ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Submitted</div>
                        <div>{{ $ticket->created_at->format('F d, Y') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Assigned To</div>
                        <div>{{ $ticket->assignedStaff?->full_name ?? 'Not yet assigned' }}</div>
                    </div>
                </div>

                <!-- Progress Steps -->
                @php
                    $statuses = ['pending','approved','assigned','ongoing','resolved','completed'];
                    $currentIndex = array_search($ticket->status, $statuses);
                @endphp
                @if($ticket->status !== 'rejected')
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                    @foreach($statuses as $i => $status)
                    <div class="text-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1
                            {{ $i <= $currentIndex ? 'bg-primary text-white' : 'bg-light text-muted border' }}"
                            style="width:36px;height:36px;">
                            @if($i < $currentIndex)<i class="fas fa-check fa-xs"></i>
                            @elseif($i === $currentIndex)<i class="fas fa-circle fa-xs"></i>
                            @else<span style="font-size:0.7rem;">{{ $i+1 }}</span>@endif
                        </div>
                        <div class="text-capitalize" style="font-size:0.7rem; {{ $i <= $currentIndex ? 'font-weight:600;color:var(--primary,#4f46e5);' : 'color:#6c757d;' }}">{{ $status }}</div>
                    </div>
                    @if($i < count($statuses)-1)
                    <div class="flex-grow-1" style="height:2px;background:{{ $i < $currentIndex ? 'var(--primary,#4f46e5)' : '#dee2e6' }};"></div>
                    @endif
                    @endforeach
                </div>
                @else
                <div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>This ticket has been rejected.</div>
                @endif

                <a href="{{ route('faculty.tickets.show', $ticket) }}" class="btn btn-primary">
                    <i class="fas fa-eye me-2"></i>View Full Details
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
