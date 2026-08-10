@extends('layouts.app')
@section('title', 'Feedback')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="fas fa-star me-2 text-warning"></i>Feedback</h4>
        <p class="text-muted small mb-0">User ratings and comments on completed maintenance requests</p>
    </div>
</div>

{{-- Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center p-4">
                <div class="display-5 fw-bold text-warning mb-1">
                    {{ $avgRating ? number_format($avgRating, 1) : '—' }}
                </div>
                <div class="d-flex justify-content-center gap-1 mb-1">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $avgRating && $i <= round($avgRating) ? 'text-warning' : 'text-muted' }}"></i>
                    @endfor
                </div>
                <div class="text-muted small">Average Rating</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center p-4">
                <div class="display-5 fw-bold text-primary mb-1">{{ $totalCount }}</div>
                <div class="text-muted small">Total Feedback Submitted</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-4">
                <div class="text-muted small fw-semibold mb-2">Rating Breakdown</div>
                @for($i = 5; $i >= 1; $i--)
                @php $count = $ratingCounts[$i] ?? 0; $pct = $totalCount > 0 ? ($count / $totalCount) * 100 : 0; @endphp
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="text-muted small" style="width:12px;">{{ $i }}</span>
                    <i class="fas fa-star text-warning" style="font-size:0.75rem;"></i>
                    <div class="flex-grow-1 bg-light rounded" style="height:8px;">
                        <div class="bg-warning rounded" style="height:8px; width:{{ $pct }}%;"></div>
                    </div>
                    <span class="text-muted small" style="width:20px;">{{ $count }}</span>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body py-3 px-4">
        <form method="GET" action="{{ route('admin.feedback') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm"
                    placeholder="Search ticket or user..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="rating" class="form-select form-select-sm">
                    <option value="">All Ratings</option>
                    @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>
                <a href="{{ route('admin.feedback') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Feedback Table --}}
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        @if($feedbacks->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-star fa-3x mb-3 opacity-25"></i>
            <div>No feedback submitted yet.</div>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">User</th>
                        <th>Ticket</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedbacks as $feedback)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold small">{{ $feedback->user->full_name }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">{{ $feedback->user->department }}</div>
                        </td>
                        <td>
                            <a href="{{ route('admin.tickets.show', $feedback->ticket) }}" class="text-decoration-none small fw-semibold">
                                #{{ $feedback->ticket->ticket_number }}
                            </a>
                            <div class="text-muted" style="font-size:0.75rem;">{{ Str::limit($feedback->ticket->title, 40) }}</div>
                        </td>
                        <td>
                            <div class="d-flex gap-1 align-items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $feedback->rating ? 'text-warning' : 'text-muted' }}" style="font-size:0.8rem;"></i>
                                @endfor
                                <span class="ms-1 small fw-semibold">{{ $feedback->rating }}</span>
                            </div>
                        </td>
                        <td>
                            @if($feedback->comment)
                                <span class="text-muted small fst-italic">"{{ Str::limit($feedback->comment, 80) }}"</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted small">{{ $feedback->created_at->format('M d, Y') }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($feedbacks->hasPages())
        <div class="px-4 py-3 border-top">
            {{ $feedbacks->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
