<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>My App</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <style>
            body {
                min-height: 100vh;
                background-color: #f8f9fa;
            }
            .sidebar {
                min-height: 100vh;
                border-right: 1px solid #dee2e6;
            }
            .sidebar .nav-link {
                color: #333;
                font-size: 1rem;
            }
            .sidebar .nav-link.active, .sidebar .nav-link:hover {
                background-color: #e9ecef;
                color: #0d6efd;
            }
            .sidebar .bi {
                font-size: 1.2rem;
                vertical-align: middle;
            }
            .main-content {
                padding-top: 24px;
            }
        </style>
    </head>
    <body>
        <div id="app">
            <div class="main-wrapper">

<header>
    @include('shared.header')
</header>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar position-fixed" style="height:100vh; z-index: 1020;">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="#" class="nav-link">
                            <span class="bi bi-speedometer2 me-2"></span>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                            <span>
                                <a href="{{ route('cash-flows.index') }}" class="nav-link">
                                <span class="bi bi-cash-stack me-2"></span>
                                Kas
                                </a>
                            </span>                        
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ route('inventories.index') }}" class="nav-link">
                              <span>
                                <span class="bi bi-box-seam me-2"></span>
                                Inventaris
                            </span>
                            
                        </a>
                        
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#menuCollapse3" role="button" aria-expanded="false" aria-controls="menuCollapse3">
                            <span>
                                <span class="bi bi-gear-wide-connected me-2"></span>
                                Produksi
                            </span>
                            <span class="bi bi-chevron-down"></span>
                        </a>
                        <div class="collapse" id="menuCollapse3">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-3">
                                <li>
                                    <a href="#" class="nav-link">
                                        <span class="bi bi-journal-text me-2"></span>
                                        Proses produksi
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="nav-link">
                                        <span class="bi bi-box2 me-2"></span>
                                        Stok barang
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="nav-link">
                                        <span class="bi bi-calculator me-2"></span>
                                        Estimasi Bahan
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="nav-link">
                                        <span class="bi bi-currency-dollar me-2"></span>
                                        Kalkulasi HPP
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="nav-link">
                                        <span class="bi bi-file-earmark-bar-graph me-2"></span>
                                        Laporan HPP per Produk
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#menuCollapse4" role="button" aria-expanded="false" aria-controls="menuCollapse4">
                            <span>
                                <span class="bi bi-clipboard-data me-2"></span>
                                Laporan
                            </span>
                            <span class="bi bi-chevron-down"></span>
                        </a>
                        <div class="collapse" id="menuCollapse4">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-3">
                                <li>
                                    <a href="#" class="nav-link">
                                        <span class="bi bi-cash-stack me-2"></span>
                                        Laporan Kas
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="nav-link">
                                        <span class="bi bi-box-seam me-2"></span>
                                        Laporan Inventaris
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="nav-link">
                                        <span class="bi bi-gear-wide-connected me-2"></span>
                                        Laporan Produksi
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="nav-link">
                                        <span class="bi bi-currency-dollar me-2"></span>
                                        Laporan HPP
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item mt-4">
                        <a href="" class="nav-link text-danger">
                            <span class="bi bi-box-arrow-right me-2"></span>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content" style="margin-left:16.666667%;">
            @yield('content')
        </main>
    </div>
</div>
@include('shared.footer')
