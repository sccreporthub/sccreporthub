<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Notification;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // ─── All Tickets ──────────────────────────────────────────────────────────

    public function tickets(Request $request)
    {
        // Default: pending and approved only
        // If 'all' is passed, show all statuses
        $query = Ticket::with(['user', 'facility', 'assignedStaff']);

        if ($request->filled('all')) {
            // Active tickets only — matches the dashboard Active Tickets count
            $query->whereNotIn('status', ['completed', 'rejected']);
        } elseif ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default view: pending and approved
            $query->whereIn('status', ['pending', 'approved']);
        }

        if ($request->filled('priority')) {
            $query->where('priority_level', $request->priority);
        }
        if ($request->filled('category')) {
            $query->where('issue_category', $request->category);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%"));
            });
        }

        $tickets = $query->latest()->paginate(15);

        $maintenanceStaff = User::whereHas('role', fn($q) => $q->where('slug', 'maintenance'))
            ->where('status', 'active')
            ->get();

        return view('admin.tickets.index', compact('tickets', 'maintenanceStaff'));
    }

    // ─── Show Ticket Detail ───────────────────────────────────────────────────

    public function showTicket(Ticket $ticket)
    {
        $ticket->load(['user', 'facility', 'assignedStaff', 'maintenanceLogs.maintenanceStaff', 'approvedBy']);

        $maintenanceStaff = User::whereHas('role', fn($q) => $q->where('slug', 'maintenance'))
            ->where('status', 'active')
            ->get();

        return view('admin.tickets.show', compact('ticket', 'maintenanceStaff'));
    }

    // ─── Update Priority ──────────────────────────────────────────────────────

    public function updatePriority(Request $request, Ticket $ticket)
    {
        $request->validate([
            'priority_level' => ['required', 'in:normal,high,urgent'],
        ]);

        $old = $ticket->priority_level;
        $new = $request->priority_level;

        if ($old === $new) {
            return back()->with('info', 'Priority is already set to ' . ucfirst($new) . '.');
        }

        $ticket->update(['priority_level' => $new]);

        return back()->with('success', "Priority updated from " . ucfirst($old) . " to " . ucfirst($new) . ".");
    }

    public function approveTicket(Request $request, Ticket $ticket)
    {
        if ($ticket->status !== Ticket::STATUS_PENDING) {
            return back()->with('error', 'Only pending tickets can be approved.');
        }

        $ticket->update([
            'status'      => Ticket::STATUS_APPROVED,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Notify the requester
        Notification::create([
            'user_id'   => $ticket->user_id,
            'ticket_id' => $ticket->id,
            'message'   => "Your ticket #{$ticket->ticket_number} has been approved and is awaiting assignment.",
            'type'      => 'ticket_approved',
            'is_read'   => false,
        ]);

        return back()->with('success', "Ticket #{$ticket->ticket_number} approved successfully.");
    }

    // ─── Reject Ticket ────────────────────────────────────────────────────────

    public function rejectTicket(Request $request, Ticket $ticket)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:5'],
        ]);

        if ($ticket->status !== Ticket::STATUS_PENDING) {
            return back()->with('error', 'Only pending tickets can be rejected.');
        }

        $ticket->update([
            'status'      => Ticket::STATUS_REJECTED,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Notify the requester with reason
        Notification::create([
            'user_id'   => $ticket->user_id,
            'ticket_id' => $ticket->id,
            'message'   => "Your ticket #{$ticket->ticket_number} has been rejected. Reason: {$request->rejection_reason}",
            'type'      => 'ticket_rejected',
            'is_read'   => false,
        ]);

        return back()->with('success', "Ticket #{$ticket->ticket_number} rejected.");
    }

    // ─── Assign Ticket ────────────────────────────────────────────────────────

    public function assignTicket(Request $request, Ticket $ticket)
    {
        $request->validate([
            'assigned_to' => ['required', 'exists:users,id'],
        ]);

        $staff = User::findOrFail($request->assigned_to);

        $ticket->update([
            'status'      => Ticket::STATUS_ASSIGNED,
            'assigned_to' => $staff->id,
        ]);

        // Notify maintenance staff
        Notification::create([
            'user_id'   => $staff->id,
            'ticket_id' => $ticket->id,
            'message'   => "You have been assigned to ticket #{$ticket->ticket_number} — {$ticket->title}. Priority: " . ucfirst($ticket->priority_level),
            'type'      => 'task_assigned',
            'is_read'   => false,
        ]);

        // Notify requester
        Notification::create([
            'user_id'   => $ticket->user_id,
            'ticket_id' => $ticket->id,
            'message'   => "Your ticket #{$ticket->ticket_number} has been assigned to {$staff->full_name}.",
            'type'      => 'ticket_assigned',
            'is_read'   => false,
        ]);

        return back()->with('success', "Ticket assigned to {$staff->full_name} successfully.");
    }

    // ─── Verify Completion ────────────────────────────────────────────────────

    public function verifyCompletion(Ticket $ticket)
    {
        if ($ticket->status !== Ticket::STATUS_RESOLVED) {
            return back()->with('error', 'Only resolved tickets can be marked as completed.');
        }

        $ticket->update([
            'status'       => Ticket::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        Notification::create([
            'user_id'   => $ticket->user_id,
            'ticket_id' => $ticket->id,
            'message'   => "Your ticket #{$ticket->ticket_number} has been verified and marked as completed.",
            'type'      => 'ticket_completed',
            'is_read'   => false,
        ]);

        return redirect()->route('admin.monitoring.index')
            ->with('success', "Ticket #{$ticket->ticket_number} marked as completed.");
    }

    // ─── Generate Receipt ─────────────────────────────────────────────────────

    public function generateReceipt(Ticket $ticket)
    {
        $ticket->load(['user', 'facility', 'assignedStaff', 'maintenanceLogs.maintenanceStaff', 'approvedBy']);
        $latestLog = $ticket->maintenanceLogs->last();
        return view('admin.tickets.receipt', compact('ticket', 'latestLog'));
    }

    // ─── Maintenance Monitoring: List ─────────────────────────────────────────

    public function monitoringIndex(Request $request)
    {
        $query = Ticket::with(['user', 'facility', 'assignedStaff'])
            ->whereIn('status', ['assigned', 'ongoing', 'resolved']);

        if ($request->filled('status')) {
            // Support comma-separated statuses e.g. ?status=assigned,ongoing
            $statuses = explode(',', $request->status);
            $query->whereIn('status', $statuses);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%"));
            });
        }

        $tickets = $query->orderByRaw("FIELD(status,'resolved','ongoing','assigned')")
                         ->orderByRaw("FIELD(priority_level,'urgent','high','normal')")
                         ->paginate(15);

        return view('admin.monitoring.index', compact('tickets'));
    }

    // ─── Maintenance Monitoring: Show ─────────────────────────────────────────

    public function monitoringShow(Ticket $ticket)
    {
        $ticket->load(['user', 'facility', 'assignedStaff', 'maintenanceLogs.maintenanceStaff', 'approvedBy']);
        return view('admin.monitoring.show', compact('ticket'));
    }
    // ─── Settings Hub ─────────────────────────────────────────────────────────

    public function settings()
    {
        return view('admin.settings.index');
    }

    // ─── History Logs ─────────────────────────────────────────────────────────

    public function historyLogs(Request $request)
    {
        $query = Ticket::with(['user', 'facility', 'assignedStaff'])
            ->whereIn('status', ['completed', 'rejected']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $tickets = $query->latest()->paginate(15);

        return view('admin.history', compact('tickets'));
    }

    // ─── Facilities Management ────────────────────────────────────────────────

    public function facilities()
    {
        $facilities = Facility::withCount('tickets')->latest()->paginate(15);
        return view('admin.facilities.index', compact('facilities'));
    }

    public function storeFacility(Request $request)
    {
        $request->validate([
            'building_name' => ['required', 'string', 'max:150'],
            'room_number'   => ['nullable', 'string', 'max:50'],
            'floor'         => ['nullable', 'string', 'max:50'],
            'description'   => ['nullable', 'string'],
        ]);

        Facility::create($request->only('building_name', 'room_number', 'floor', 'description'));

        return back()->with('success', 'Facility added successfully.');
    }

    public function updateFacility(Request $request, Facility $facility)
    {
        $request->validate([
            'building_name' => ['required', 'string', 'max:150'],
            'room_number'   => ['nullable', 'string', 'max:50'],
            'floor'         => ['nullable', 'string', 'max:50'],
            'description'   => ['nullable', 'string'],
        ]);

        $facility->update($request->only('building_name', 'room_number', 'floor', 'description'));

        return back()->with('success', 'Facility updated successfully.');
    }

    public function destroyFacility(Facility $facility)
    {
        $facility->delete();
        return back()->with('success', 'Facility deleted.');
    }
}
