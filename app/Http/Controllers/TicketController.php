<?php

namespace App\Http\Controllers;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use App\Models\Facility;
use App\Models\Notification;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    // ─── Faculty: History (Completed Tickets) ────────────────────────────────

    public function history()
    {
        $tickets = Ticket::where('user_id', Auth::id())
            ->whereIn('status', ['completed', 'rejected'])
            ->with(['facility', 'assignedStaff', 'feedback'])
            ->latest()
            ->paginate(10);

        return view('faculty.history', compact('tickets'));
    }

    // ─── Faculty: List My Tickets ─────────────────────────────────────────────

    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())
            ->whereNotIn('status', ['completed', 'rejected'])
            ->with(['facility', 'assignedStaff'])
            ->latest()
            ->paginate(10);

        return view('faculty.tickets.index', compact('tickets'));
    }

    // ─── Faculty: Show Create Form ────────────────────────────────────────────

    public function create()
    {
        $facilities = Facility::orderBy('building_name')->get();
        return view('faculty.tickets.create', compact('facilities'));
    }

    // ─── Faculty: Store New Ticket ────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['required', 'string', 'min:10'],
            'issue_category' => ['required', 'in:' . implode(',', array_keys(Ticket::CATEGORIES))],
            'location_id'    => ['nullable', 'exists:facilities,id'],
            'photo'          => ['nullable', 'image', 'max:10240'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $cloudinary = new Cloudinary(Configuration::instance(env('CLOUDINARY_URL')));
            $result = $cloudinary->uploadApi()->upload($request->file('photo')->getRealPath(), ['folder' => 'scc-reporthub/ticket_photos']);
            $photoPath = $result['secure_url'];
        }

        // ── Auto-detect priority based on same category + same location ───────
        $priority = $this->detectPriority($request->location_id, $request->issue_category);

        $ticket = Ticket::create([
            'ticket_number'  => $this->generateTicketNumber(),
            'user_id'        => Auth::id(),
            'issue_category' => $request->issue_category,
            'location_id'    => $request->location_id,
            'title'          => $request->title,
            'description'    => $request->description,
            'priority_level' => $priority,
            'photo_path'     => $photoPath,
            'status'         => Ticket::STATUS_PENDING,
        ]);

        // Re-escalate all existing similar active tickets
        $this->escalateSimilarTickets($request->location_id, $request->issue_category, $ticket->id);

        // Notify admins
        $this->notifyAdmins($ticket);

        return redirect()->route('faculty.tickets.show', $ticket)
            ->with('success', "Ticket #{$ticket->ticket_number} submitted successfully. Awaiting admin review.");
    }

    // ─── Helper: Detect priority based on same category + location ───────────

    private function detectPriority(?int $locationId, string $category): string
    {
        $similarCount = $this->countSimilarTickets($locationId, $category);
        // +1 for the current ticket being submitted
        $total = $similarCount + 1;

        if ($total >= 3) return Ticket::PRIORITY_URGENT;
        if ($total >= 2) return Ticket::PRIORITY_HIGH;
        return Ticket::PRIORITY_NORMAL;
    }

    // ─── Helper: Count similar active tickets (same category + location) ─────

    private function countSimilarTickets(?int $locationId, string $category, ?int $excludeId = null): int
    {
        $query = Ticket::whereNotIn('status', ['completed', 'rejected'])
            ->where('issue_category', $category);

        if ($locationId) {
            $query->where('location_id', $locationId);
        }

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->count();
    }

    // ─── Helper: Escalate existing similar tickets ────────────────────────────

    private function escalateSimilarTickets(?int $locationId, string $category, int $newTicketId): void
    {
        $total = $this->countSimilarTickets($locationId, $category, $newTicketId) + 1;

        if ($total < 2) return;

        $newPriority = $total >= 3 ? Ticket::PRIORITY_URGENT : Ticket::PRIORITY_HIGH;
        $priorityOrder = ['normal' => 1, 'high' => 2, 'urgent' => 3];

        Ticket::whereNotIn('status', ['completed', 'rejected'])
            ->where('id', '!=', $newTicketId)
            ->where('issue_category', $category)
            ->when($locationId, fn($q) => $q->where('location_id', $locationId))
            ->get()
            ->each(function ($t) use ($newPriority, $priorityOrder) {
                if (($priorityOrder[$t->priority_level] ?? 1) < $priorityOrder[$newPriority]) {
                    $t->update(['priority_level' => $newPriority]);
                }
            });
    }

    // ─── Faculty: Show Ticket Details ─────────────────────────────────────────

    public function show(Ticket $ticket)
    {
        // Faculty can only view their own tickets
        if (Auth::user()->isFaculty() && $ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->load(['user', 'facility', 'assignedStaff', 'maintenanceLogs.maintenanceStaff', 'feedback']);

        return view('faculty.tickets.show', compact('ticket'));
    }

    // ─── Faculty: Track Ticket ────────────────────────────────────────────────

    public function track(Request $request)
    {
        $ticket = null;

        if ($request->filled('ticket_number')) {
            $ticket = Ticket::where('ticket_number', $request->ticket_number)
                ->where('user_id', Auth::id())
                ->with(['facility', 'assignedStaff', 'maintenanceLogs'])
                ->first();

            if (!$ticket) {
                return back()->withErrors(['ticket_number' => 'Ticket not found or does not belong to your account.'])
                             ->withInput();
            }
        }

        return view('faculty.tickets.track', compact('ticket'));
    }

    // ─── Helper: Generate Unique Ticket Number ────────────────────────────────

    private function generateTicketNumber(): string
    {
        do {
            $number = 'TKT-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        } while (Ticket::where('ticket_number', $number)->exists());

        return $number;
    }

    // ─── Helper: Notify Admins ────────────────────────────────────────────────

    private function notifyAdmins(Ticket $ticket): void
    {
        $admins = \App\Models\User::whereHas('role', fn($q) => $q->where('slug', 'admin'))->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id'   => $admin->id,
                'ticket_id' => $ticket->id,
                'message'   => "New ticket #{$ticket->ticket_number} submitted by {$ticket->user->full_name} — Priority: " . ucfirst($ticket->priority_level),
                'type'      => 'new_ticket',
                'is_read'   => false,
            ]);
        }
    }
}
