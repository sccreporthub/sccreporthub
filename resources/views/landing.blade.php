<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCC ReportHub – Centralized Facility Maintenance System</title>
    <meta name="description" content="SCC ReportHub is a centralized facility maintenance and issue reporting system for Southern Christian College.">

    <!-- Bootstrap 5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6.5 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ===== BASE ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background: #fff;
            overflow-x: hidden;
        }

        /* ===== GRADIENTS & COLORS ===== */
        .gradient-text {
            background: linear-gradient(135deg, #1e40af, #2563eb, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #1e40af, #2563eb, #7c3aed);
        }
        .gradient-bg-teal {
            background: linear-gradient(135deg, #0d9488, #0891b2);
        }
        .gradient-bg-purple {
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
        }

        /* ===== NAVBAR ===== */
        #mainNav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1050;
            padding: 12px 0;
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,0.3);
            transition: box-shadow 0.3s ease, background 0.3s ease;
        }
        #mainNav.scrolled {
            box-shadow: 0 4px 24px rgba(30,64,175,0.10);
            background: rgba(255,255,255,0.92);
        }
        .nav-brand-wrap { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-brand-logo { width: 34px; height: 34px; object-fit: contain; }
        .nav-brand-text { display: flex; flex-direction: column; line-height: 1.1; }
        .nav-brand-school {
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #94a3b8;
        }
        .nav-brand-name {
            font-size: 15px;
            font-weight: 800;
            background: linear-gradient(135deg, #1e40af, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .nav-link-custom {
            font-size: 14px;
            font-weight: 500;
            color: #475569;
            text-decoration: none;
            padding: 6px 14px;
            border-radius: 8px;
            transition: color 0.2s, background 0.2s;
        }
        .nav-link-custom:hover { color: #2563eb; background: rgba(37,99,235,0.07); }
        .btn-nav-signin {
            font-size: 14px; font-weight: 600;
            padding: 7px 20px;
            border: 2px solid #2563eb;
            color: #2563eb;
            border-radius: 10px;
            background: transparent;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-nav-signin:hover { background: #2563eb; color: #fff; }
        .btn-nav-started {
            font-size: 14px; font-weight: 600;
            padding: 7px 20px;
            border-radius: 10px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            color: #fff;
            text-decoration: none;
            border: none;
            transition: opacity 0.2s, transform 0.2s;
        }
        .btn-nav-started:hover { opacity: 0.88; transform: translateY(-1px); color: #fff; }

        /* Mobile overlay menu */
        .mobile-menu-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 2000;
            background: rgba(15,23,42,0.97);
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 28px;
        }
        .mobile-menu-overlay.open { display: flex; }
        .mobile-menu-overlay a {
            font-size: 22px; font-weight: 700;
            color: #fff; text-decoration: none;
            transition: color 0.2s;
        }
        .mobile-menu-overlay a:hover { color: #93c5fd; }
        .mobile-close-btn {
            position: absolute; top: 24px; right: 24px;
            background: none; border: none;
            color: #fff; font-size: 28px; cursor: pointer;
        }
        .hamburger-btn {
            background: none; border: none;
            font-size: 22px; color: #1e40af; cursor: pointer;
        }

        /* ===== HERO ===== */
        #hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #f0f4ff 0%, #faf5ff 100%);
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
            padding: 120px 0 80px;
        }
        .hero-deco {
            position: absolute; border-radius: 50%;
            pointer-events: none; opacity: 0.18;
        }
        .hero-deco-1 {
            width: 420px; height: 420px;
            background: radial-gradient(circle, #3b82f6, transparent);
            top: -80px; left: -100px;
        }
        .hero-deco-2 {
            width: 320px; height: 320px;
            background: radial-gradient(circle, #7c3aed, transparent);
            bottom: -60px; right: -80px;
        }
        .hero-deco-3 {
            width: 200px; height: 200px;
            background: radial-gradient(circle, #2563eb, transparent);
            top: 40%; left: 60%;
            opacity: 0.10;
        }
        .hero-logo { width: 80px; height: 80px; object-fit: contain; margin-bottom: 20px; }
        .hero-title {
            font-size: clamp(2.8rem, 7vw, 5rem);
            font-weight: 900;
            line-height: 1.05;
            background: linear-gradient(135deg, #1e40af, #2563eb, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 16px;
        }
        .hero-subtitle {
            font-size: clamp(1rem, 2.5vw, 1.2rem);
            font-weight: 600;
            color: #475569;
            max-width: 640px;
            margin: 0 auto 16px;
            line-height: 1.5;
        }
        .hero-desc {
            font-size: 1rem;
            color: #64748b;
            max-width: 520px;
            margin: 0 auto 36px;
            line-height: 1.7;
        }
        .btn-hero-primary {
            padding: 14px 36px;
            font-size: 1rem; font-weight: 700;
            border-radius: 12px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            color: #fff; border: none; text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 20px rgba(37,99,235,0.3);
        }
        .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(37,99,235,0.4); color: #fff; }
        .btn-hero-outline {
            padding: 14px 36px;
            font-size: 1rem; font-weight: 700;
            border-radius: 12px;
            border: 2px solid #2563eb;
            color: #2563eb; background: transparent; text-decoration: none;
            transition: all 0.2s;
        }
        .btn-hero-outline:hover { background: #2563eb; color: #fff; }

        /* ===== FADE-IN ANIMATION ===== */
        .fade-in {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity 0.65s ease, transform 0.65s ease;
        }
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .fade-in-delay-1 { transition-delay: 0.1s; }
        .fade-in-delay-2 { transition-delay: 0.2s; }
        .fade-in-delay-3 { transition-delay: 0.3s; }
        .fade-in-delay-4 { transition-delay: 0.4s; }

        /* ===== SECTION SHARED ===== */
        .section-label {
            display: inline-block;
            font-size: 12px; font-weight: 700;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: #2563eb;
            background: rgba(37,99,235,0.08);
            padding: 5px 14px; border-radius: 20px;
            margin-bottom: 12px;
        }
        .section-title {
            font-size: clamp(1.7rem, 4vw, 2.4rem);
            font-weight: 800; color: #0f172a;
            margin-bottom: 12px;
        }
        .section-desc {
            font-size: 1rem; color: #64748b;
            max-width: 560px; margin: 0 auto;
            line-height: 1.7;
        }
    </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav id="mainNav">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between">
            <!-- Brand -->
            <a href="{{ route('landing') }}" class="nav-brand-wrap">
                <img src="{{ asset('images/scc-logo.png') }}" alt="SCC Logo" class="nav-brand-logo">
                <div class="nav-brand-text">
                    <span class="nav-brand-name">SCC ReportHub</span>
                </div>
            </a>

            <!-- Center nav links (desktop) -->
            <div class="d-none d-lg-flex align-items-center gap-1">
                <a href="#features" class="nav-link-custom">Features</a>
                <a href="#how-it-works" class="nav-link-custom">How It Works</a>
                <a href="#roles" class="nav-link-custom">Roles</a>
            </div>

            <!-- Right buttons (desktop) -->
            <div class="d-none d-lg-flex align-items-center gap-2">
                <a href="{{ route('login') }}" class="btn-nav-signin">Sign In</a>
                <a href="{{ route('register') }}" class="btn-nav-started">Get Started</a>
            </div>

            <!-- Hamburger (mobile) -->
            <button class="hamburger-btn d-lg-none" id="hamburgerBtn" aria-label="Open menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</nav>

<!-- Mobile Overlay Menu -->
<div class="mobile-menu-overlay" id="mobileMenu">
    <button class="mobile-close-btn" id="mobileCloseBtn" aria-label="Close menu">
        <i class="fas fa-times"></i>
    </button>
    <a href="{{ route('landing') }}" onclick="closeMobileMenu()">Home</a>
    <a href="#features" onclick="closeMobileMenu()">Features</a>
    <a href="#how-it-works" onclick="closeMobileMenu()">How It Works</a>
    <a href="#roles" onclick="closeMobileMenu()">Roles</a>
    <a href="{{ route('login') }}" onclick="closeMobileMenu()">Sign In</a>
    <a href="{{ route('register') }}" onclick="closeMobileMenu()" style="color:#93c5fd;">Get Started</a>
</div>

<!-- ===== HERO ===== -->
<section id="hero">
    <div class="hero-deco hero-deco-1"></div>
    <div class="hero-deco hero-deco-2"></div>
    <div class="hero-deco hero-deco-3"></div>
    <div class="container text-center position-relative">
        <img src="{{ asset('images/scc-logo.png') }}" alt="SCC Logo" class="hero-logo fade-in">
        <h1 class="hero-title fade-in fade-in-delay-1">SCC ReportHub</h1>
        <h2 class="hero-subtitle fade-in fade-in-delay-2">
            A Centralized Facility Maintenance and Issue Reporting System<br class="d-none d-md-block"> for Southern Christian College
        </h2>
        <p class="hero-desc fade-in fade-in-delay-3">
            Streamline facility concerns, track maintenance requests in real time, and keep your campus running smoothly — all in one place.
        </p>
        <div class="d-flex flex-wrap gap-3 justify-content-center fade-in fade-in-delay-4">
            <a href="{{ route('register') }}" class="btn-hero-primary">
                <i class="fas fa-rocket me-2"></i>Get Started
            </a>
            <a href="{{ route('login') }}" class="btn-hero-outline">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
            </a>
        </div>
    </div>
</section>


<!-- ===== FEATURES ===== -->
<section id="features" style="padding: 96px 0; background: #fff;">
    <div class="container">
        <div class="text-center mb-5 fade-in">
            <span class="section-label">What We Offer</span>
            <h2 class="section-title">Key Features</h2>
            <p class="section-desc">Everything you need to manage facility maintenance efficiently — from submission to resolution.</p>
        </div>
        <div class="row g-4 justify-content-center">
            <!-- Card 1 -->
            <div class="col-md-4 fade-in fade-in-delay-1">
                <div class="feature-card h-100">
                    <div class="feature-icon-wrap" style="background: linear-gradient(135deg,#1e40af,#3b82f6);">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <h3 class="feature-title">Submit Ticket Requests</h3>
                    <p class="feature-desc">Easily report facility issues and maintenance concerns through a simple, guided submission form available to all faculty and staff.</p>
                    <div class="feature-accent" style="background: linear-gradient(90deg,#1e40af,#3b82f6);"></div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-md-4 fade-in fade-in-delay-2">
                <div class="feature-card h-100">
                    <div class="feature-icon-wrap" style="background: linear-gradient(135deg,#7c3aed,#6d28d9);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="feature-title">Track Request Status</h3>
                    <p class="feature-desc">Monitor the real-time progress of your submitted requests — from pending review to active repair and final resolution.</p>
                    <div class="feature-accent" style="background: linear-gradient(90deg,#7c3aed,#6d28d9);"></div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-md-4 fade-in fade-in-delay-3">
                <div class="feature-card h-100">
                    <div class="feature-icon-wrap" style="background: linear-gradient(135deg,#0d9488,#0891b2);">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3 class="feature-title">Maintenance Task Management</h3>
                    <p class="feature-desc">Administrators assign tasks to maintenance staff, who can update work status and log completion details for full accountability.</p>
                    <div class="feature-accent" style="background: linear-gradient(90deg,#0d9488,#0891b2);"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .feature-card {
        background: #fff;
        border-radius: 18px;
        padding: 36px 28px 28px;
        box-shadow: 0 4px 24px rgba(30,64,175,0.08);
        border: 1px solid #f1f5f9;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 48px rgba(30,64,175,0.14);
    }
    .feature-accent {
        position: absolute; top: 0; left: 0; right: 0;
        height: 4px; border-radius: 18px 18px 0 0;
    }
    .feature-icon-wrap {
        width: 60px; height: 60px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 20px;
        font-size: 24px; color: #fff;
        box-shadow: 0 4px 16px rgba(37,99,235,0.25);
    }
    .feature-title {
        font-size: 1.15rem; font-weight: 700;
        color: #0f172a; margin-bottom: 10px;
    }
    .feature-desc {
        font-size: 0.93rem; color: #64748b;
        line-height: 1.7; margin: 0;
    }
</style>


<!-- ===== HOW IT WORKS ===== -->
<section id="how-it-works" style="padding: 96px 0; background: #f8fafc;">
    <div class="container">
        <div class="text-center mb-5 fade-in">
            <span class="section-label">The Process</span>
            <h2 class="section-title">How It Works</h2>
            <p class="section-desc">A simple four-step process that takes your facility concern from submission to resolution.</p>
        </div>

        <!-- Steps -->
        <div class="hiw-wrapper fade-in">
            <!-- Step 1 -->
            <div class="hiw-step">
                <div class="hiw-circle gradient-bg">
                    <span class="hiw-num">1</span>
                    <i class="fas fa-file-alt hiw-icon"></i>
                </div>
                <div class="hiw-connector d-none d-md-block"></div>
                <h4 class="hiw-title">Submit a Facility Concern</h4>
                <p class="hiw-desc">Faculty or staff fills out a ticket form describing the issue, location, and urgency level.</p>
            </div>
            <!-- Step 2 -->
            <div class="hiw-step">
                <div class="hiw-circle gradient-bg-purple">
                    <span class="hiw-num">2</span>
                    <i class="fas fa-user-check hiw-icon"></i>
                </div>
                <div class="hiw-connector d-none d-md-block"></div>
                <h4 class="hiw-title">Administrator Reviews Request</h4>
                <p class="hiw-desc">The admin evaluates the report, prioritizes it, and assigns it to the appropriate maintenance staff.</p>
            </div>
            <!-- Step 3 -->
            <div class="hiw-step">
                <div class="hiw-circle gradient-bg-teal">
                    <span class="hiw-num">3</span>
                    <i class="fas fa-tools hiw-icon"></i>
                </div>
                <div class="hiw-connector d-none d-md-block"></div>
                <h4 class="hiw-title">Maintenance Staff Performs Repair</h4>
                <p class="hiw-desc">Assigned staff carries out the repair work and logs updates, photos, and completion notes.</p>
            </div>
            <!-- Step 4 -->
            <div class="hiw-step">
                <div class="hiw-circle" style="background: linear-gradient(135deg,#0369a1,#0284c7);">
                    <span class="hiw-num">4</span>
                    <i class="fas fa-chart-line hiw-icon"></i>
                </div>
                <h4 class="hiw-title">Monitor Progress and Resolution</h4>
                <p class="hiw-desc">Requesters and admins track real-time status updates until the issue is fully resolved and closed.</p>
            </div>
        </div>
    </div>
</section>

<style>
    .hiw-wrapper {
        display: flex;
        flex-direction: column;
        gap: 40px;
    }
    @media (min-width: 768px) {
        .hiw-wrapper {
            flex-direction: row;
            align-items: flex-start;
            gap: 0;
        }
    }
    .hiw-step {
        flex: 1;
        text-align: center;
        padding: 0 16px;
        position: relative;
    }
    .hiw-circle {
        width: 80px; height: 80px;
        border-radius: 50%;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        margin: 0 auto 18px;
        box-shadow: 0 6px 24px rgba(37,99,235,0.22);
        position: relative;
    }
    .hiw-num {
        font-size: 11px; font-weight: 800;
        color: rgba(255,255,255,0.7);
        line-height: 1;
        position: absolute; top: 10px;
    }
    .hiw-icon {
        font-size: 26px; color: #fff;
        margin-top: 8px;
    }
    .hiw-connector {
        position: absolute;
        top: 40px;
        left: calc(50% + 44px);
        right: calc(-50% + 44px);
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #7c3aed);
        z-index: 0;
    }
    .hiw-title {
        font-size: 1rem; font-weight: 700;
        color: #0f172a; margin-bottom: 8px;
    }
    .hiw-desc {
        font-size: 0.88rem; color: #64748b;
        line-height: 1.65; margin: 0;
    }
</style>


<!-- ===== USER ROLES ===== -->
<section id="roles" style="padding: 96px 0; background: #fff;">
    <div class="container">
        <div class="text-center mb-5 fade-in">
            <span class="section-label">User Roles</span>
            <h2 class="section-title">Who Uses SCC ReportHub?</h2>
            <p class="section-desc">Three distinct roles work together to keep Southern Christian College's facilities in top condition.</p>
        </div>
        <div class="row g-4 justify-content-center">
            <!-- Faculty & Staff -->
            <div class="col-md-4 fade-in fade-in-delay-1">
                <div class="role-card h-100" style="--accent: #2563eb;">
                    <div class="role-icon-wrap" style="background: linear-gradient(135deg,#1e40af,#3b82f6);">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="role-name">Faculty &amp; Staff</h3>
                    <p class="role-desc">Report facility concerns and track the status of submitted maintenance requests.</p>
                    <ul class="role-list">
                        <li><i class="fas fa-check-circle"></i> Submit facility issue tickets</li>
                        <li><i class="fas fa-check-circle"></i> Monitor request progress</li>
                        <li><i class="fas fa-check-circle"></i> View history of completed requests</li>
                    </ul>
                </div>
            </div>
            <!-- Maintenance Staff -->
            <div class="col-md-4 fade-in fade-in-delay-2">
                <div class="role-card h-100" style="--accent: #0d9488;">
                    <div class="role-icon-wrap" style="background: linear-gradient(135deg,#0d9488,#0891b2);">
                        <i class="fas fa-hard-hat"></i>
                    </div>
                    <h3 class="role-name">Maintenance Staff</h3>
                    <p class="role-desc">Receive assigned tasks, perform repairs, and update the status of maintenance work.</p>
                    <ul class="role-list">
                        <li><i class="fas fa-check-circle" style="color:#0d9488;"></i> View and accept assigned tasks</li>
                        <li><i class="fas fa-check-circle" style="color:#0d9488;"></i> Log repair progress and notes</li>
                        <li><i class="fas fa-check-circle" style="color:#0d9488;"></i> Mark tasks as completed</li>
                    </ul>
                </div>
            </div>
            <!-- Administrator -->
            <div class="col-md-4 fade-in fade-in-delay-3">
                <div class="role-card h-100" style="--accent: #7c3aed;">
                    <div class="role-icon-wrap" style="background: linear-gradient(135deg,#7c3aed,#6d28d9);">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3 class="role-name">Administrator</h3>
                    <p class="role-desc">Oversee all reports, assign tasks to maintenance staff, and monitor system-wide performance.</p>
                    <ul class="role-list">
                        <li><i class="fas fa-check-circle" style="color:#7c3aed;"></i> Manage all submitted tickets</li>
                        <li><i class="fas fa-check-circle" style="color:#7c3aed;"></i> Assign tasks to maintenance staff</li>
                        <li><i class="fas fa-check-circle" style="color:#7c3aed;"></i> View analytics and reports</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .role-card {
        background: #fff;
        border-radius: 18px;
        padding: 32px 26px;
        border: 1px solid #f1f5f9;
        border-top: 4px solid var(--accent, #2563eb);
        box-shadow: 0 4px 20px rgba(30,64,175,0.07);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .role-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 14px 40px rgba(30,64,175,0.13);
    }
    .role-icon-wrap {
        width: 58px; height: 58px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; color: #fff;
        margin-bottom: 18px;
        box-shadow: 0 4px 14px rgba(37,99,235,0.2);
    }
    .role-name {
        font-size: 1.1rem; font-weight: 800;
        color: #0f172a; margin-bottom: 8px;
    }
    .role-desc {
        font-size: 0.9rem; color: #64748b;
        line-height: 1.65; margin-bottom: 18px;
    }
    .role-list {
        list-style: none; padding: 0; margin: 0;
        display: flex; flex-direction: column; gap: 8px;
    }
    .role-list li {
        font-size: 0.88rem; color: #475569;
        display: flex; align-items: center; gap: 8px;
    }
    .role-list li i { color: #2563eb; font-size: 13px; flex-shrink: 0; }
</style>


<!-- ===== STATISTICS ===== -->
<section id="statistics" style="padding: 96px 0; background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);">
    <div class="container">
        <div class="text-center mb-5 fade-in">
            <span class="section-label" style="color:#93c5fd; background:rgba(147,197,253,0.12);">By The Numbers</span>
            <h2 class="section-title" style="color:#fff;">System at a Glance</h2>
            <p class="section-desc" style="color:#94a3b8;">Real-time insights into the state of facility maintenance across the campus.</p>
        </div>
        <div class="row g-4 justify-content-center" id="statsRow">
            <div class="col-6 col-md-3 fade-in fade-in-delay-1">
                <div class="stat-card">
                    <div class="stat-icon-wrap" style="background:linear-gradient(135deg,#1e40af,#3b82f6);">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="stat-number" data-target="248">0</div>
                    <div class="stat-label">Total Reports</div>
                </div>
            </div>
            <div class="col-6 col-md-3 fade-in fade-in-delay-2">
                <div class="stat-card">
                    <div class="stat-icon-wrap" style="background:linear-gradient(135deg,#d97706,#f59e0b);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-number" data-target="42">0</div>
                    <div class="stat-label">Pending Requests</div>
                </div>
            </div>
            <div class="col-6 col-md-3 fade-in fade-in-delay-3">
                <div class="stat-card">
                    <div class="stat-icon-wrap" style="background:linear-gradient(135deg,#059669,#10b981);">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div class="stat-number" data-target="189">0</div>
                    <div class="stat-label">Resolved Issues</div>
                </div>
            </div>
            <div class="col-6 col-md-3 fade-in fade-in-delay-4">
                <div class="stat-card">
                    <div class="stat-icon-wrap" style="background:linear-gradient(135deg,#7c3aed,#6d28d9);">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number" data-target="156">0</div>
                    <div class="stat-label">Active Users</div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .stat-card {
        background: rgba(255,255,255,0.06);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 20px;
        padding: 32px 20px;
        text-align: center;
        transition: transform 0.3s, background 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-6px);
        background: rgba(255,255,255,0.10);
    }
    .stat-icon-wrap {
        width: 54px; height: 54px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; color: #fff;
        margin: 0 auto 16px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.3);
    }
    .stat-number {
        font-size: 2.6rem; font-weight: 900;
        color: #fff; line-height: 1;
        margin-bottom: 6px;
        background: linear-gradient(135deg, #93c5fd, #c4b5fd);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .stat-label {
        font-size: 0.85rem; font-weight: 600;
        color: #94a3b8; text-transform: uppercase;
        letter-spacing: 0.06em;
    }
</style>


<!-- ===== FOOTER ===== -->
<footer style="background: #0f172a; padding: 72px 0 0;">
    <div class="container">
        <div class="row g-5">
            <!-- Brand column -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-3 mb-16" style="margin-bottom:16px;">
                    <img src="{{ asset('images/scc-logo.png') }}" alt="SCC Logo" style="width:44px;height:44px;object-fit:contain;">
                    <div>
                        <div style="font-size:17px;font-weight:800;background:linear-gradient(135deg,#93c5fd,#c4b5fd);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">SCC ReportHub</div>
                        <div style="font-size:11px;color:#64748b;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;">Southern Christian College</div>
                    </div>
                </div>
                <p style="font-size:0.88rem;color:#64748b;line-height:1.7;max-width:300px;">
                    A centralized facility maintenance and issue reporting system designed to keep the SCC campus safe, functional, and well-maintained.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 col-6">
                <h5 style="font-size:0.9rem;font-weight:700;color:#e2e8f0;margin-bottom:18px;text-transform:uppercase;letter-spacing:0.08em;">Quick Links</h5>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px;">
                    <li><a href="#features" class="footer-link">Features</a></li>
                    <li><a href="#how-it-works" class="footer-link">How It Works</a></li>
                    <li><a href="#roles" class="footer-link">Roles</a></li>
                    <li><a href="{{ route('login') }}" class="footer-link">Sign In</a></li>
                    <li><a href="{{ route('register') }}" class="footer-link">Get Started</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-3 col-md-6">
                <h5 style="font-size:0.9rem;font-weight:700;color:#e2e8f0;margin-bottom:18px;text-transform:uppercase;letter-spacing:0.08em;">Contact</h5>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:12px;">
                    <li class="footer-contact-item">
                        <i class="fas fa-university" style="color:#3b82f6;width:18px;"></i>
                        <span>Southern Christian College</span>
                    </li>
                    <li class="footer-contact-item">
                        <i class="fas fa-map-marker-alt" style="color:#3b82f6;width:18px;"></i>
                        <span>Midsayap, Cotabato, Philippines</span>
                    </li>
                    <li class="footer-contact-item">
                        <i class="fas fa-globe" style="color:#3b82f6;width:18px;"></i>
                        <span>scc-reporthub.edu.ph</span>
                    </li>
                </ul>
            </div>

            <!-- CTA column -->
            <div class="col-lg-3 col-md-6">
                <h5 style="font-size:0.9rem;font-weight:700;color:#e2e8f0;margin-bottom:18px;text-transform:uppercase;letter-spacing:0.08em;">Get Started Today</h5>
                <p style="font-size:0.88rem;color:#64748b;line-height:1.65;margin-bottom:18px;">
                    Join the SCC community in keeping our campus facilities in excellent condition.
                </p>
                <a href="{{ route('register') }}" style="display:inline-block;padding:11px 26px;background:linear-gradient(135deg,#2563eb,#7c3aed);color:#fff;border-radius:10px;font-size:0.9rem;font-weight:700;text-decoration:none;transition:opacity 0.2s;">
                    <i class="fas fa-rocket me-2"></i>Create Account
                </a>
            </div>
        </div>

        <!-- Bottom bar -->
        <div style="margin-top:56px;padding:20px 0;border-top:1px solid rgba(255,255,255,0.07);display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;">
            <p style="font-size:0.83rem;color:#475569;margin:0;">
                &copy; {{ date('Y') }} SCC ReportHub &mdash; Southern Christian College. All rights reserved.
            </p>
            <p style="font-size:0.83rem;color:#334155;margin:0;">
                Built with <i class="fas fa-heart" style="color:#ef4444;"></i> for Southern Christian College
            </p>
        </div>
    </div>
</footer>

<style>
    .footer-link {
        font-size: 0.88rem; color: #64748b;
        text-decoration: none;
        transition: color 0.2s;
    }
    .footer-link:hover { color: #93c5fd; }
    .footer-contact-item {
        display: flex; align-items: flex-start; gap: 10px;
        font-size: 0.88rem; color: #64748b; line-height: 1.5;
    }
    .footer-contact-item i { margin-top: 2px; flex-shrink: 0; }
</style>


<!-- ===== BACK TO TOP ===== -->
<button id="backToTop" aria-label="Back to top" style="
    display:none; position:fixed; bottom:28px; right:28px; z-index:999;
    width:46px; height:46px; border-radius:50%;
    background:linear-gradient(135deg,#2563eb,#7c3aed);
    color:#fff; border:none; font-size:18px;
    box-shadow:0 4px 18px rgba(37,99,235,0.35);
    cursor:pointer; transition:transform 0.2s, opacity 0.3s;
    align-items:center; justify-content:center;
">
    <i class="fas fa-chevron-up"></i>
</button>

<!-- Bootstrap 5.3.2 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // ===== NAVBAR SCROLL STATE =====
    const nav = document.getElementById('mainNav');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 20);
    });

    // ===== MOBILE MENU =====
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const mobileMenu   = document.getElementById('mobileMenu');
    const mobileClose  = document.getElementById('mobileCloseBtn');

    hamburgerBtn.addEventListener('click', () => mobileMenu.classList.add('open'));
    mobileClose.addEventListener('click',  () => mobileMenu.classList.remove('open'));

    function closeMobileMenu() {
        mobileMenu.classList.remove('open');
    }

    // ===== INTERSECTION OBSERVER: FADE-IN =====
    const fadeEls = document.querySelectorAll('.fade-in');
    const fadeObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                fadeObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    fadeEls.forEach(el => fadeObserver.observe(el));

    // ===== COUNTER ANIMATION =====
    function animateCounter(el, target, duration) {
        let start = 0;
        const step = Math.ceil(target / (duration / 16));
        const timer = setInterval(() => {
            start += step;
            if (start >= target) {
                el.textContent = target;
                clearInterval(timer);
            } else {
                el.textContent = start;
            }
        }, 16);
    }

    const statsSection = document.getElementById('statsRow');
    let countersStarted = false;

    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !countersStarted) {
                countersStarted = true;
                document.querySelectorAll('.stat-number').forEach(el => {
                    const target = parseInt(el.getAttribute('data-target'), 10);
                    animateCounter(el, target, 1600);
                });
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });

    if (statsSection) statsObserver.observe(statsSection);

    // ===== BACK TO TOP =====
    const backToTopBtn = document.getElementById('backToTop');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 400) {
            backToTopBtn.style.display = 'flex';
            backToTopBtn.style.opacity = '1';
        } else {
            backToTopBtn.style.opacity = '0';
            setTimeout(() => {
                if (window.scrollY <= 400) backToTopBtn.style.display = 'none';
            }, 300);
        }
    });
    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    backToTopBtn.addEventListener('mouseenter', () => {
        backToTopBtn.style.transform = 'translateY(-3px) scale(1.08)';
    });
    backToTopBtn.addEventListener('mouseleave', () => {
        backToTopBtn.style.transform = 'translateY(0) scale(1)';
    });

    // ===== SMOOTH SCROLL for anchor links =====
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                const offset = 80;
                const top = target.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        });
    });
</script>
</body>
</html>
