@extends('layouts.app')
@section('title', 'Ticket Details')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="fas fa-ticket-alt me-2 text-primary"></i>Ticket Details</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item">
                    @if(request('from') === 'history')
                        <a href="{{ route('faculty.history') }}">History</a>
                    @else
                        <a href="{{ route('faculty.tickets.index') }}">Request Monitoring</a>
                    @endif
                </li>
                <li class="breadcrumb-item active">{{ $ticket->ticket_number }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ request('from') === 'history' ? route('faculty.history') : route('faculty.tickets.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <!-- Ticket Info -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Ticket Information</span>
                <div class="d-flex gap-2">
                    <span class="badge bg-{{ $ticket->priority_badge }} text-capitalize">{{ $ticket->priority_level }}</span>
                    <span class="badge bg-{{ $ticket->status_badge }} text-capitalize">{{ $ticket->status }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Ticket Number</label>
                        <div class="fw-semibold"><code>{{ $ticket->ticket_number }}</code></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Date Submitted</label>
                        <div>{{ $ticket->created_at->format('F d, Y h:i A') }}</div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">Title</label>
                        <div class="fw-semibold">{{ $ticket->title }}</div>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">Description</label>
                        <div class="p-3 bg-light rounded">{{ $ticket->description }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Location</label>
                        <div>{{ $ticket->facility?->full_location ?? 'Not specified' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Issue Category</label>
                        <div>
                            <i class="fas {{ $ticket->category_icon }} me-1 text-primary"></i>
                            {{ $ticket->category_label }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Assigned To</label>
                        <div>{{ $ticket->assignedStaff?->full_name ?? 'Not yet assigned' }}</div>
                    </div>
                </div>

                @if($ticket->photo_path)
                <div class="mt-3">
                    <label class="text-muted small">Photo Evidence</label>
                    <div class="mt-2">
                        <img src="{{ $ticket->photo_url }}" alt="Evidence" class="img-fluid rounded" style="max-height:250px; cursor:pointer;" onclick="window.open(this.src,'_blank')">
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="fas fa-stream me-2 text-primary"></i>Status Timeline
            </div>
            <div class="card-body">
                @php
                    $statuses = ['pending','approved','assigned','ongoing','resolved','completed'];
                    $currentIndex = array_search($ticket->status, $statuses);
                    if ($ticket->status === 'rejected') $currentIndex = -1;
                @endphp
                @if($ticket->status === 'rejected')
                <div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>This ticket has been rejected.</div>
                @else
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    @foreach($statuses as $i => $status)
                    <div class="text-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1
                            {{ $i <= $currentIndex ? 'bg-primary text-white' : 'bg-light text-muted border' }}"
                            style="width:40px;height:40px;">
                            @if($i < $currentIndex)
                                <i class="fas fa-check fa-sm"></i>
                            @elseif($i === $currentIndex)
                                <i class="fas fa-circle fa-sm"></i>
                            @else
                                <span class="small">{{ $i+1 }}</span>
                            @endif
                        </div>
                        <div class="small text-capitalize {{ $i <= $currentIndex ? 'fw-semibold text-primary' : 'text-muted' }}">{{ $status }}</div>
                    </div>
                    @if($i < count($statuses)-1)
                    <div class="flex-grow-1" style="height:2px; background:{{ $i < $currentIndex ? 'var(--primary, #4f46e5)' : '#dee2e6' }};"></div>
                    @endif
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Maintenance Logs -->
        @if($ticket->maintenanceLogs->count())
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <i class="fas fa-clipboard-list me-2 text-primary"></i>Repair Activity
            </div>
            <div class="card-body p-0">
                <div class="timeline p-3">
                    @foreach($ticket->maintenanceLogs as $log)
                    <div class="timeline-item">
                        <div class="timeline-dot bg-{{ $log->status === 'resolved' ? 'success' : 'primary' }}"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $log->maintenanceStaff->full_name }}</strong>
                                <span class="text-muted small">{{ $log->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div>{{ $log->action_taken }}</div>
                            @if($log->repair_notes)<div class="text-muted small">{{ $log->repair_notes }}</div>@endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Right: Actions -->
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold"><i class="fas fa-info-circle me-2 text-primary"></i>Ticket Summary</div>
            <div class="card-body">
                <div class="mb-2">
                    <div class="text-muted small">Submitted</div>
                    <div class="small">{{ $ticket->created_at->format('M d, Y') }}</div>
                </div>
                @if($ticket->approved_at)
                <div class="mb-2">
                    <div class="text-muted small">Reviewed</div>
                    <div class="small">{{ $ticket->approved_at->format('M d, Y') }}</div>
                </div>
                @endif
                @if($ticket->completed_at)
                <div class="mb-2">
                    <div class="text-muted small">Completed</div>
                    <div class="small">{{ $ticket->completed_at->format('M d, Y') }}</div>
                </div>
                @endif
                @if($ticket->assignedStaff)
                <div class="mb-2">
                    <div class="text-muted small">Assigned Staff</div>
                    <div class="small fw-semibold">{{ $ticket->assignedStaff->full_name }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Feedback Section --}}
        @if($ticket->status === 'completed')
            @if($ticket->feedback)
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="fas fa-star me-2 text-warning"></i>Your Feedback
                </div>
                <div class="card-body">
                    <div class="d-flex gap-1 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $ticket->feedback->rating ? 'text-warning' : 'text-muted' }}" style="font-size:1.1rem;"></i>
                        @endfor
                        <span class="ms-2 fw-semibold text-muted small">{{ $ticket->feedback->rating }}/5</span>
                    </div>
                    @if($ticket->feedback->comment)
                    <p class="text-muted small mb-0 fst-italic">"{{ $ticket->feedback->comment }}"</p>
                    @endif
                    <div class="text-muted" style="font-size:0.72rem;">Submitted {{ $ticket->feedback->created_at->format('M d, Y') }}</div>
                </div>
            </div>
            @else
            <div class="card shadow-sm border-warning border-2">
                <div class="card-body text-center p-4">
                    <i class="fas fa-star fa-2x text-warning mb-2"></i>
                    <h6 class="fw-semibold mb-1">How was the service?</h6>
                    <p class="text-muted small mb-3">Your repair is complete. Rate the maintenance service to help us improve.</p>
                    <a href="{{ route('faculty.feedback.create', $ticket) }}" class="btn btn-warning w-100 fw-semibold">
                        <i class="fas fa-star me-2"></i>Submit Feedback
                    </a>
                </div>
            </div>
            @endif
        @endif
    </div>
</div>
@endsection
