<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel App</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</head>

<body>
    <div style="background-color: #101827; ">
        <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #101827;">
        <div class="container">
            <!-- Left side: Only Logo -->
            <a class="navbar-brand" href="{{ route('products') }}">
                🛍️
            </a>

            <!-- Right side: Buttons -->
            <div class="d-flex align-items-center ms-auto">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger ms-2">Logout 🚪</button>
                    </form>
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
