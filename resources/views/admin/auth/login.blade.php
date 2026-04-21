<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin Login — VueJobs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --vue: #42b883; }
        .btn-vue { background-color: var(--vue); border-color: var(--vue); color: #fff; }
        .btn-vue:hover { background-color: #379a6e; border-color: #379a6e; color: #fff; }
        .form-control:focus { border-color: var(--vue); box-shadow: 0 0 0 0.2rem rgba(66,184,131,0.25); }
        .form-check-input:checked { background-color: var(--vue); border-color: var(--vue); }
    </style>
</head>
<body class="bg-light">
    <div class="min-vh-100 d-flex align-items-center justify-content-center">
        <div style="width: 100%; max-width: 400px;" class="px-3">
            <div class="text-center mb-4">
                <h3 class="fw-bold">VueJobs <span style="color: var(--vue);">Admin</span></h3>
                <p class="text-muted small">Sign in to manage blogs</p>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label small fw-medium">Email address</label>
                            <input
                                id="email" name="email" type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required autofocus
                                placeholder="admin@vuejobs.in"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label small fw-medium">Password</label>
                            <input
                                id="password" name="password" type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                required placeholder="••••••••"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label small" for="remember">Remember me</label>
                        </div>

                        <button type="submit" class="btn btn-vue w-100">Sign In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
