<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SCC ReportHub')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body>

<!-- ─── Sidebar ──────────────────────────────────────────────────────────── -->
<div class="wrapper d-flex">
    <nav id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <div class="d-flex align-items-center justify-content-between">
                <div class="sidebar-logo">
                    <div class="logo-text">
                        <span class="logo-title">SCC</span>
                        <span class="logo-subtitle">ReportHub</span>
                    </div>
                </div>
                {{-- Close button inside sidebar --}}
                <button id="sidebarClose" class="btn btn-link p-1" style="color:rgba(255,255,255,0.5);">
                    <i class="fas fa-xmark fa-lg"></i>
                </button>
            </div>
        </div>

        <div class="sidebar-user">
            <img src="{{ auth()->user()->profile_photo_url }}" alt="Avatar" class="user-avatar" onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->full_name) }}&background=0d6efd&color=fff&size=40'">
            <div class="user-info">
                <span class="user-name">{{ auth()->user()->full_name }}</span>
                <span class="user-role badge bg-primary">{{ auth()->user()->role->name }}</span>
            </div>
        </div>

        <ul class="sidebar-nav">
            @if(auth()->user()->isAdmin())
                <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-pie"></i> Dashboard</a>
                </li>
                <li class="nav-item {{ (request()->routeIs('admin.tickets.*') && request()->get('from') !== 'history') ? 'active' : '' }}">
                    <a href="{{ route('admin.tickets.index') }}"><i class="fas fa-clipboard-list"></i> Ticket Request Management</a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.monitoring.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.monitoring.index') }}"><i class="fas fa-wrench"></i> Maintenance Monitoring</a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i> User Management</a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.history') || request()->get('from') === 'history' ? 'active' : '' }}">
                    <a href="{{ route('admin.history') }}"><i class="fas fa-clock-rotate-left"></i> History</a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.facilities.index') }}"><i class="fas fa-building"></i> Facility Management</a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.feedback') ? 'active' : '' }}">
                    <a href="{{ route('admin.feedback') }}"><i class="fas fa-star"></i> Feedback</a>
                </li>

                <li class="nav-divider"></li>

                {{-- Settings as dedicated page --}}
                <li class="nav-item {{ request()->routeIs('admin.settings') || request()->routeIs('profile.*') || request()->routeIs('password.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.settings') }}"><i class="fas fa-gear"></i> Settings</a>
                </li>

            @elseif(auth()->user()->isFaculty())
                <li class="nav-item {{ request()->routeIs('faculty.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('faculty.dashboard') }}"><i class="fas fa-chart-pie"></i> Dashboard</a>
                </li>
                <li class="nav-item {{ request()->routeIs('faculty.tickets.create') ? 'active' : '' }}">
                    <a href="{{ route('faculty.tickets.create') }}"><i class="fas fa-circle-plus"></i> Create Ticket Request</a>
                </li>
                <li class="nav-item {{ (request()->routeIs('faculty.tickets.index') || request()->routeIs('faculty.tickets.show') || request()->routeIs('faculty.tickets.track')) && request()->get('from') !== 'history' ? 'active' : '' }}">
                    <a href="{{ route('faculty.tickets.index') }}"><i class="fas fa-magnifying-glass-chart"></i> Request Monitoring</a>
                </li>
                <li class="nav-item {{ request()->routeIs('faculty.history') || request()->get('from') === 'history' ? 'active' : '' }}">
                    <a href="{{ route('faculty.history') }}"><i class="fas fa-clock-rotate-left"></i> History</a>
                </li>

                <li class="nav-divider"></li>

                <li class="nav-item {{ request()->routeIs('settings.*') || request()->routeIs('profile.*') || request()->routeIs('password.*') ? 'active' : '' }}">
                    <a href="{{ route('settings.index') }}"><i class="fas fa-gear"></i> Settings</a>
                </li>

            @elseif(auth()->user()->isMaintenance())
                <li class="nav-item {{ request()->routeIs('maintenance.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('maintenance.dashboard') }}"><i class="fas fa-chart-pie"></i> Dashboard</a>
                </li>
                <li class="nav-item {{ (request()->routeIs('maintenance.tasks.index') || request()->routeIs('maintenance.tasks.show')) && request()->get('from') !== 'history' ? 'active' : '' }}">
                    <a href="{{ route('maintenance.tasks.index') }}"><i class="fas fa-screwdriver-wrench"></i> Assigned Maintenance Tasks</a>
                </li>
                <li class="nav-item {{ request()->routeIs('maintenance.tasks.completed') || request()->get('from') === 'history' ? 'active' : '' }}">
                    <a href="{{ route('maintenance.tasks.completed') }}"><i class="fas fa-clock-rotate-left"></i> Tasks History</a>
                </li>

                <li class="nav-divider"></li>

                <li class="nav-item {{ request()->routeIs('settings.*') || request()->routeIs('profile.*') || request()->routeIs('password.*') ? 'active' : '' }}">
                    <a href="{{ route('settings.index') }}"><i class="fas fa-gear"></i> Settings</a>
                </li>
            @endif

            <li class="nav-divider"></li>

            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout"><i class="fas fa-arrow-right-from-bracket"></i> Logout</button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- ─── Main Content ──────────────────────────────────────────────────── -->
    <div class="main-content flex-grow-1">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="btn btn-link text-dark sidebar-toggle-btn p-1">
                    <i class="fas fa-bars fa-lg"></i>
                </button>
                <div class="navbar-brand-text">
                    <span class="brand-title">SCC</span>
                    <span class="brand-sub">ReportHub</span>
                </div>
            </div>
            <div class="navbar-right d-flex align-items-center gap-3">
                <!-- Dark Mode Toggle -->
                <button class="dark-mode-toggle" id="darkModeToggle" title="Toggle dark mode">
                    <i class="fas fa-moon" id="darkModeIcon"></i>
                </button>
                <!-- Notification Bell -->
                <div class="dropdown">
                    <button class="btn btn-link text-dark position-relative p-2" data-bs-toggle="dropdown" aria-label="Notifications">
                        <i class="fas fa-bell fa-lg"></i>
                        @if(($unreadNotificationCount ?? 0) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notif-badge" style="font-size:0.65rem; min-width:18px;">
                                {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0 shadow-lg border-0" id="notif-dropdown" style="width:340px; border-radius:12px; overflow:hidden;">
                        {{-- Header --}}
                        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom" style="background:#f8fafc;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-bell text-primary" style="font-size:0.85rem;"></i>
                                <span class="fw-semibold" style="font-size:0.9rem;">Notifications</span>
                                @if(($unreadNotificationCount ?? 0) > 0)
                                <span class="badge bg-primary rounded-pill" style="font-size:0.7rem;">{{ $unreadNotificationCount }}</span>
                                @endif
                            </div>
                            <form id="mark-all-form" method="POST" action="{{ route('notifications.mark-all-read') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link text-muted p-0" style="font-size:0.75rem; text-decoration:none;">
                                    <i class="fas fa-check-double me-1"></i>Mark all read
                                </button>
                            </form>
                        </div>
                        {{-- List --}}
                        <div id="notification-list" style="max-height:360px; overflow-y:auto;">
                            <div class="text-center py-4 text-muted small">
                                <i class="fas fa-spinner fa-spin me-1"></i> Loading...
                            </div>
                        </div>
                        {{-- Footer --}}
                        <div class="text-center border-top py-2" style="background:#f8fafc;">
                            <a href="{{ route('notifications.index') }}" class="text-primary fw-semibold" style="font-size:0.8rem; text-decoration:none;">
                                View all notifications <i class="fas fa-arrow-right ms-1 fa-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Menu -->
                <div class="dropdown">
                    <button class="btn btn-link text-dark d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                        <img src="{{ auth()->user()->profile_photo_url }}" alt="Avatar" class="rounded-circle" width="32" height="32" style="object-fit:cover;" onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->full_name) }}&background=0d6efd&color=fff&size=32'">
                        <span class="d-none d-md-inline">{{ auth()->user()->first_name }}</span>
                        <i class="fas fa-chevron-down fa-xs"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i>My Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('password.change') }}"><i class="fas fa-key me-2"></i>Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="page-content">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<!-- App JS -->
