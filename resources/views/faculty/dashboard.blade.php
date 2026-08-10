@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i>Dashboard</h4>
        <p class="text-muted small mb-0">Welcome, {{ auth()->user()->full_name }} — {{ auth()->user()->department }}</p>
    </div>
    <a href="{{ route('faculty.tickets.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i><span class="d-none d-sm-inline">Create Ticket Request</span><span class="d-sm-none">New Request</span>
    </a>
</div>

<!-- Stats -->
<div class="row g-2 mb-4">
    <div class="col-6">
        <a href="{{ route('faculty.tickets.index') }}" class="text-decoration-none">
            <div class="stat-card stat-primary" style="cursor:pointer;">
                <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['total_tickets'] }}</div>
                    <div class="stat-label">Active</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6">
        <a href="{{ route('faculty.tickets.index') }}?status=pending" class="text-decoration-none">
            <div class="stat-card stat-warning" style="cursor:pointer;">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['pending_tickets'] }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6">
        <a href="{{ route('faculty.tickets.index') }}?status=ongoing" class="text-decoration-none">
            <div class="stat-card stat-info" style="cursor:pointer;">
                <div class="stat-icon"><i class="fas fa-screwdriver-wrench"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['ongoing_tickets'] }}</div>
                    <div class="stat-label">In Progress</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6">
        <a href="{{ route('faculty.history') }}" class="text-decoration-none">
            <div class="stat-card stat-success" style="cursor:pointer;">
                <div class="stat-icon"><i class="fas fa-circle-check"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['completed_tickets'] }}</div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Tickets -->
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="fas fa-clipboard-list me-2 text-primary"></i>My Recent Requests</span>
                <a href="{{ route('faculty.tickets.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($recentTickets as $ticket)
                <a href="{{ route('faculty.tickets.show', $ticket) }}" class="list-group-item list-group-item-action px-3 py-3">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div class="flex-grow-1 min-width-0">
                            <div class="fw-semibold small text-truncate">{{ $ticket->title }}</div>
                            <code style="font-size:0.72rem;">{{ $ticket->ticket_number }}</code>
                            <div class="text-muted" style="font-size:0.72rem;">{{ $ticket->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-1 flex-shrink-0">
                            <span class="badge bg-{{ $ticket->priority_badge }} text-capitalize">{{ $ticket->priority_level }}</span>
                            <span class="badge bg-{{ $ticket->status_badge }} text-capitalize">{{ $ticket->status }}</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="list-group-item text-center text-muted py-4">
                    No requests yet. <a href="{{ route('faculty.tickets.create') }}">Submit your first</a>.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-12 col-lg-4">
        <!-- Notifications -->
        <div class="card shadow-sm mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="fas fa-bell me-2 text-primary"></i>Notifications</span>
                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($unreadNotifications as $notif)
                <div class="list-group-item {{ !$notif->is_read ? 'bg-light' : '' }}">
                    <div class="small">{{ Str::limit($notif->message, 80) }}</div>
                    <div class="text-muted" style="font-size:0.75rem;">{{ $notif->created_at->diffForHumans() }}</div>
                </div>
                @empty
                <div class="list-group-item text-center text-muted py-3 small">No new notifications.</div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">
                <i class="fas fa-bolt me-2 text-primary"></i>Quick Actions
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('faculty.tickets.create') }}" class="btn btn-primary">
                    <i class="fas fa-circle-plus me-2"></i>Create Ticket Request
                </a>
                <a href="{{ route('faculty.tickets.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-magnifying-glass-chart me-2"></i>Request Monitoring
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
