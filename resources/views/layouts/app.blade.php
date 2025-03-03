<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TahfidzTrack')</title>
</head>
<body>

    @if (!request()->is('/') && !request()->is('login'))
        <header class="p-3 bg-dark text-white">
            <div class="container d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">TahfidzTrack</h1>
                <nav>
                    <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm">Dashboard</a>
                    <a href="{{ route('program.create') }}" class="btn btn-primary btn-sm">Buat Program</a>
                </nav>
            </div>
        </header>
    @endif

    <main class="container mt-4">
        @yield('content')
    </main>

</body>
</html>
