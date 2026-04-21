<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') — VueJobs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --vue: #42b883; --vue-dark: #379a6e; }
        .bg-vue { background-color: var(--vue) !important; }
        .btn-vue { background-color: var(--vue); border-color: var(--vue); color: #fff; }
        .btn-vue:hover { background-color: var(--vue-dark); border-color: var(--vue-dark); color: #fff; }
        .text-vue { color: var(--vue) !important; }

        /* Sidebar */
        .admin-sidebar {
            width: 240px;
            min-height: 100vh;
            background: #1e293b;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: transform 0.2s;
        }
        .admin-sidebar .brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .admin-sidebar .nav-link {
            color: rgba(255,255,255,0.6);
            padding: 0.6rem 1.5rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.15s;
        }
        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.08);
        }
        .admin-sidebar .nav-link.active {
            border-right: 3px solid var(--vue);
        }
        .admin-sidebar .sidebar-section {
            padding: 1rem 1.5rem 0.4rem;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255,255,255,0.3);
            font-weight: 600;
        }
        .admin-content {
            margin-left: 240px;
            min-height: 100vh;
            background: #f8f9fa;
        }
        .admin-topbar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.75rem 1.5rem;
        }

        @media (max-width: 768px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.show { transform: translateX(0); }
            .admin-content { margin-left: 0; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="sidebar">
        <div class="brand">
            <a href="{{ route('admin.blogs.index') }}" class="text-decoration-none">
                <span class="text-white fw-bold fs-5">VueJobs</span>
                <span class="text-vue fs-6 fw-medium ms-1">Admin</span>
            </a>
        </div>

        <nav class="mt-2">
            <div class="sidebar-section">Content</div>
            <a href="{{ route('admin.blogs.index') }}"
               class="nav-link {{ request()->routeIs('admin.blogs.index') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> All Blogs
            </a>
            <a href="{{ route('admin.blogs.create') }}"
               class="nav-link {{ request()->routeIs('admin.blogs.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i> New Blog
            </a>
        </nav>

        <!-- User at bottom -->
        <div class="position-absolute bottom-0 start-0 end-0 p-3 border-top" style="border-color: rgba(255,255,255,0.08) !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-vue d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
                        <span class="text-white fw-semibold small">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    </div>
                    <span class="text-white small">{{ auth()->user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link btn-sm text-secondary p-0" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main content -->
    <div class="admin-content">
        <!-- Topbar -->
        <div class="admin-topbar d-flex align-items-center justify-content-between">
            <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list"></i>
            </button>
            <h6 class="mb-0 text-muted small">@yield('title', 'Dashboard')</h6>
            <div></div>
        </div>

        <!-- Page content -->
        <div class="p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
