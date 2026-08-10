<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    // ─── Faculty: Submit Feedback Form ───────────────────────────────────────

    public function create(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        if ($ticket->status !== Ticket::STATUS_COMPLETED) {
            return back()->with('error', 'Feedback can only be submitted for completed tickets.');
        }

        if ($ticket->feedback) {
            return redirect()->route('faculty.tickets.show', $ticket)
                ->with('info', 'You have already submitted feedback for this ticket.');
        }

        return view('faculty.feedback.create', compact('ticket'));
    }

    // ─── Faculty: Store Feedback ──────────────────────────────────────────────

    public function store(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        if ($ticket->status !== Ticket::STATUS_COMPLETED) {
            return back()->with('error', 'Feedback can only be submitted for completed tickets.');
        }

        if ($ticket->feedback) {
            return redirect()->route('faculty.tickets.show', $ticket)
                ->with('info', 'You have already submitted feedback for this ticket.');
        }

        $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Feedback::create([
            'ticket_id' => $ticket->id,
            'user_id'   => Auth::id(),
            'rating'    => $request->rating,
            'comment'   => $request->comment,
        ]);

        return redirect()->route('faculty.tickets.show', $ticket)
            ->with('success', 'Thank you for your feedback!');
    }

    // ─── Admin: View All Feedback ─────────────────────────────────────────────

    public function adminIndex(Request $request)
    {
        $query = Feedback::with(['ticket', 'user'])->latest();

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('ticket', fn($q) => $q->where('ticket_number', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%"))
                ->orWhereHas('user', fn($q) => $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%"));
        }

        $feedbacks   = $query->paginate(15)->withQueryString();
        $avgRating   = Feedback::avg('rating');
        $totalCount  = Feedback::count();
        $ratingCounts = Feedback::selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->pluck('count', 'rating');

        return view('admin.feedback.index', compact('feedbacks', 'avgRating', 'totalCount', 'ratingCounts'));
    }
}
