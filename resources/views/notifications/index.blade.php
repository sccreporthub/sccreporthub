@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="page-header mb-4">
    <h4 class="fw-bold mb-0"><i class="fas fa-bell me-2 text-primary"></i>Notifications</h4>
    <form method="POST" action="{{ route('notifications.mark-all-read') }}">
        @csrf
        <button type="submit" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-check-double me-1"></i>Mark All Read
        </button>
    </form>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        @forelse($notifications as $notif)
        <a href="{{ $notif->url }}" class="d-flex align-items-start p-3 border-bottom text-decoration-none text-dark notif-row {{ !$notif->is_read ? 'bg-light' : '' }}" style="transition: background 0.15s;">
            <div class="me-3 mt-1">
                @php
                    $icon = match($notif->type) {
                        'new_ticket'       => 'fa-ticket-alt text-primary',
                        'ticket_approved'  => 'fa-check-circle text-success',
                        'ticket_rejected'  => 'fa-times-circle text-danger',
                        'ticket_assigned'  => 'fa-user-plus text-info',
                        'task_assigned'    => 'fa-hard-hat text-warning',
                        'task_started'     => 'fa-play-circle text-info',
                        'task_resolved'    => 'fa-check-double text-success',
                        'ticket_completed' => 'fa-star text-warning',
                        default            => 'fa-bell text-secondary',
                    };
                @endphp
                <i class="fas {{ $icon }} fa-lg"></i>
            </div>
            <div class="flex-grow-1">
                <div class="small {{ !$notif->is_read ? 'fw-semibold' : '' }}">{{ $notif->message }}</div>
                <div class="text-muted" style="font-size:0.75rem;">{{ $notif->created_at->diffForHumans() }} — {{ $notif->created_at->format('M d, Y h:i A') }}</div>
            </div>
            @if(!$notif->is_read)
            <span class="badge bg-primary rounded-pill ms-2 align-self-center">New</span>
            @endif
        </a>
        @empty
        <div class="text-center text-muted py-5">
            <i class="fas fa-bell-slash fa-3x mb-3"></i>
            <div>No notifications yet.</div>
        </div>
        @endforelse
    </div>
    @if($notifications->hasPages())
    <div class="card-footer bg-white">{{ $notifications->links() }}</div>
    @endif
</div>
@endsection

@push('styles')
<style>
.notif-row:hover {
    background-color: #f0f4ff !important;
}
</style>
@endpush
