<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceLog;
use App\Models\Notification;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    // ─── View Assigned Tasks ──────────────────────────────────────────────────

    public function assignedTasks(Request $request)
    {
        $user = Auth::user();

        $query = Ticket::where('assigned_to', $user->id)
            ->whereIn('status', ['assigned', 'ongoing'])
            ->with(['user', 'facility']);

        // Auto-filter by specialization unless "show_all" is requested
        $specializationCategories = $user->specialization_categories;
        $isFiltered = !empty($specializationCategories) && !$request->boolean('show_all');

        if ($isFiltered) {
            $query->whereIn('issue_category', $specializationCategories);
        }

        if ($request->filled('priority')) {
            $query->where('priority_level', $request->priority);
        }

        $tickets = $query->orderByRaw("FIELD(priority_level, 'urgent', 'high', 'normal')")->paginate(10);

        return view('maintenance.tasks.index', compact('tickets', 'isFiltered', 'specializationCategories'));
    }

    // ─── View Task Detail ─────────────────────────────────────────────────────

    public function showTask(Ticket $ticket)
    {
        // Maintenance staff can only view their assigned tickets
        if ($ticket->assigned_to !== Auth::id()) {
            abort(403, 'This task is not assigned to you.');
        }

        $ticket->load(['user', 'facility', 'maintenanceLogs.maintenanceStaff']);

        return view('maintenance.tasks.show', compact('ticket'));
    }

    // ─── Start Task (Ongoing) ─────────────────────────────────────────────────

    public function startTask(Ticket $ticket)
    {
        if ($ticket->assigned_to !== Auth::id()) {
            abort(403);
        }

        if ($ticket->status !== Ticket::STATUS_ASSIGNED) {
            return back()->with('error', 'Task cannot be started at this stage.');
        }

        $ticket->update(['status' => Ticket::STATUS_ONGOING]);

        MaintenanceLog::create([
            'ticket_id'            => $ticket->id,
            'maintenance_staff_id' => Auth::id(),
            'action_taken'         => 'Task started — repair work in progress.',
            'status'               => 'ongoing',
        ]);

        // Notify requester
        Notification::create([
            'user_id'   => $ticket->user_id,
            'ticket_id' => $ticket->id,
            'message'   => "Repair work has started on your ticket #{$ticket->ticket_number}.",
            'type'      => 'task_started',
            'is_read'   => false,
        ]);

        return back()->with('success', 'Task marked as ongoing.');
    }

    // ─── Update Repair Details ────────────────────────────────────────────────

    public function updateRepair(Request $request, Ticket $ticket)
    {
        if ($ticket->assigned_to !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'action_taken'  => ['required', 'string', 'min:5'],
            'repair_notes'  => ['nullable', 'string'],
            'repair_cost'   => ['nullable', 'numeric', 'min:0'],
            'materials_used'=> ['nullable', 'string'],
        ]);

        MaintenanceLog::create([
            'ticket_id'            => $ticket->id,
            'maintenance_staff_id' => Auth::id(),
            'action_taken'         => $request->action_taken,
            'repair_notes'         => $request->repair_notes,
            'repair_cost'          => $request->repair_cost ?? 0,
            'materials_used'       => $request->materials_used,
            'status'               => 'ongoing',
        ]);

        return back()->with('success', 'Repair update logged successfully.');
    }

    // ─── Resolve Task ─────────────────────────────────────────────────────────

    public function resolveTask(Request $request, Ticket $ticket)
    {
        if ($ticket->assigned_to !== Auth::id()) {
            abort(403);
        }

        if (!in_array($ticket->status, [Ticket::STATUS_ASSIGNED, Ticket::STATUS_ONGOING])) {
            return back()->with('error', 'Task cannot be resolved at this stage.');
        }

        $request->validate([
            'action_taken'  => ['required', 'string', 'min:5'],
            'repair_notes'  => ['required', 'string', 'min:5'],
            'repair_cost'   => ['required', 'numeric', 'min:0'],
            'materials_used'=> ['nullable', 'string'],
        ]);

        MaintenanceLog::create([
            'ticket_id'            => $ticket->id,
            'maintenance_staff_id' => Auth::id(),
            'action_taken'         => $request->action_taken,
            'repair_notes'         => $request->repair_notes,
            'repair_cost'          => $request->repair_cost,
            'materials_used'       => $request->materials_used,
            'status'               => 'resolved',
        ]);

        $ticket->update(['status' => Ticket::STATUS_RESOLVED]);

        // Notify admin
        $admins = \App\Models\User::whereHas('role', fn($q) => $q->where('slug', 'admin'))->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id'   => $admin->id,
                'ticket_id' => $ticket->id,
                'message'   => "Ticket #{$ticket->ticket_number} has been resolved by {$ticket->assignedStaff->full_name}. Awaiting your verification.",
                'type'      => 'task_resolved',
                'is_read'   => false,
            ]);
        }

        // Notify requester
        Notification::create([
            'user_id'   => $ticket->user_id,
            'ticket_id' => $ticket->id,
            'message'   => "The repair for your ticket #{$ticket->ticket_number} has been completed. Awaiting admin verification.",
            'type'      => 'task_resolved',
            'is_read'   => false,
        ]);

        return redirect()->route('maintenance.tasks.index')
            ->with('success', "Task #{$ticket->ticket_number} marked as resolved. Awaiting admin verification.");
    }

    // ─── Completed Task History ───────────────────────────────────────────────

    public function completedTasks(Request $request)
    {
        $query = Ticket::where('assigned_to', Auth::id())
            ->with(['user', 'facility', 'maintenanceLogs'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['resolved', 'completed']);
        }

        $tickets = $query->paginate(10);

        return view('maintenance.tasks.completed', compact('tickets'));
    }
}
