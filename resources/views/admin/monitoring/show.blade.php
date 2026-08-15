@extends('layouts.app')
@section('title', 'Monitor Task')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="fas fa-hard-hat me-2 text-primary"></i>Task Progress</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('admin.monitoring.index') }}">Maintenance Monitoring</a></li>
                <li class="breadcrumb-item active">{{ $ticket->ticket_number }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        @if(in_array($ticket->status, ['resolved', 'completed']))
        <a href="{{ route('admin.monitoring.receipt', $ticket) }}" class="btn btn-success" target="_blank">
            <i class="fas fa-print me-1"></i>Print Receipt
        </a>
        @endif
        <a href="{{ route('admin.monitoring.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left: Ticket Info + Logs -->
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
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
                        <label class="text-muted small">Submitted By</label>
                        <div>{{ $ticket->user->full_name }}</div>
                        <div class="text-muted small">{{ $ticket->user->department }}</div>
                        <div class="text-muted small">
                            <i class="fas fa-phone me-1"></i>{{ $ticket->user->contact_number ?? 'N/A' }}
                        </div>
                    </div>
                </div>

                @if($ticket->photo_path)
                <div class="mt-3">
                    <label class="text-muted small">Photo Evidence</label>
                    <div class="mt-2">
                        <img src="{{ $ticket->photo_url }}" alt="Evidence"
                             class="img-fluid rounded" style="max-height:280px; cursor:pointer;"
                             onclick="window.open(this.src,'_blank')">
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Maintenance Activity Log -->
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">
                <i class="fas fa-clipboard-list me-2 text-primary"></i>Maintenance Activity Log
            </div>
            @if($ticket->maintenanceLogs->count())
            <div class="card-body">
                <div class="timeline">
                    @foreach($ticket->maintenanceLogs as $log)
                    <div class="timeline-item">
                        <div class="timeline-dot bg-{{ $log->status === 'resolved' ? 'success' : 'primary' }}"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <strong>{{ $log->maintenanceStaff->full_name }}</strong>
                                <span class="text-muted small">{{ $log->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="mt-1"><strong>Action:</strong> {{ $log->action_taken }}</div>
                            @if($log->repair_notes)
                            <div><strong>Notes:</strong> {{ $log->repair_notes }}</div>
                            @endif
                            @if($log->materials_used)
                            <div><strong>Materials:</strong> {{ $log->materials_used }}</div>
                            @endif
                            @if($log->repair_cost > 0)
                            <div><strong>Cost:</strong> ₱{{ number_format($log->repair_cost, 2) }}</div>
                            @endif
                            <span class="badge bg-{{ $log->status === 'resolved' ? 'success' : 'info' }} mt-1">
                                {{ ucfirst($log->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="card-body text-center text-muted py-4 small">
                <i class="fas fa-clipboard fa-2x mb-2 d-block opacity-25"></i>
                No activity logged yet.
            </div>
            @endif
        </div>
    </div>

    <!-- Right: Actions + Staff Info -->
    <div class="col-md-4">
        <!-- Actions -->
        <div class="card shadow-sm mb-4">
            <div class="card-header fw-semibold">
                <i class="fas fa-cog me-2 text-primary"></i>Monitoring Actions
            </div>
            <div class="card-body d-grid gap-2">
                @if($ticket->status === 'resolved')
                <div class="alert alert-warning py-2 small mb-2">
                    <i class="fas fa-clock me-1"></i>
                    This task has been resolved by maintenance staff and is awaiting your verification.
                </div>
                <form method="POST" action="{{ route('admin.monitoring.verify', $ticket) }}">
                    @csrf
                    <button type="submit" class="btn btn-success w-100"
                            onclick="return confirm('Verify and mark this ticket as completed?')">
                        <i class="fas fa-check-double me-2"></i>Verify & Complete
                    </button>
                </form>
                <a href="{{ route('admin.monitoring.receipt', $ticket) }}" class="btn btn-outline-success w-100" target="_blank">
                    <i class="fas fa-print me-2"></i>Generate Receipt
                </a>
                @elseif($ticket->status === 'ongoing')
                <div class="alert alert-info py-2 small mb-0">
                    <i class="fas fa-tools me-1"></i>
                    Repair work is currently in progress.
                </div>
                @elseif($ticket->status === 'assigned')
                <div class="alert alert-secondary py-2 small mb-0">
                    <i class="fas fa-user-check me-1"></i>
                    Task has been assigned. Waiting for maintenance staff to start.
                </div>
                @elseif($ticket->status === 'completed')
                <a href="{{ route('admin.monitoring.receipt', $ticket) }}" class="btn btn-outline-success w-100" target="_blank">
                    <i class="fas fa-print me-2"></i>Print Receipt
                </a>
                @endif
            </div>
        </div>

        <!-- Assigned Staff -->
        @if($ticket->assignedStaff)
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">
                <i class="fas fa-hard-hat me-2 text-primary"></i>Assigned Staff
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $ticket->assignedStaff->profile_photo_url }}" alt="Avatar"
                         class="rounded-circle" width="48" height="48" style="object-fit:cover;"
                         onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($ticket->assignedStaff->full_name) }}&background=4f46e5&color=fff&size=48'">
                    <div>
                        <div class="fw-semibold">{{ $ticket->assignedStaff->full_name }}</div>
                        <div class="text-muted small">{{ $ticket->assignedStaff->department }}</div>
                        <div class="text-muted small">{{ $ticket->assignedStaff->contact_number }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
