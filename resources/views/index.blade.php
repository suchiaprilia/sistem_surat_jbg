<!doctype html>
<html lang="en">
<head>
    <title>Live Preview | Gradient Able Dashboard Template</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Gradient Able is trending dashboard template made using Bootstrap 5 design framework..." />
    <meta name="keywords" content="Bootstrap admin template, Dashboard UI Kit..." />
    <meta name="author" content="codedthemes" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('gradient/assets/images/favicon.svg') }}" type="image/x-icon" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />

    <!-- Icon Fonts -->
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/feather.css') }}" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/fonts/material.css') }}" />

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('gradient/assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/css/style-preset.css') }}" />
    <link rel="stylesheet" href="{{ asset('gradient/assets/css/landing.css') }}" />
</head>

<body data-pc-header="header-1" data-pc-preset="preset-1" data-pc-sidebar-theme="light"
      data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light" class="landing-page">

    <!-- Pre-loader -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <!-- Header -->
    <header id="home" class="bg-white">
        <nav class="navbar navbar-expand-lg navbar-dark default">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('gradient/assets/images/logo-white.svg') }}" alt="logo" class="logo-lg" />
                </a>
                <button class="navbar-toggler rounded" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                    <ul class="navbar-nav ms-auto me-auto mb-2 mb-lg-0 align-items-start">
                        <li class="nav-item px-1">
                            <a class="nav-link" href="https://codedthemes.gitbook.io/gradient-able-bootstrap/" target="_blank">Documentation</a>
                        </li>
                        <li class="nav-item px-1">
                            <a class="nav-link" href="{{ url('/gradient/dashboard/index.html') }}">Live Preview</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav mb-2 mb-lg-0 align-items-start">
                        <li class="nav-item">
                            <a href="https://codedthemes.com/item/gradient-able-admin-template/" target="_blank" class="btn btn-success">
                                Get Gradient Able <i class="ph ph-arrow-square-out"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-5 header-content">
                    <h6 class="mb-0">Most Complete, Fast and Robust Flat User Interface</h6>
                    <h1 class="my-3">Friends with Gradient Able</h1>
                    <p class="text-muted f-16 my-3">
                        Gradient Able is the first released flat UI design admin template made using Bootstrap 5.
                        The most productive feature-rich dashboard template ever built in Bootstrap 5.
                    </p>
                    <div>
                        <a href="{{ url('/gradient/dashboard/index.html') }}" class="btn btn-dark me-2">Preview</a>
                        <a href="https://codedthemes.com/item/gradient-able-admin-template/" target="_blank" class="btn btn-primary">Buy Now</a>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card img-header">
                        <img src="{{ asset('gradient/assets/images/landing/img-header-moke.png') }}" alt="img" class="img-fluid" />
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Scripts -->
    <script src="{{ asset('gradient/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('gradient/assets/js/plugins/bootstrap.min.js') }}"></script>
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
