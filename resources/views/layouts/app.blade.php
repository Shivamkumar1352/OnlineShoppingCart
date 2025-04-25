<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel App</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <style>
        /* Enhanced Profile Dropdown Styles */
        .profile-dropdown {
            transition: all 0.3s ease;
        }

        .profile-dropdown .dropdown-toggle {
            border-color: #3b82f6;
            color: #3b82f6;
            padding: 8px 12px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .profile-dropdown .dropdown-toggle:hover {
            background-color: rgba(59, 130, 246, 0.1);
            transform: scale(1.05);
        }

        .profile-dropdown .dropdown-menu {
            background-color: #1f2937;
            border: 1px solid #3b82f6;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
            min-width: 220px;
        }

        .profile-dropdown .dropdown-item {
            padding: 10px 15px;
            transition: all 0.2s ease;
            border-radius: 4px;
            margin: 2px 5px;
        }

        .profile-dropdown .dropdown-item:hover {
            background-color: #3b82f6 !important;
            color: white !important;
            transform: translateX(5px);
        }

        .profile-dropdown .dropdown-divider {
            border-color: #4b5563;
            margin: 8px 0;
        }

        .profile-dropdown .user-info {
            padding: 10px 15px;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 4px;
            margin: 5px;
        }

        /* Make the profile icon more prominent */
        .profile-icon {
            font-size: 1.4rem;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div style="background-color: #101827;">
        <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #101827;">
            <div class="container">
                <!-- Left side: Logo -->
                <a class="navbar-brand" href="{{ route('products') }}">
                    <img width="48" height="48" src="https://img.icons8.com/emoji/48/shopping-bags.png" alt="shopping-bags"/>
                </a>
                <h2 class="text-primary m-0"><b>My Store</b></h2>
                <div class="d-flex align-items-center ms-auto">
                    @auth
                        <!-- Enhanced Profile Dropdown -->
                        <div class="dropdown profile-dropdown">
                            <button class="btn btn-outline-light dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle profile-icon"></i>
                                <span class="ms-2 d-none d-sm-inline">{{ Auth::user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li>
                                    <div class="user-info text-white">
                                        <div class="fw-bold">{{ Auth::user()->name }}</div>
                                        <small class="fw-bold">{{ Auth::user()->email }}</small>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-white" href="{{ route('profile.orders') }}">
                                        <i class="bi bi-receipt me-2"></i>Order History
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-white w-100 text-start">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary ms-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary ms-2">Register</a>
                    @endauth
                </div>
            </div>
        </nav>

        <div class="container mt-4">
            @yield('content')
        </div>
    </div>
</body>
</html>