<script src="{{ asset('js/app.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const bellBtn = document.querySelector('[data-bs-toggle="dropdown"]');
    if (bellBtn) {
        bellBtn.addEventListener('show.bs.dropdown', loadNotifications);
    }
});

// Icon map per notification type
const notifIcons = {
    new_ticket:       { icon: 'fa-ticket-alt',      bg: '#eef2ff', color: '#4f46e5' },
    ticket_approved:  { icon: 'fa-check-circle',    bg: '#f0fdf4', color: '#16a34a' },
    ticket_rejected:  { icon: 'fa-times-circle',    bg: '#fef2f2', color: '#dc2626' },
    ticket_assigned:  { icon: 'fa-user-plus',       bg: '#eff6ff', color: '#2563eb' },
    task_assigned:    { icon: 'fa-hard-hat',         bg: '#fffbeb', color: '#d97706' },
    ticket_completed: { icon: 'fa-star',             bg: '#fefce8', color: '#ca8a04' },
    ticket_resolved:  { icon: 'fa-check-double',    bg: '#f0fdf4', color: '#16a34a' },
};

function getNotifMeta(type) {
    return notifIcons[type] || { icon: 'fa-bell', bg: '#f1f5f9', color: '#64748b' };
}

// Truncate message to fit nicely
function truncate(str, len = 60) {
    return str.length > len ? str.slice(0, len) + '…' : str;
}

