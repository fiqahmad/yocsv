<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Register | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="CSV Upload and Management System" name="description" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />
</head>

<body class="loading authentication-bg" data-layout-config='{"darkMode":false}'>
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">

                        <!-- Logo -->
                        <div class="card-header pt-4 pb-4 text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <a href="{{ url('/') }}">
                                <span><img src="{{ asset('assets/images/yo-print.png') }}" alt="YoPrint" height="40"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center pb-0 fw-bold">Sign Up</h4>
                                <p class="text-muted mb-4">Create your account to start managing CSV files.</p>
                            </div>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input class="form-control @error('name') is-invalid @enderror"
                                           type="text"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           required
                                           autofocus
                                           placeholder="Enter your name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input class="form-control @error('email') is-invalid @enderror"
                                           type="email"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required
                                           placeholder="Enter your email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password"
                                               id="password"
                                               name="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               required
                                               placeholder="Enter your password">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password"
                                               id="password_confirmation"
                                               name="password_confirmation"
                                               class="form-control"
                                               required
                                               placeholder="Confirm your password">
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 mb-0 text-center">
                                    <button class="btn btn-primary" type="submit"> Sign Up </button>
                                </div>

                            </form>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted">Already have an account?
                                <a href="{{ route('login') }}" class="text-muted ms-1"><b>Log In</b></a>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <footer class="footer footer-alt">
        {{ date('Y') }} © {{ config('app.name') }}
    </footer>

    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

</body>

</html>
