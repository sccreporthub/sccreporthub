/**
 * SCC ReportHub – Main JavaScript
 */

function toggleNavCollapse(el) {
    const item = el.closest('.nav-item-collapse');
    item.classList.toggle('open');
}

document.addEventListener('DOMContentLoaded', function () {

    // ─── Sidebar Toggle ───────────────────────────────────────────────────────
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose  = document.getElementById('sidebarClose');
    const sidebar = document.getElementById('sidebar');

    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        });

        if (sidebarClose) {
            sidebarClose.addEventListener('click', function (e) {
                e.stopPropagation();
                closeSidebar();
            });
        }

        overlay.addEventListener('click', closeSidebar);
    }

    // ─── Dark Mode ────────────────────────────────────────────────────────────
    const darkToggle = document.getElementById('darkModeToggle');
    const darkIcon   = document.getElementById('darkModeIcon');

    function applyDarkMode(enabled) {
        document.body.classList.toggle('dark-mode', enabled);
        if (darkIcon) {
            darkIcon.className = enabled ? 'fas fa-sun' : 'fas fa-moon';
        }
    }

    applyDarkMode(localStorage.getItem('darkMode') === 'true');

    if (darkToggle) {
        darkToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', String(!isDark));
            applyDarkMode(!isDark);
        });
    }




    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });

    // ─── Confirm Delete Buttons ───────────────────────────────────────────────
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const msg = this.dataset.confirm || 'Are you sure?';
            Swal.fire({
                title: 'Confirm Action',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, proceed',
            }).then(result => {
                if (result.isConfirmed) {
                    const form = this.closest('form');
                    if (form) form.submit();
                    else window.location.href = this.href;
                }
            });
        });
    });

    // ─── Tooltip Initialization ───────────────────────────────────────────────
    const tooltipEls = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipEls.forEach(el => new bootstrap.Tooltip(el));

    // ─── File Input Preview ───────────────────────────────────────────────────
    document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
        input.addEventListener('change', function () {
            const previewId = this.dataset.preview;
            const preview = document.getElementById(previewId);
            if (preview && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => preview.src = e.target.result;
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
});
