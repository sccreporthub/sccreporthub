@extends('layouts.app')
@section('title', 'Settings')

@section('content')
<div class="page-header mb-4">
    <div>
        <h4 class="fw-bold mb-0"><i class="fas fa-gear me-2 text-primary"></i>Settings</h4>
        <p class="text-muted small mb-0">Manage your profile and security settings</p>
    </div>
</div>

<div class="row g-4">
    <!-- Admin Profile -->
    <div class="col-12 col-md-4">
        <a href="{{ route('profile.edit') }}" class="text-decoration-none">
            <div class="card shadow-sm h-100 settings-card">
                <div class="card-body text-center p-4">
                    <div class="settings-icon mb-3" style="background: linear-gradient(135deg, #4f46e5, #818cf8);">
                        <i class="fas fa-circle-user"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Profile</h5>
                    <p class="text-muted small mb-0">Update your name, department, contact number, and profile photo</p>
                </div>
                <div class="card-footer bg-transparent border-top text-center py-2">
                    <span class="text-primary small fw-semibold">Edit Profile <i class="fas fa-arrow-right ms-1 fa-xs"></i></span>
                </div>
            </div>
        </a>
    </div>

    <!-- Security Settings -->
    <div class="col-12 col-md-4">
        <a href="{{ route('password.change') }}" class="text-decoration-none">
            <div class="card shadow-sm h-100 settings-card">
                <div class="card-body text-center p-4">
                    <div class="settings-icon mb-3" style="background: linear-gradient(135deg, #10b981, #34d399);">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Security Settings</h5>
                    <p class="text-muted small mb-0">Change your password to keep your account secure</p>
                </div>
                <div class="card-footer bg-transparent border-top text-center py-2">
                    <span class="text-primary small fw-semibold">Change Password <i class="fas fa-arrow-right ms-1 fa-xs"></i></span>
                </div>
            </div>
        </a>
    </div>

</div>
@endsection

@push('styles')
<style>
.settings-card {
    border-radius: 16px !important;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    cursor: pointer;
}
.settings-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.1) !important;
}
.settings-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 1.6rem;
    color: #fff;
    box-shadow: 0 4px 14px rgba(0,0,0,0.15);
}
</style>
@endpush
