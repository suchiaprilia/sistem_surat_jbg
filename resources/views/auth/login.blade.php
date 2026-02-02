<!doctype html>
<html lang="id">
<head>
    <title>Login | Sistem Surat JBG</title>
    <!-- Meta -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="Jorong Barutama Greston" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('gradient/assets/images/favicon.svg') }}" type="image/x-icon" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/feather.css') }}" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/material.css') }}" />

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('gradient/assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/css/style-preset.css') }}" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .auth-main {
            background: linear-gradient(135deg, #f5f7fb 0%, #e4e9f2 100%);
        }
        .gradient-able-logo {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1.8rem;
            color: #4dabf7;
            margin-bottom: 1.5rem;
        }
        .logo {
            width: 120px;
            height: auto;
            display: block;
            margin: 0 auto 1.5rem;
        }
        .login-heading {
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body data-pc-header="header-1" data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light">
    <!-- Pre-loader -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <div class="auth-main v1 bg-grd-primary">
        <div class="auth-wrapper">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <!-- Teks SISTEM SURAT -->
                            <div class="gradient-able-logo">SISTEM SURAT</div>

                            <!-- HANYA LOGO DI TENGAH TANPA LATAR -->
                            <img src="{{ asset('gradient/assets/images/logojbgbaru.jpeg') }}"
                                 alt="Logo JBG"
                                 class="logo" />

                            <!-- Login heading -->
                            <h4 class="login-heading">Login with your email</h4>
                        </div>

                        <!-- Error Messages -->
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

                            <div class="form-group mb-3">
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    id="floatingInput"
                                    placeholder="Alamat Email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                />
                            </div>

                            <div class="form-group mb-3">
                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    id="floatingInput1"
                                    placeholder="Kata Sandi"
                                    required
                                />
                            </div>

                            <div class="d-flex mt-1 justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
                                    <label class="form-check-label text-muted" for="remember">Remember me?</label>
                                </div>
                                <a href="#" class="text-muted">Forgot Password?</a>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('gradient/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/script.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/theme.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/plugins/feather.min.js') }}"></script>

    <script>
        layout_change('light');
        layout_sidebar_change('light');
        change_box_container('false');
        layout_caption_change('true');
        layout_rtl_change('false');
        preset_change('preset-1');
        header_change('header-1');
    </script>
</body>
</html>
