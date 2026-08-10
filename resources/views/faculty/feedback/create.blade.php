@extends('layouts.app')
@section('title', 'Submit Feedback')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="fas fa-star me-2 text-warning"></i>Submit Feedback</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('faculty.tickets.show', $ticket) }}">Ticket #{{ $ticket->ticket_number }}</a></li>
                <li class="breadcrumb-item active">Submit Feedback</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('faculty.tickets.show', $ticket) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">

        {{-- Ticket Summary Card --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body p-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;">
                        <i class="fas fa-check text-success"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $ticket->title }}</div>
                        <div class="text-muted small">Ticket #{{ $ticket->ticket_number }} &bull; Completed {{ $ticket->completed_at?->format('F d, Y') }}</div>
                        @if($ticket->assignedStaff)
                        <div class="text-muted small">Handled by: <span class="fw-semibold">{{ $ticket->assignedStaff->full_name }}</span></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Feedback Form --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0">Rate the Maintenance Service</h6>
                <div class="text-muted small">Your feedback helps us improve the quality of service.</div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('faculty.feedback.store', $ticket) }}" method="POST">
                    @csrf

                    {{-- Star Rating --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Overall Rating <span class="text-danger">*</span></label>
                        <div class="star-rating d-flex gap-2 mb-1" id="starRating">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star fa-2x star-icon text-muted" data-value="{{ $i }}" style="cursor:pointer; transition: color 0.15s;"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating') }}">
                        <div class="small text-muted" id="ratingLabel">Click a star to rate</div>
                        @error('rating')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Comment --}}
                    <div class="mb-4">
                        <label for="comment" class="form-label fw-semibold">Additional Comments <span class="text-muted fw-normal">(optional)</span></label>
                        <textarea name="comment" id="comment" rows="4"
                            class="form-control @error('comment') is-invalid @enderror"
                            placeholder="Share your experience with the maintenance service..."
                            maxlength="1000">{{ old('comment') }}</textarea>
                        <div class="d-flex justify-content-end mt-1">
                            <span class="text-muted small" id="charCount">0 / 1000</span>
                        </div>
                        @error('comment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning fw-semibold py-2" id="submitBtn" disabled>
                            <i class="fas fa-paper-plane me-2"></i>Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
.star-icon:hover,
.star-icon.hovered,
.star-icon.selected {
    color: #f59e0b !important;
}
</style>
@endpush

@push('scripts')
<script>
const stars      = document.querySelectorAll('.star-icon');
const ratingInput = document.getElementById('ratingInput');
const ratingLabel = document.getElementById('ratingLabel');
const submitBtn   = document.getElementById('submitBtn');
const comment     = document.getElementById('comment');
const charCount   = document.getElementById('charCount');

const labels = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];

// Restore old value if any
let selected = parseInt(ratingInput.value) || 0;
if (selected) highlightStars(selected);

stars.forEach(star => {
    star.addEventListener('mouseenter', () => highlightStars(parseInt(star.dataset.value)));
    star.addEventListener('mouseleave', () => highlightStars(selected));
    star.addEventListener('click', () => {
        selected = parseInt(star.dataset.value);
        ratingInput.value = selected;
        ratingLabel.textContent = labels[selected];
        ratingLabel.className = 'small fw-semibold text-warning';
        submitBtn.disabled = false;
        highlightStars(selected);
    });
});

function highlightStars(value) {
    stars.forEach((s, i) => {
        s.classList.toggle('selected', i < value);
        s.style.color = i < value ? '#f59e0b' : '#9ca3af';
    });
}

comment.addEventListener('input', () => {
    charCount.textContent = comment.value.length + ' / 1000';
});
</script>
@endpush
