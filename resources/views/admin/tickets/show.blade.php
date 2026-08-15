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
                        <a href="{{ route('admin.history') }}">History</a>
                    @else
                        <a href="{{ route('admin.tickets.index') }}">Tickets</a>
                    @endif
                </li>
                <li class="breadcrumb-item active">{{ $ticket->ticket_number }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        @if($ticket->status === 'resolved')
        <a href="{{ route('admin.tickets.receipt', $ticket) }}" class="btn btn-success" target="_blank">
            <i class="fas fa-print me-1"></i>Print Receipt
        </a>
        @endif
        <a href="{{ request('from') === 'history' ? route('admin.history') : route('admin.tickets.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Ticket Info -->
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Ticket Information</span>
                <div class="d-flex gap-2">
                    <span class="badge bg-{{ $ticket->priority_badge }} text-capitalize fs-6">
                        {{ $ticket->priority_level }}
                    </span>
                    <span class="badge bg-{{ $ticket->status_badge }} text-capitalize fs-6">
                        {{ $ticket->status }}
                    </span>
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
                        <label class="text-muted small">Facility Location</label>
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
                        @if($ticket->contact_number)
                        <div class="text-muted small"><i class="fas fa-phone me-1"></i>{{ $ticket->contact_number }}</div>
                        @endif
                    </div>
                    @if($ticket->approved_at)
                    <div class="col-md-6">
                        <label class="text-muted small">Reviewed By</label>
                        <div>{{ $ticket->approvedBy?->full_name ?? '—' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Review Date</label>
                        <div>{{ $ticket->approved_at->format('F d, Y h:i A') }}</div>
                    </div>
                    @endif
                    @if($ticket->completed_at)
                    <div class="col-md-6">
                        <label class="text-muted small">Completed At</label>
                        <div>{{ $ticket->completed_at->format('F d, Y h:i A') }}</div>
                    </div>
                    @endif
                </div>

                @if($ticket->photo_path)
                <div class="mt-3">
                    <label class="text-muted small">Photo Evidence</label>
                    <div class="mt-2">
                        <img src="{{ $ticket->photo_url }}" alt="Evidence" class="img-fluid rounded" style="max-height:300px; cursor:pointer;"
                             onclick="window.open(this.src,'_blank')">
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Maintenance Logs -->
        @if($ticket->maintenanceLogs->count())
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="fas fa-clipboard-list me-2 text-primary"></i>Maintenance Activity Log
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
                            <span class="badge bg-{{ $log->status === 'resolved' ? 'success' : 'info' }} mt-1">{{ ucfirst($log->status) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Right Column: Actions -->
    <div class="col-md-4">
        <!-- Admin Actions -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">
                <i class="fas fa-cog me-2 text-primary"></i>Admin Actions
            </div>
            <div class="card-body d-grid gap-2">

                @if($ticket->status === 'pending')
                <!-- Approve -->
                <form method="POST" action="{{ route('admin.tickets.approve', $ticket) }}">
                    @csrf
                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Approve this ticket?')">
                        <i class="fas fa-check me-2"></i>Approve Ticket
                    </button>
                </form>
                <!-- Reject -->
                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="fas fa-times me-2"></i>Reject Ticket
                </button>
                @endif

                @if(in_array($ticket->status, ['approved']))
                <!-- Assign -->
                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#assignModal">
                    <i class="fas fa-user-plus me-2"></i>Assign to Staff
                </button>
                @endif

                @if($ticket->status === 'resolved')
                <!-- Verify Completion -->
                <form method="POST" action="{{ route('admin.tickets.verify', $ticket) }}">
                    @csrf
                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Mark this ticket as completed?')">
                        <i class="fas fa-check-double me-2"></i>Verify & Complete
                    </button>
                </form>
                <a href="{{ route('admin.tickets.receipt', $ticket) }}" class="btn btn-outline-success w-100" target="_blank">
                    <i class="fas fa-print me-2"></i>Print Receipt
                </a>
                @endif

                @if($ticket->status === 'completed')
                <a href="{{ route('admin.tickets.receipt', $ticket) }}" class="btn btn-outline-success w-100" target="_blank">
                    <i class="fas fa-print me-2"></i>Print Receipt
                </a>
                @endif
            </div>
        </div>

        <!-- Assigned Staff Info -->
        @if($ticket->assignedStaff)
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <i class="fas fa-hard-hat me-2 text-primary"></i>Assigned Staff
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $ticket->assignedStaff->profile_photo_url }}" alt="Avatar" class="rounded-circle" width="48" height="48" style="object-fit:cover;">
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

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.tickets.reject', $ticket) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title text-danger"><i class="fas fa-times-circle me-2"></i>Reject Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                    <textarea name="rejection_reason" class="form-control" rows="4" placeholder="Explain why this ticket is being rejected..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.tickets.assign', $ticket) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title text-primary"><i class="fas fa-user-plus me-2"></i>Assign Maintenance Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 p-2 bg-light rounded small">
                        <i class="fas {{ $ticket->category_icon }} me-1 text-primary"></i>
                        <strong>{{ $ticket->category_label }}</strong>
                        <span class="text-muted ms-1">— matched staff are marked ✓</span>
                    </div>
                    <label class="form-label fw-semibold">Select Maintenance Staff <span class="text-danger">*</span></label>
                    <select name="assigned_to" class="form-select" required>
                        <option value="">-- Select Staff --</option>
                        @php
                            $matched = $maintenanceStaff->filter(function($s) use ($ticket) {
                                $specs = $s->specialization ?? [];
                                if (is_string($specs)) $specs = [$specs];
                                foreach ($specs as $spec) {
                                    $cats = \App\Models\User::SPECIALIZATION_CATEGORIES[$spec] ?? [];
                                    if (in_array($ticket->issue_category, $cats)) return true;
                                }
                                return false;
                            });
                            $others = $maintenanceStaff->filter(function($s) use ($ticket) {
                                $specs = $s->specialization ?? [];
                                if (is_string($specs)) $specs = [$specs];
                                foreach ($specs as $spec) {
                                    $cats = \App\Models\User::SPECIALIZATION_CATEGORIES[$spec] ?? [];
                                    if (in_array($ticket->issue_category, $cats)) return false;
                                }
                                return true;
                            });
                        @endphp

                        @if($matched->count())
                        <optgroup label="✓ Matched Specialization">
                            @foreach($matched as $staff)
                            <option value="{{ $staff->id }}">
                                ✓ {{ $staff->full_name }} — {{ $staff->specialization_labels }}
                            </option>
                            @endforeach
                        </optgroup>
                        @endif

                        @if($others->count())
                        <optgroup label="Other Staff">
                            @foreach($others as $staff)
                            <option value="{{ $staff->id }}">
                                {{ $staff->full_name }}
                                @if(!empty($staff->specialization))
                                    — {{ $staff->specialization_labels }}
                                @endif
                            </option>
                            @endforeach
                        </optgroup>
                        @endif
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
