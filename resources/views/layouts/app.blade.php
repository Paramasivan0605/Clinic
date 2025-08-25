<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>{{ $title ?? 'Client' }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- Bootstrap 5 --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- FullCalendar --}}
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">

  <style>
    body { background: #f8f9fa; color: #212529; } /* light background + dark text */
    .card { background: #ffffff; border: 1px solid #dee2e6; } /* light cards */
    .form-control, .form-select { background: #ffffff; color: #212529; border-color: #ced4da; }
    a { text-decoration: none; }
    .navbar { border-bottom: 1px solid #dee2e6; }
  </style>

  @stack('head')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">Client</a>
    <div class="d-flex gap-2">
      @auth('admin')
        <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.dashboard') }}">Admin</a>
        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <button class="btn btn-danger btn-sm">Logout</button>
        </form>
      @else
        <a class="btn btn-outline-primary btn-sm" href=""></a>
      @endauth
    </div>
  </div>
</nav>

<main class="container my-4">
  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif
  @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
@stack('scripts')
</body>
</html>
