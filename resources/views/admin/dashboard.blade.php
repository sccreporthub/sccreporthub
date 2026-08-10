@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Admin Dashboard</h4>
        <p class="text-muted small mb-0">Welcome back, {{ auth()->user()->full_name }}</p>
    </div>
    <div class="text-muted small">{{ now()->format('l, F d, Y') }}</div>
</div>

<!-- ─── Stats Cards ─────────────────────────────────────────────────────────── -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.tickets.index') }}?all=1" class="text-decoration-none">
            <div class="stat-card stat-primary" style="cursor:pointer;">
                <div class="stat-icon"><i class="fas fa-ticket-alt"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['total_tickets'] }}</div>
                    <div class="stat-label">Active Tickets</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.tickets.index') }}?status=pending" class="text-decoration-none">
            <div class="stat-card stat-warning" style="cursor:pointer;">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['pending_tickets'] }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.monitoring.index') }}" class="text-decoration-none">
            <div class="stat-card stat-info" style="cursor:pointer;">
                <div class="stat-icon"><i class="fas fa-tools"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['ongoing_tickets'] }}</div>
                    <div class="stat-label">Ongoing</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.history') }}?status=completed" class="text-decoration-none">
            <div class="stat-card stat-success" style="cursor:pointer;">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['completed_tickets'] }}</div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.tickets.index') }}?all=1&priority=urgent" class="text-decoration-none">
            <div class="stat-card stat-danger" style="cursor:pointer;">
                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['urgent_tickets'] }}</div>
                    <div class="stat-label">Urgent Active</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.history') }}?status=rejected" class="text-decoration-none">
            <div class="stat-card stat-secondary" style="cursor:pointer;">
                <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['rejected_tickets'] }}</div>
                    <div class="stat-label">Rejected</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.users.index') }}?role=faculty" class="text-decoration-none">
            <div class="stat-card stat-primary" style="cursor:pointer;">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['total_users'] }}</div>
                    <div class="stat-label">Faculty/Staff</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.users.index') }}?role=maintenance" class="text-decoration-none">
            <div class="stat-card stat-info" style="cursor:pointer;">
                <div class="stat-icon"><i class="fas fa-hard-hat"></i></div>
                <div class="stat-info">
                    <div class="stat-value">{{ $stats['maintenance_staff'] }}</div>
                    <div class="stat-label">Maintenance Staff</div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- ─── Charts Row ──────────────────────────────────────────────────────────── -->
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">
                <i class="fas fa-chart-line me-2 text-primary"></i>Monthly Ticket Submissions (Last 6 Months)
            </div>
            <div class="card-body" style="height:250px; position:relative;">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">
                <i class="fas fa-chart-pie me-2 text-primary"></i>Priority Distribution
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="height:250px; position:relative;">
                <canvas id="priorityChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- ─── Status Distribution ─────────────────────────────────────────────────── -->
<div class="row g-3 mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <i class="fas fa-chart-bar me-2 text-primary"></i>Ticket Status Overview
            </div>
            <div class="card-body" style="height:200px; position:relative;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- ─── Recent Tickets Table ────────────────────────────────────────────────── -->
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="fas fa-list me-2 text-primary"></i>Recent Ticket Requests</span>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="recentTicketsTable">
                <thead class="table-light">
                    <tr>
                        <th>Ticket #</th>
                        <th>Title</th>
                        <th>Submitted By</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTickets as $ticket)
                    <tr>
                        <td><code>{{ $ticket->ticket_number }}</code></td>
                        <td>{{ Str::limit($ticket->title, 40) }}</td>
                        <td>{{ $ticket->user->full_name }}</td>
                        <td>
                            <span class="badge bg-{{ $ticket->priority_badge }} text-capitalize">
                                {{ $ticket->priority_level }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $ticket->status_badge }} text-capitalize">
                                {{ $ticket->status }}
                            </span>
                        </td>
                        <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No tickets yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Monthly Chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlyData['labels']) !!},
        datasets: [{
            label: 'Tickets Submitted',
            data: {!! json_encode($monthlyData['data']) !!},
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13,110,253,0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#0d6efd',
            pointRadius: 5,
        }]
    },
    options: {
        animation: { duration: 0 },
        responsive: true,
        maintainAspectRatio: false,
        resizeDelay: 200,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

// Priority Pie Chart
const priorityCtx = document.getElementById('priorityChart').getContext('2d');
new Chart(priorityCtx, {
    type: 'doughnut',
    data: {
        labels: ['Urgent', 'High', 'Normal'],
        datasets: [{
            data: [{{ $priorityData['urgent'] }}, {{ $priorityData['high'] }}, {{ $priorityData['normal'] }}],
            backgroundColor: ['#dc3545', '#ffc107', '#198754'],
            borderWidth: 2,
        }]
    },
    options: {
        animation: { duration: 0 },
        responsive: true,
        maintainAspectRatio: false,
        resizeDelay: 200,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Status Bar Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'bar',
    data: {
        labels: ['Pending', 'Approved', 'Assigned', 'Ongoing', 'Resolved', 'Completed', 'Rejected'],
        datasets: [{
            label: 'Tickets',
            data: [
                {{ $statusData['pending'] }}, {{ $statusData['approved'] }},
                {{ $statusData['assigned'] }}, {{ $statusData['ongoing'] }},
                {{ $statusData['resolved'] }}, {{ $statusData['completed'] }},
                {{ $statusData['rejected'] }}
            ],
            backgroundColor: ['#ffc107','#0dcaf0','#0d6efd','#6c757d','#20c997','#198754','#dc3545'],
        }]
    },
    options: {
        animation: { duration: 0 },
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

$(document).ready(function() {
    @if($recentTickets->count())
    $('#recentTicketsTable').DataTable({ paging: false, searching: false, info: false, order: [] });
    @endif
});
</script>
@endpush
