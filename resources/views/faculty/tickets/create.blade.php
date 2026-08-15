@extends('layouts.app')
@section('title', 'Create Ticket Request')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="fas fa-circle-plus me-2 text-primary"></i>Create Ticket Request</h4>
        <p class="text-muted small mb-0">Input location, describe the issue, upload photo evidence, then submit</p>
    </div>
    <a href="{{ route('faculty.tickets.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">

        <div class="card shadow-sm">
            <div class="card-body p-3 p-md-4">
                <form method="POST" action="{{ route('faculty.tickets.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Issue Category <span class="text-danger">*</span></label>
                        <select name="issue_category" class="form-select @error('issue_category') is-invalid @enderror" required>
                            <option value="">-- Select a category --</option>
                            @foreach(\App\Models\Ticket::CATEGORIES as $value => $label)
                            <option value="{{ $value }}" {{ old('issue_category') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        @error('issue_category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Facility Location</label>
                        <select name="location_id" class="form-select @error('location_id') is-invalid @enderror">
                            <option value="">-- Select a facility --</option>
                            @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}" {{ old('location_id') == $facility->id ? 'selected' : '' }}>
                                {{ $facility->full_location }}
                            </option>
                            @endforeach
                        </select>
                        @error('location_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Request Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="e.g. Broken ceiling fan in Room 201" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Contact Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone text-muted"></i></span>
                            <input type="text" name="contact_number"
                                   class="form-control @error('contact_number') is-invalid @enderror"
                                   value="{{ old('contact_number') }}"
                                   placeholder="09XXXXXXXXX" required>
                        </div>
                        @error('contact_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        <div class="form-text">For admin or maintenance to reach you for clarifications.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Detailed Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="4" placeholder="Describe the issue in detail..." required>{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Photo Evidence <span class="text-muted fw-normal small">(optional, max 10MB)</span></label>

                        {{-- Custom file upload area --}}
                        <div id="uploadArea" class="upload-area border rounded p-3 text-center" style="cursor:pointer; border-style:dashed !important; border-color:#6c757d; background:#f8f9fa;" onclick="document.getElementById('photoInput').click()">
                            <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                            <p class="mb-0 text-muted small">Click to browse or drag & drop a photo here</p>
                            <p class="mb-0 text-muted" style="font-size:0.72rem;">Any image format (JPG, PNG, WEBP, GIF, etc.) — max 10MB</p>
                        </div>
                        <input type="file" name="photo" id="photoInput" class="d-none @error('photo') is-invalid @enderror"
                               accept="image/*" onchange="previewPhoto(this)">
                        @error('photo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                        {{-- Portrait preview --}}
                        <div id="photoPreview" class="mt-3 d-none">
                            <div class="d-flex justify-content-center">
                                <div class="position-relative" style="width:180px;">
                                    <img id="previewImg" src="" alt="Preview"
                                         class="rounded shadow-sm w-100"
                                         style="height:240px; object-fit:cover; object-position:center; display:block;">
                                    <button type="button"
                                            class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle p-0"
                                            style="width:24px;height:24px;line-height:1;"
                                            onclick="clearPhoto()" title="Remove photo">
                                        <i class="fas fa-times" style="font-size:0.65rem;"></i>
                                    </button>
                                </div>
                            </div>
                            <p id="photoFileName" class="text-center text-muted mt-1 mb-0" style="font-size:0.75rem;"></p>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Submit Request
                        </button>
                        <a href="{{ route('faculty.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('photoFileName').textContent = file.name;
            document.getElementById('photoPreview').classList.remove('d-none');
            document.getElementById('uploadArea').classList.add('d-none');
        };
        reader.readAsDataURL(file);
    }
}

function clearPhoto() {
    document.getElementById('photoInput').value = '';
    document.getElementById('photoPreview').classList.add('d-none');
    document.getElementById('uploadArea').classList.remove('d-none');
}

// Drag & drop support
document.addEventListener('DOMContentLoaded', function () {
    const area = document.getElementById('uploadArea');
    const input = document.getElementById('photoInput');

    area.addEventListener('dragover', e => {
        e.preventDefault();
        area.style.borderColor = '#0d6efd';
        area.style.background = '#e8f0fe';
    });
    area.addEventListener('dragleave', () => {
        area.style.borderColor = '#6c757d';
        area.style.background = '#f8f9fa';
    });
    area.addEventListener('drop', e => {
        e.preventDefault();
        area.style.borderColor = '#6c757d';
        area.style.background = '#f8f9fa';
        if (e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            previewPhoto(input);
        }
    });
});
</script>
@endpush
