<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Result Analysis System</title>
    <link rel="icon" href="{{ asset('assets/images/icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar" class="sidebarMobile">
            <!-- Content for sidebar -->
            <div class="h-100">
                <div>
                    <a href="#"><img src="{{ asset('assets/images/logo.png') }}" alt="logo"
                            class="img-fluid"></a>
                </div>
                <ul class="sidear-nav">
                    <li class="sidebar-item {{ Route::is('support-dashboard') ? 'active' : '' }}">
                        <a href="{{ route('support-dashboard') }}" class="sidebar-link">
                            <i class="bi bi-sliders"></i> Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <span class="sidebar-link">
                            <i class="bi bi-three-dots-vertical"></i> Menu
                        </span>
                    </li>
                    <li class="sidebar-item {{ Route::is('support-tickets') ? 'active' : '' }}">
                        <a href="{{ route('support-tickets') }}" class="sidebar-nested-link">
                            <i class="bi bi-file-earmark-arrow-up-fill"></i> All Tickets
                        </a>
                    </li>
                    <li class="sidebar-item {{ Route::is('support-pending') ? 'active' : '' }}">
                        <a href="{{ route('support-pending') }}" class="sidebar-nested-link">
                            <i class="bi bi-file-earmark-arrow-down-fill"></i> Pending Tickets
                        </a>
                    </li>
                    <li class="sidebar-item {{ Route::is('support-resolved') ? 'active' : '' }}">
                        <a href="{{ route('support-resolved') }}" class="sidebar-nested-link">
                            <i class="bi bi-cloud-arrow-up-fill"></i> Resolved Tickets
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <div class="main">
            <nav class="navbar navbar-expand px-3 border-bottom bg-light">
                <button class="btn" id="sidebar-toggle" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="breadcrumb-bar text-custom px-3">
                    <span class="breadcrumb-item fs-6">
                        @yield('breadcrumb')
                    </span>
                </div>
                <div class="navbar-collapse navbar">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a href="" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <img src="{{ asset('assets/images/admin.png') }}"
                                    class="rounded-circle avatar img-fluid" alt="profile-ph">
                                <span
                                    class="text-custom d-none s-md-inline d-lg-inline d-xl-inline d-xxl-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="{{ route('profile.edit') }}" class="dropdown-item"><i
                                        class="bi bi-person-fill fs-5 pe-1"></i>
                                    Profile
                                </a>
                                <a href="{{ route('support') }}" class="dropdown-item"><i
                                        class="bi bi-headset fs-5 pe-1"></i>
                                    Support
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    <button type="submit" class="dropdown-item"><i
                                            class="bi bi-box-arrow-right fs-5 pe-1"></i> Log-Out</button>
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>

                </div>
            </nav>
            <main class="content">
                @yield('content')
                <footer class="footer p-3" style="background-color: #eceff3">
                    <span>All rights reserved. Designed and developed by <a
                            href="https://smu.edu.in/smit/dept-faculty/dept-list/dept-ca-smit.html"
                            target="_blank">Department of Computer Applications</a>, Sikkim Manipal Institute of
                        Technology</span>
                </footer>
            </main>
        </div>
    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/toggleButton.js') }}"></script> --}}
    <script src="{{ asset('assets/js/hidepoupdatetable.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/chart.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/showPassword.js') }}"></script> --}}
    @yield('scripts')

    <script src="{{ asset('assets/js/script.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/progress-bar.js') }}"></script> --}}
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.selectpicker').selectpicker();
        });
    </script>
</body>

</html>
