@extends('layouts.app')
@section('title', 'View Task Details')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="fas fa-screwdriver-wrench me-2 text-primary"></i>View Task Details</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item">
                    @if(request('from') === 'history')
                        <a href="{{ route('maintenance.tasks.completed') }}">Tasks History</a>
                    @else
                        <a href="{{ route('maintenance.tasks.index') }}">Assigned Maintenance Tasks</a>
                    @endif
                </li>
                <li class="breadcrumb-item active">{{ $ticket->ticket_number }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ request('from') === 'history' ? route('maintenance.tasks.completed') : route('maintenance.tasks.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>

<div class="row g-4">
    <!-- Task Info -->
    <div class="col-md-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Task Information</span>
                <div class="d-flex gap-2">
                    <span class="badge bg-{{ $ticket->priority_badge }} text-capitalize">{{ $ticket->priority_level }}</span>
                    <span class="badge bg-{{ $ticket->status_badge }} text-capitalize">{{ $ticket->status }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Ticket Number</div>
                        <div class="fw-semibold"><code>{{ $ticket->ticket_number }}</code></div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Date Submitted</div>
                        <div>{{ $ticket->created_at->format('F d, Y') }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Title</div>
                        <div class="fw-semibold">{{ $ticket->title }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Description</div>
                        <div class="p-3 bg-light rounded">{{ $ticket->description }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Location</div>
                        <div>{{ $ticket->facility?->full_location ?? 'Not specified' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Reported By</div>
                        <div>{{ $ticket->user->full_name }}</div>
                        <div class="text-muted small">{{ $ticket->user->department }}</div>
                        @if($ticket->contact_number)
                        <div class="text-muted small"><i class="fas fa-phone me-1"></i>{{ $ticket->contact_number }}</div>
                        @endif
                    </div>
                </div>

                @if($ticket->photo_path)
                <div class="mt-3">
                    <div class="text-muted small mb-2">Photo Evidence</div>
                    <img src="{{ $ticket->photo_url }}" alt="Evidence" class="img-fluid rounded" style="max-height:250px; cursor:pointer;" onclick="window.open(this.src,'_blank')">
                </div>
                @endif
            </div>
        </div>

        <!-- Activity Log -->
        @if($ticket->maintenanceLogs->count())
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <i class="fas fa-clipboard-list me-2 text-primary"></i>Activity Log
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
                            @if($log->repair_notes)<div class="text-muted small">Notes: {{ $log->repair_notes }}</div>@endif
                            @if($log->materials_used)<div class="text-muted small">Materials: {{ $log->materials_used }}</div>@endif
                            @if($log->repair_cost > 0)<div class="text-muted small">Cost: ₱{{ number_format($log->repair_cost, 2) }}</div>@endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Actions Panel -->
    <div class="col-md-5">
        @if(in_array($ticket->status, ['assigned', 'ongoing']))

        <!-- Perform Repair / Start Task -->
        @if($ticket->status === 'assigned')
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="fas fa-play me-2 text-primary"></i>Perform Repair</h6>
                <p class="text-muted small">Click below to start working on this task.</p>
                <form method="POST" action="{{ route('maintenance.tasks.start', $ticket) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Start working on this task?')">
                        <i class="fas fa-play me-2"></i>Start Repair
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Input Repair Details -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">
                <i class="fas fa-edit me-2 text-primary"></i>Input Repair Details (Cost, etc.)
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('maintenance.tasks.update-repair', $ticket) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small">Action Taken <span class="text-danger">*</span></label>
                        <textarea name="action_taken" class="form-control form-control-sm @error('action_taken') is-invalid @enderror"
                                  rows="3" placeholder="Describe what was done..." required>{{ old('action_taken') }}</textarea>
                        @error('action_taken')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Repair Notes</label>
                        <textarea name="repair_notes" class="form-control form-control-sm" rows="2" placeholder="Additional notes...">{{ old('repair_notes') }}</textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small">Repair Cost (₱)</label>
                            <input type="number" name="repair_cost" class="form-control form-control-sm" step="0.01" min="0" value="{{ old('repair_cost', 0) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Materials Used</label>
                            <input type="text" name="materials_used" class="form-control form-control-sm" placeholder="e.g. Wires, bulbs" value="{{ old('materials_used') }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary w-100 btn-sm">
                        <i class="fas fa-save me-2"></i>Save Repair Details
                    </button>
                </form>
            </div>
        </div>

        <!-- Update Status: Resolved -->
        <div class="card shadow-sm border-success">
            <div class="card-header bg-white fw-semibold text-success">
                <i class="fas fa-check-circle me-2"></i>Update Status: Resolved
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('maintenance.tasks.resolve', $ticket) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small">Final Action Taken <span class="text-danger">*</span></label>
                        <textarea name="action_taken" class="form-control form-control-sm @error('action_taken') is-invalid @enderror"
                                  rows="3" placeholder="Describe the final repair performed..." required>{{ old('action_taken') }}</textarea>
                        @error('action_taken')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Resolution Notes <span class="text-danger">*</span></label>
                        <textarea name="repair_notes" class="form-control form-control-sm @error('repair_notes') is-invalid @enderror"
                                  rows="2" placeholder="Summary of the repair..." required>{{ old('repair_notes') }}</textarea>
                        @error('repair_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small">Total Repair Cost (₱) <span class="text-danger">*</span></label>
                            <input type="number" name="repair_cost" class="form-control form-control-sm @error('repair_cost') is-invalid @enderror"
                                   step="0.01" min="0" value="{{ old('repair_cost', 0) }}" required>
                            @error('repair_cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Materials Used</label>
                            <input type="text" name="materials_used" class="form-control form-control-sm" value="{{ old('materials_used') }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Mark this task as resolved? This will notify the admin for verification.')">
                        <i class="fas fa-check-double me-2"></i>Update Status: Resolved
                    </button>
                </form>
            </div>
        </div>

        @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h6>Task {{ ucfirst($ticket->status) }}</h6>
                <p class="text-muted small">This task has been {{ $ticket->status }}.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
