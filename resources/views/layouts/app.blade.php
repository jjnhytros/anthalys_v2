<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Anthalys')</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
</head>

<body>
    <header class="bg-dark text-white p-3">
        <div class="container">
            <h1 class="h3">Benvenuti ad Anthalys</h1>
            <nav class="nav">
                <a class="nav-link text-white" href="{{ route('cities.index') }}">Città</a>
            </nav>
        </div>
    </header>

    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Anthalys</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
