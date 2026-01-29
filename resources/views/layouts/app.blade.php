<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('messages.app_name'))</title>
    <meta name="description" content="@yield('meta_description', __('messages.hero_subtitle'))">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@300;400;500;600;700&family=Noto+Sans+SC:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4A90A4;
            --secondary-color: #F5A623;
            --success-color: #7CB342;
            --light-bg: #F8F9FA;
            --text-color: #333333;
        }

        body {
            font-family: 'Noto Sans TC', 'Noto Sans SC', sans-serif;
            color: var(--text-color);
            background-color: var(--light-bg);
        }

        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .nav-link {
            color: var(--text-color) !important;
            font-weight: 500;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #3d7a8c;
            border-color: #3d7a8c;
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.12);
        }

        .card-school {
            height: 100%;
        }

        .ranking-badge {
            background: linear-gradient(135deg, var(--secondary-color), #ff9800);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .success-rate {
            color: var(--success-color);
            font-weight: 600;
        }

        .class-badge {
            background-color: #E3F2FD;
            color: #1976D2;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            margin-right: 4px;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color), #2E6B7D);
            color: white;
            padding: 80px 0;
            margin-bottom: 40px;
        }

        .search-box {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
        }

        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 40px 0 20px;
            margin-top: 60px;
        }

        .footer a {
            color: #bdc3c7;
            text-decoration: none;
        }

        .footer a:hover {
            color: white;
        }

        .language-switcher .dropdown-toggle::after {
            display: none;
        }

        .deadline-card {
            border-left: 4px solid var(--secondary-color);
        }

        .deadline-urgent {
            border-left-color: #dc3545;
        }

        .feature-tag {
            display: inline-block;
            background-color: #f0f0f0;
            color: #666;
            padding: 4px 12px;
            border-radius: 16px;
            font-size: 0.85rem;
            margin: 4px;
        }

        .district-card {
            cursor: pointer;
            transition: all 0.2s;
        }

        .district-card:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .alert-floating {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-mortarboard-fill me-2"></i>{{ __('messages.app_name') }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            {{ __('messages.home') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('kindergartens.*') ? 'active' : '' }}" href="{{ route('kindergartens.index') }}">
                            {{ __('messages.kindergartens') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('deadlines.*') ? 'active' : '' }}" href="{{ route('deadlines.index') }}">
                            {{ __('messages.deadlines') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                            {{ __('messages.about') }}
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <!-- Language Switcher -->
                    <li class="nav-item dropdown language-switcher">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-globe me-1"></i>
                            {{ config('app.available_locales')[app()->getLocale()] ?? 'Language' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @foreach(config('app.available_locales') as $locale => $name)
                                <li>
                                    <a class="dropdown-item {{ app()->getLocale() === $locale ? 'active' : '' }}" 
                                       href="{{ route('language.switch', $locale) }}">
                                        {{ $name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>

                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('favorites.index') }}">
                                <i class="bi bi-heart-fill text-danger"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('favorites.index') }}">{{ __('messages.favorites') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile') }}">{{ __('messages.profile') }}</a></li>
                                @if(auth()->user()->isAdmin())
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">{{ __('messages.admin_panel') }}</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">{{ __('auth.logout') }}</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('auth.login') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm ms-2" href="{{ route('register') }}">{{ __('auth.register') }}</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show alert-floating" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show alert-floating" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show alert-floating" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show alert-floating" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="bi bi-mortarboard-fill me-2"></i>{{ __('messages.app_name') }}</h5>
                    <p class="text-muted">{{ __('messages.hero_subtitle') }}</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>{{ __('messages.kindergartens') }}</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('kindergartens.index') }}">{{ __('messages.search') }}</a></li>
                        <li><a href="{{ route('deadlines.index') }}">{{ __('messages.deadlines') }}</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h6>{{ __('messages.home') }}</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('about') }}">{{ __('messages.about') }}</a></li>
                        <li><a href="{{ route('contact') }}">{{ __('messages.contact') }}</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6>{{ __('messages.language_preference') }}</h6>
                    <div class="btn-group" role="group">
                        @foreach(config('app.available_locales') as $locale => $name)
                            <a href="{{ route('language.switch', $locale) }}" 
                               class="btn btn-sm {{ app()->getLocale() === $locale ? 'btn-light' : 'btn-outline-light' }}">
                                {{ $name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">&copy; {{ date('Y') }} {{ __('messages.app_name') }}. {{ __('messages.all_rights_reserved') }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="me-3">{{ __('messages.privacy_policy') }}</a>
                    <a href="#">{{ __('messages.terms_of_service') }}</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Auto-hide alerts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('.alert-floating').forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>
</html>
