<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceLog;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // ─── Admin Dashboard ──────────────────────────────────────────────────────

    public function adminDashboard()
    {
        $stats = [
            'total_tickets'     => Ticket::whereNotIn('status', ['completed', 'rejected'])->count(),
            'pending_tickets'   => Ticket::where('status', 'pending')->count(),
            'ongoing_tickets'   => Ticket::whereIn('status', ['assigned', 'ongoing'])->count(),
            'resolved_tickets'  => Ticket::where('status', 'resolved')->count(),
            'completed_tickets' => Ticket::where('status', 'completed')->count(),
            'total_users'       => User::whereHas('role', fn($q) => $q->where('slug', 'faculty'))->count(),
            'maintenance_staff' => User::whereHas('role', fn($q) => $q->where('slug', 'maintenance'))->count(),
            'urgent_tickets'    => Ticket::where('priority_level', 'urgent')->whereNotIn('status', ['completed', 'rejected'])->count(),
            'rejected_tickets'  => Ticket::where('status', 'rejected')->count(),
        ];

        // Recent tickets for the table
        $recentTickets = Ticket::with(['user', 'facility', 'assignedStaff'])
            ->latest()
            ->take(10)
            ->get();

        // Monthly ticket data for chart (last 6 months)
        $monthlyData = $this->getMonthlyTicketData();

        // Priority distribution for pie chart
        $priorityData = [
            'urgent' => Ticket::where('priority_level', 'urgent')->count(),
            'high'   => Ticket::where('priority_level', 'high')->count(),
            'normal' => Ticket::where('priority_level', 'normal')->count(),
        ];

        // Status distribution
        $statusData = [
            'pending'   => Ticket::where('status', 'pending')->count(),
            'approved'  => Ticket::where('status', 'approved')->count(),
            'assigned'  => Ticket::where('status', 'assigned')->count(),
            'ongoing'   => Ticket::where('status', 'ongoing')->count(),
            'resolved'  => Ticket::where('status', 'resolved')->count(),
            'completed' => Ticket::where('status', 'completed')->count(),
            'rejected'  => Ticket::where('status', 'rejected')->count(),
        ];

        return view('admin.dashboard', compact('stats', 'recentTickets', 'monthlyData', 'priorityData', 'statusData'));
    }

    // ─── Faculty Dashboard ────────────────────────────────────────────────────

    public function facultyDashboard()
    {
        $user = Auth::user();

        $stats = [
            'total_tickets'     => Ticket::where('user_id', $user->id)->whereNotIn('status', ['completed', 'rejected'])->count(),
            'pending_tickets'   => Ticket::where('user_id', $user->id)->where('status', 'pending')->count(),
            'ongoing_tickets'   => Ticket::where('user_id', $user->id)->whereIn('status', ['assigned', 'ongoing'])->count(),
            'completed_tickets' => Ticket::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];

        $recentTickets = Ticket::where('user_id', $user->id)
            ->with(['facility', 'assignedStaff'])
            ->latest()
            ->take(5)
            ->get();

        $unreadNotifications = $user->notifications()->unread()->latest()->take(5)->get();

        return view('faculty.dashboard', compact('stats', 'recentTickets', 'unreadNotifications'));
    }

    // ─── Maintenance Dashboard ────────────────────────────────────────────────

    public function maintenanceDashboard()
    {
        $user = Auth::user();
        $specializationCategories = $user->specialization_categories;

        $stats = [
            'assigned_tasks'   => Ticket::where('assigned_to', $user->id)->whereIn('status', ['assigned', 'ongoing'])->count(),
            'completed_tasks'  => Ticket::where('assigned_to', $user->id)->where('status', 'completed')->count(),
            'resolved_tasks'   => Ticket::where('assigned_to', $user->id)->where('status', 'resolved')->count(),
            'urgent_tasks'     => Ticket::where('assigned_to', $user->id)->where('priority_level', 'urgent')->whereIn('status', ['assigned', 'ongoing'])->count(),
        ];

        $ticketQuery = Ticket::where('assigned_to', $user->id)
            ->whereIn('status', ['assigned', 'ongoing'])
            ->with(['user', 'facility'])
            ->orderByRaw("FIELD(priority_level, 'urgent', 'high', 'normal')")
            ->latest();

        // Auto-filter by specialization on dashboard
        if (!empty($specializationCategories)) {
            $ticketQuery->whereIn('issue_category', $specializationCategories);
        }

        $assignedTickets = $ticketQuery->get();

        $recentCompleted = Ticket::where('assigned_to', $user->id)
            ->where('status', 'completed')
            ->with(['user', 'facility'])
            ->latest()
            ->take(5)
            ->get();

        return view('maintenance.dashboard', compact('stats', 'assignedTickets', 'recentCompleted', 'specializationCategories'));
    }

    // ─── Helper: Monthly Ticket Data ──────────────────────────────────────────

    private function getMonthlyTicketData(): array
    {
        $months = [];
        $counts = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $counts[] = Ticket::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return ['labels' => $months, 'data' => $counts];
    }
}
