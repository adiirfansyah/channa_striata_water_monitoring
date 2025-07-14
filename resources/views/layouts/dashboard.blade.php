<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Monitoring App</title>

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @stack('styles')

    <style>
        /* =================================
           Variabel Warna & Pengaturan Dasar
           ================================= */
        :root {
            --bs-primary: #435EBE;
            --bs-primary-rgb: 67, 94, 190;
            --sidebar-bg: #FFFFFF;
            --sidebar-link-color: #6c757d;
            --sidebar-link-active-color: var(--bs-primary);
            --sidebar-link-hover-color: var(--bs-primary);
            --main-bg: #F0F2F5;
            --card-shadow: 0 0.5rem 1.5rem rgba(0,0,0, .07);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--main-bg);
            color: #212529;
        }
        
        /* =================================
           Layout Sidebar Modern
           ================================= */
        .sidebar {
            background-color: var(--sidebar-bg);
            border-right: 1px solid #e9ecef;
            box-shadow: 0 0 2rem rgba(0,0,0, .05);
            transition: margin-left .3s ease-in-out;
        }

        .sidebar .position-sticky {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        
        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            padding: 1.5rem 1.5rem;
            color: var(--bs-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .sidebar-brand i {
            margin-right: 0.75rem;
            font-size: 1.8rem;
        }
        
        /* [DIUBAH] Menghapus flex-grow agar menu logout tidak terdorong ke bawah */
        .sidebar .nav-flex-column {
           /* flex-grow: 1; */
        }

        .sidebar .nav-link {
            color: var(--sidebar-link-color);
            font-weight: 500;
            padding: 0.8rem 1.5rem;
            margin: 0.2rem 0;
            display: flex;
            align-items: center;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }
        
        .sidebar .nav-link i {
            margin-right: 1rem;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            color: var(--sidebar-link-hover-color);
            background-color: rgba(var(--bs-primary-rgb), 0.05);
        }

        .sidebar .nav-link.active {
            color: var(--sidebar-link-active-color);
            background-color: rgba(var(--bs-primary-rgb), 0.1);
            border-left: 3px solid var(--bs-primary);
        }
        
        /* [DIHAPUS] CSS untuk User Profile sudah tidak diperlukan lagi */
        
        .main-content {
            padding: 1.5rem;
        }

        /* =================================
           Responsif
           ================================= */
        @media (max-width: 767.98px) {
            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 1030;
                margin-left: -100%;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky">
                    <a href="{{ route('dashboard') }}" class="sidebar-brand">
                        <i class="bi bi-droplet-fill"></i>
                        <span>AquaTrack </span>
                    </a>
                    
                    <ul class="nav flex-column nav-flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-grid-1x2-fill"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard.monitoring') ? 'active' : '' }}" href="{{ route('dashboard.monitoring') }}">
                                <i class="bi bi-activity"></i> Monitoring
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard.history') ? 'active' : '' }}" href="{{ route('dashboard.history') }}">
                                <i class="bi bi-clock-history"></i> History
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link" style="border: none; background: none; width: 100%; text-align: left; cursor: pointer;">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content Area -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-0">
                <!-- Konten Halaman Utama -->
                <main class="main-content">
                    @yield('dashboard-content')
                </main>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @stack('scripts')
</body>

</html>