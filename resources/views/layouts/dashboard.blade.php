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
                    <img src="{{ asset('assets/images/yo-print.png') }}" alt="YoPrint" height="40">
                </span>
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/yo-print.png') }}" alt="YoPrint" height="35">
                </span>
            </a>

            <a href="{{ url('/') }}" class="logo text-center logo-dark">
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/yo-print.png') }}" alt="YoPrint" height="40">
                </span>
                <span class="logo-sm">
                    <img src="{{ asset('assets/images/yo-print.png') }}" alt="YoPrint" height="35">
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

                    @if(auth()->user()->is_admin)
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
                    @endif

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
                        <!-- Notification Bell -->
                        <li class="dropdown notification-list" id="notification-dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <i class="mdi mdi-bell-outline noti-icon"></i>
                                <span class="noti-icon-badge" id="notification-badge" style="display: none;"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg py-0">
                                <div class="p-2 border-top-0 border-start-0 border-end-0 border-dashed border">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0 font-16 fw-semibold">Notifications</h6>
                                        </div>
                                        <div class="col-auto">
                                            <a href="javascript:void(0);" class="text-dark text-decoration-underline" id="mark-all-read">
                                                <small>Mark all as read</small>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-1" style="max-height: 300px;" data-simplebar>
                                    <div id="notification-list">
                                        <div class="text-center py-3">
                                            <i class="mdi mdi-loading mdi-spin"></i> Loading...
                                        </div>
                                    </div>
                                </div>

                                <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item border-top py-2" id="view-all-notifications">
                                    View All
                                </a>
                            </div>
                        </li>

                        <!-- User Profile Dropdown -->
                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <span class="account-user-avatar">
                                    <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-image" class="rounded-circle">
                                </span>
                                <span>
                                    <span class="account-user-name">{{ Auth::user()->name }}</span>
                                    <span class="account-position">{{ Auth::user()->is_admin ? 'Admin' : 'User' }}</span>
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

    <!-- Notification System -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notificationList = document.getElementById('notification-list');
            const notificationBadge = document.getElementById('notification-badge');
            const markAllReadBtn = document.getElementById('mark-all-read');

            // Load notifications
            function loadNotifications() {
                fetch('{{ route("notifications.index") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateBadge(data.unread_count);
                    renderNotifications(data.notifications);
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    notificationList.innerHTML = '<div class="text-center py-3 text-muted">Failed to load notifications</div>';
                });
            }

            // Update badge count
            function updateBadge(count) {
                if (count > 0) {
                    notificationBadge.textContent = count > 99 ? '99+' : count;
                    notificationBadge.style.display = 'inline';
                } else {
                    notificationBadge.style.display = 'none';
                }
            }

            // Render notifications
            function renderNotifications(notifications) {
                if (notifications.length === 0) {
                    notificationList.innerHTML = '<div class="text-center py-3 text-muted">No notifications</div>';
                    return;
                }

                let html = '';
                notifications.forEach(notification => {
                    const isUnread = !notification.read_at;
                    const iconClass = getNotificationIcon(notification.type);
                    const bgClass = isUnread ? 'bg-light' : '';

                    html += `
                        <a href="javascript:void(0);" class="dropdown-item notify-item ${bgClass}" data-notification-id="${notification.id}">
                            <div class="notify-icon ${iconClass.bgColor}">
                                <i class="${iconClass.icon}"></i>
                            </div>
                            <p class="notify-details">${notification.title}
                                <small class="text-muted">${notification.message}</small>
                                <small class="text-muted">${notification.created_at}</small>
                            </p>
                        </a>
                    `;
                });

                notificationList.innerHTML = html;

                // Add click handlers
                notificationList.querySelectorAll('[data-notification-id]').forEach(item => {
                    item.addEventListener('click', function() {
                        const id = this.getAttribute('data-notification-id');
                        markAsRead(id);
                    });
                });
            }

            // Get icon based on notification type
            function getNotificationIcon(type) {
                const icons = {
                    'csv_uploaded': { icon: 'mdi mdi-upload', bgColor: 'bg-primary' },
                    'csv_processing': { icon: 'mdi mdi-cog-sync', bgColor: 'bg-info' },
                    'csv_completed': { icon: 'mdi mdi-check-circle', bgColor: 'bg-success' },
                    'csv_failed': { icon: 'mdi mdi-alert-circle', bgColor: 'bg-danger' }
                };
                return icons[type] || { icon: 'mdi mdi-bell', bgColor: 'bg-secondary' };
            }

            // Mark single notification as read
            function markAsRead(id) {
                fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateBadge(data.unread_count);
                    loadNotifications();
                });
            }

            // Mark all as read
            markAllReadBtn.addEventListener('click', function() {
                fetch('{{ route("notifications.readAll") }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateBadge(0);
                    loadNotifications();
                });
            });

            // Load notifications on dropdown open
            document.getElementById('notification-dropdown').addEventListener('show.bs.dropdown', function() {
                loadNotifications();
            });

            // Initial load and periodic refresh
            loadNotifications();
            setInterval(loadNotifications, 30000); // Refresh every 30 seconds
        });
    </script>

    @stack('scripts')
</body>

</html>
