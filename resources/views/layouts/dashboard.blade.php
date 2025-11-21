<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Dashboard') | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />
</head>

<body class="loading" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": false}'>
    <div class="wrapper">

        <!-- Left Sidebar -->
        <div class="leftside-menu">
            <a href="{{ url('/') }}" class="logo text-center logo-light">
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/yo-print.png') }}" alt="YoPrint" height="30">
                </span>
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/yo-print.png') }}" alt="YoPrint" height="30">
                </span>
            </a>

            <a href="{{ url('/') }}" class="logo text-center logo-dark">
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/yo-print.png') }}" alt="YoPrint" height="30">
                </span>
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/yo-print.png') }}" alt="YoPrint" height="30">
                </span>
            </a>

            <div class="h-100" id="leftside-menu-container" data-simplebar>
                <ul class="side-nav">
                    <li class="side-nav-title side-nav-item">Navigation</li>

                    <li class="side-nav-item">
                        <a href="{{ route('csv.index') }}" class="side-nav-link {{ request()->routeIs('csv.index') ? 'active' : '' }}">
                            <i class="uil-folder-plus"></i>
                            <span> CSV Files </span>
                        </a>
                    </li>

                    <li class="side-nav-title side-nav-item">Admin</li>

                    <li class="side-nav-item">
                        <a href="{{ route('admin.csv.index') }}" class="side-nav-link {{ request()->routeIs('admin.csv.*') ? 'active' : '' }}">
                            <i class="uil-dashboard"></i>
                            <span> Processing Dashboard </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('admin.csv.data') }}" class="side-nav-link {{ request()->routeIs('admin.csv.data') ? 'active' : '' }}">
                            <i class="uil-database"></i>
                            <span> CSV Data Records </span>
                        </a>
                    </li>

                    <li class="side-nav-title side-nav-item">Account</li>

                    <li class="side-nav-item">
                        <a href="{{ route('profile.edit') }}" class="side-nav-link">
                            <i class="uil-user"></i>
                            <span> Profile </span>
                        </a>
                    </li>
                </ul>

                <div class="clearfix"></div>
            </div>
        </div>
        <!-- End Left Sidebar -->

        <!-- Content Page -->
        <div class="content-page">
            <div class="content">

                <!-- Topbar -->
                <div class="navbar-custom">
                    <ul class="list-unstyled topbar-menu float-end mb-0">
                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <span class="account-user-avatar">
                                    <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-image" class="rounded-circle">
                                </span>
                                <span>
                                    <span class="account-user-name">{{ Auth::user()->name }}</span>
                                    <span class="account-position">User</span>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome!</h6>
                                </div>

                                <a href="{{ route('profile.edit') }}" class="dropdown-item notify-item">
                                    <i class="mdi mdi-account-circle me-1"></i>
                                    <span>My Account</span>
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item notify-item">
                                        <i class="mdi mdi-logout me-1"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>

                    <button class="button-menu-mobile open-left">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </div>
                <!-- End Topbar -->

                <!-- Main Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>

            </div>

            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <script>document.write(new Date().getFullYear())</script> © {{ config('app.name') }}
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    @stack('scripts')
</body>

</html>
