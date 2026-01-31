<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Surat Menyurat</title>

    <!-- Bootstrap CDN hanya di view ini -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f5f7fb; }
        .card { border: 0; border-radius: 16px; }
        .brand-badge {
            width: 44px; height: 44px; border-radius: 12px;
            display: grid; place-items: center;
            background: #0d6efd; color: #fff; font-weight: 700;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height:100vh;">
        <div class="col-12 col-md-7 col-lg-5">

            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="brand-badge">SM</div>
                        <div>
                            <h1 class="h4 mb-0">Surat Menyurat</h1>
                            <div class="text-muted small">Silakan login untuk melanjutkan</div>
                        </div>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger py-2">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.process') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="contoh@domain.com"
                                value="{{ old('email') }}"
                                required
                                autofocus
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Masukkan password"
                                required
                            >
                            <!-- <div class="form-text">Password dicek menggunakan MD5 (sesuai permintaan).</div> -->
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Login
                        </button>

                        <div class="text-center mt-3 small text-muted">
                            © {{ date('Y') }} Surat Menyurat
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS CDN hanya di view ini -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
