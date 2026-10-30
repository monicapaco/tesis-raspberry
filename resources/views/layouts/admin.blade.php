<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome.css') }}">

    <title>@yield('title', 'Sistema - Admin')</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            background: #f5f6f8;
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #111;
        }

        .sidebar a {
            color: #d1d1d1;
        }

        .sidebar a:hover {
            background: #1c1c1c;
            color: white;
        }

        .sidebar .submenu a {
            padding-left: 2rem;
            font-size: 0.9rem;
        }

        .sidebar-group-title {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0.5rem 1rem;
            color: white;
            font-size: 1.08rem;
            font-weight: 600;
        }

        .sidebar-group-title i {
            width: 20px;
            text-align: center;
        }
    </style>
</head>

<body>

    @php
        $categoriaActive = request()->routeIs('categoria.*');
        $articuloActive = request()->routeIs('articulo.*');
        $proveedorActive = request()->routeIs('proveedor.*');
        $ingresoActive = request()->routeIs('ingreso.*');
        $transporteActive = request()->routeIs('transporte.*');
        $clienteActive = request()->routeIs('cliente.*');
        $ventaActive = request()->routeIs('venta.*');
    @endphp

    <div class="d-flex">

        {{-- SIDEBAR --}}
        <aside class="sidebar p-3">

            <h4 class="sidebar-title text-white mb-4">
                Admin Panel
            </h4>

            <ul class="nav flex-column">

                {{-- ALMACEN --}}
                <li>
                    <div class="sidebar-group-title">
                        <i class="fa fa-laptop"></i>
                        <span>Almacén</span>
                    </div>

                    <div class="submenu">
                        <a
                            href="{{ route('categoria.index') }}"
                            @class(['nav-link', 'active' => $categoriaActive])
                            @if ($categoriaActive) aria-current="page" @endif
                        >Categorías</a>
                        <a
                            href="{{ route('articulo.index') }}"
                            @class(['nav-link', 'active' => $articuloActive])
                            @if ($articuloActive) aria-current="page" @endif
                        >Inventario</a>
                    </div>
                </li>

                {{-- COMPRAS --}}
                <li>
                    <div class="sidebar-group-title">
                        <i class="fa fa-th"></i>
                        <span>Compras</span>
                    </div>

                    <div class="submenu">
                        <a
                            href="{{ route('proveedor.index') }}"
                            @class(['nav-link', 'active' => $proveedorActive])
                            @if ($proveedorActive) aria-current="page" @endif
                        >Proveedores</a>
                        <a
                            href="{{ route('ingreso.index') }}"
                            @class(['nav-link', 'active' => $ingresoActive])
                            @if ($ingresoActive) aria-current="page" @endif
                        >Ingresos</a>
                    </div>
                </li>

                {{-- VENTAS --}}
                <li>
                    <div class="sidebar-group-title">
                        <i class="fa fa-shopping-cart"></i>
                        <span>Ventas</span>
                    </div>

                    <div class="submenu">
                        <a
                            href="{{ route('transporte.index') }}"
                            @class(['nav-link', 'active' => $transporteActive])
                            @if ($transporteActive) aria-current="page" @endif
                        >Transporte</a>
                        <a
                            href="{{ route('cliente.index') }}"
                            @class(['nav-link', 'active' => $clienteActive])
                            @if ($clienteActive) aria-current="page" @endif
                        >Clientes</a>
                        <a
                            href="{{ route('venta.index') }}"
                            @class(['nav-link', 'active' => $ventaActive])
                            @if ($ventaActive) aria-current="page" @endif
                        >Ventas</a>
                    </div>
                </li>

            </ul>

        </aside>

        {{-- CONTENIDO --}}
        <div class="flex-grow-1">

            {{-- NAVBAR --}}
            <nav class="navbar navbar-light bg-white shadow-sm px-4">

                <button class="btn btn-dark hamburger active" id="toggleSidebar">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <div class="d-flex align-items-center gap-3">

                    {{-- <span>{{ Auth::user()->name }}</span> --}}

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm">
                            Cerrar sesión
                        </button>
                    </form>

                </div>
            </nav>

            <main class="p-4">
                @yield('contenido')
            </main>

        </div>

    </div>

    <script>
        const toggleBtn = document.getElementById("toggleSidebar");

        toggleBtn.addEventListener("click", function() {

            document.body.classList.toggle("sidebar-collapsed");
            this.classList.toggle("active");

        });

        if (document.body.classList.contains("sidebar-collapsed")) {
            toggleBtn.classList.add("active");
        }
    </script>

    @stack('scripts')

</body>

</html>