function loadNotifications() {
    fetch('{{ route("notifications.recent") }}')
        .then(r => r.json())
        .then(data => {
            const list = document.getElementById('notification-list');
            if (!data.length) {
                list.innerHTML = `
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-bell-slash fa-2x mb-2 d-block opacity-25"></i>
                        <span style="font-size:0.82rem;">No notifications yet</span>
                    </div>`;
                return;
            }

            list.innerHTML = data.map(n => {
                const meta = getNotifMeta(n.type);
                return `
                <a href="${n.url}"
                   class="notif-item d-flex align-items-start gap-3 px-3 py-2 border-bottom text-decoration-none"
                   data-id="${n.id}"
                   data-read="${n.is_read}"
                   style="background:${n.is_read ? '#fff' : '#f5f7ff'}; transition:background 0.15s;">
                    <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-circle"
                         style="width:36px;height:36px;background:${meta.bg};margin-top:2px;">
                        <i class="fas ${meta.icon}" style="color:${meta.color};font-size:0.85rem;"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="${n.is_read ? 'text-muted' : 'fw-semibold text-dark'}"
                             style="font-size:0.8rem; line-height:1.4; white-space:normal; word-break:break-word;">
                            ${truncate(n.message, 75)}
                        </div>
                        <div class="text-muted mt-1" style="font-size:0.72rem;">${n.created_at}</div>
                    </div>
                    ${!n.is_read ? '<span class="flex-shrink-0 mt-2" style="width:8px;height:8px;border-radius:50%;background:#4f46e5;display:inline-block;"></span>' : ''}
                </a>`;
            }).join('');

            // Mark as read on click
            document.querySelectorAll('.notif-item').forEach(item => {
                item.addEventListener('click', function (e) {
                    const id   = this.dataset.id;
                    const read = this.dataset.read === 'true';
                    if (!read) {
                        e.preventDefault();
                        const url = this.href;
                        fetch(`/notifications/${id}/read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        }).finally(() => {
                            updateNotifBadge();
                            window.location.href = url;
                        });
                    }
                });
            });
        });
}

function updateNotifBadge() {
    fetch('{{ route("notifications.unread-count") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        let badge = document.getElementById('notif-badge');
        const count = data.count;
        if (count > 0) {
            if (!badge) {
                badge = document.createElement('span');
                badge.id = 'notif-badge';
                badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                badge.style.cssText = 'font-size:0.65rem;min-width:18px;';
                document.querySelector('.fa-bell')?.closest('button')?.appendChild(badge);
            }
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = '';
        } else {
            if (badge) badge.style.display = 'none';
        }
    })
    .catch(() => {});
}

// Poll every 30 seconds
setInterval(updateNotifBadge, 30000);
</script>

@stack('scripts')
</body>
</html>

