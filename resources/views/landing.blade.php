<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name') }} - CSV Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="CSV Upload and Management System" name="description" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />
</head>

<body class="loading" data-layout-config='{"darkMode":false}'>

    <!-- NAVBAR START -->
    <nav class="navbar navbar-expand-lg py-lg-3 navbar-dark">
        <div class="container">
            <a href="{{ url('/') }}" class="navbar-brand me-lg-5">
                <img src="{{ asset('assets/images/logo.png') }}" alt="" class="logo-dark" height="18" />
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <i class="mdi mdi-menu"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                        <li class="nav-item me-0">
                            <a href="{{ route('csv.index') }}" class="btn btn-sm btn-light rounded-pill">
                                Dashboard
                            </a>
                        </li>
                    @else
                        <li class="nav-item me-2">
                            <a href="{{ route('login') }}" class="nav-link">Login</a>
                        </li>
                        <li class="nav-item me-0">
                            <a href="{{ route('register') }}" class="btn btn-sm btn-light rounded-pill">
                                Get Started
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <!-- NAVBAR END -->

    <!-- START HERO -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="mt-md-4">
                        <h2 class="text-white fw-normal mb-4 mt-3 hero-title">
                            Simple CSV File Management
                        </h2>
                        <p class="mb-4 font-16 text-white-50">Upload, manage, and track your CSV files with ease. A clean and efficient solution for your data management needs.</p>
                        @auth
                            <a href="{{ route('csv.index') }}" class="btn btn-success">Go to Dashboard <i class="mdi mdi-arrow-right ms-1"></i></a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-success">Get Started <i class="mdi mdi-arrow-right ms-1"></i></a>
                        @endauth
                    </div>
                </div>
                <div class="col-md-5 offset-md-1">
                    <div class="text-md-end mt-3 mt-md-0">
                        <img src="{{ asset('assets/images/startup.svg') }}" alt="" class="img-fluid" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END HERO -->

    <!-- START FEATURES -->
    <section class="py-5">
        <div class="container">
            <div class="row py-4">
                <div class="col-lg-12">
                    <div class="text-center">
                        <h3>Simple and <span class="text-primary">Efficient</span></h3>
                        <p class="text-muted mt-2">Everything you need for CSV file management in one place</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="text-center p-3">
                        <div class="avatar-sm m-auto">
                            <span class="avatar-title bg-primary-lighten rounded-circle">
                                <i class="uil uil-upload text-primary font-24"></i>
                            </span>
                        </div>
                        <h4 class="mt-3">Easy Upload</h4>
                        <p class="text-muted mt-2 mb-0">Upload your CSV files quickly and securely with our simple interface.</p>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="text-center p-3">
                        <div class="avatar-sm m-auto">
                            <span class="avatar-title bg-primary-lighten rounded-circle">
                                <i class="uil uil-file-check-alt text-primary font-24"></i>
                            </span>
                        </div>
                        <h4 class="mt-3">Track Status</h4>
                        <p class="text-muted mt-2 mb-0">Monitor the status of your uploaded files in real-time.</p>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="text-center p-3">
                        <div class="avatar-sm m-auto">
                            <span class="avatar-title bg-primary-lighten rounded-circle">
                                <i class="uil uil-server text-primary font-24"></i>
                            </span>
                        </div>
                        <h4 class="mt-3">Organized</h4>
                        <p class="text-muted mt-2 mb-0">Keep all your CSV files organized and easily accessible.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END FEATURES -->

    <!-- START FOOTER -->
    <footer class="bg-dark py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="text-muted mt-4 mb-0">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- END FOOTER -->

    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

</body>

</html>
