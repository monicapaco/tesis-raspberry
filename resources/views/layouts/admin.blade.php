<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome.css') }}">

    <title>@yield('title', 'Vraem Motors Joshe - Admin')</title>

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
    </style>
</head>

<body>

    <div class="d-flex">

        {{-- SIDEBAR --}}
        <aside class="sidebar p-3">

            <h4 class="sidebar-title text-white mb-4">
                Admin Panel
            </h4>

            <ul class="nav flex-column">

                {{-- ALMACEN --}}
                <li>
                    <a class="nav-link" data-bs-toggle="collapse" href="#almacenMenu">
                        <i class="fa fa-laptop"></i>
                        <span>Almacén</span>
                    </a>

                    <div class="submenu" id="almacenMenu">
                        <a href="{{ url('almacen/categoria') }}" class="nav-link">Categorías</a>
                        <a href="{{ url('almacen/articulo') }}" class="nav-link">Inventario</a>
                    </div>
                </li>

                {{-- COMPRAS --}}
                <li>
                    <a class="nav-link" data-bs-toggle="collapse" href="#comprasMenu">
                        <i class="fa fa-th"></i>
                        <span>Compras</span>
                    </a>

                    <div class="submenu" id="comprasMenu">
                        <a href="{{ url('compras/proveedor') }}" class="nav-link">Proveedores</a>
                        <a href="{{ url('compras/ingreso') }}" class="nav-link">Ingresos</a>
                    </div>
                </li>

                {{-- VENTAS --}}
                <li>
                    <a class="nav-link" data-bs-toggle="collapse" href="#ventasMenu">
                        <i class="fa fa-shopping-cart"></i>
                        <span>Ventas</span>
                    </a>

                    <div class="submenu" id="ventasMenu">
                        <a href="{{ url('ventas/transporte') }}" class="nav-link">Transporte</a>
                        <a href="{{ url('ventas/cliente') }}" class="nav-link">Clientes</a>
                        <a href="{{ url('ventas/venta') }}" class="nav-link">Ventas</a>
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

                    <span>{{ Auth::user()->name }}</span>

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
