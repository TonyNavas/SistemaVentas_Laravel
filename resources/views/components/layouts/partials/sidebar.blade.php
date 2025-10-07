<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('inicio') }}" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">
            {{ config('app.name', 'ElCortes') }}
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ auth()->user()->imagen }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('user.show', auth()->user()->id) }}" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('inicio') }}" class="{{ Request::is('/') ? 'active' : '' }} nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Inicio
                        </p>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Dashboard
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" role="navigation" aria-label="Navigation 4">
                        <li class="nav-item">
                            <a href="../index.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Dashboard v1</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../index2.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Dashboard v2</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../index3.html" class="nav-link">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Dashboard v3</p>
                            </a>
                        </li>
                    </ul>
                </li> --}}

                <li class="nav-header">GENERAL</li>

                @can('read-sales')
                    <li class="nav-item">
                        <a href="{{ route('sales.list') }}" class="nav-link {{ Request::is('ventas') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>Listado de ventas</p>
                        </a>
                    </li>
                @endcan

                @can('read-categories')
                    <li class="nav-item">
                        <a href="{{ route('category.index') }}"
                            class="nav-link {{ Request::is('categorias') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th-large"></i>
                            <p>Categorias</p>
                        </a>
                    </li>
                @endcan

                @can('read-products')
                    <li class="nav-item">
                        <a href="{{ route('product.index') }}"
                            class="nav-link {{ Request::is('productos') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box-open"></i>
                            <p>Productos</p>
                        </a>
                    </li>
                @endcan

                @can('read-cabin')
                    <li class="nav-item">
                        <a href="{{ route('tables.index') }}" class="nav-link {{ Request::is('mesas') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-concierge-bell"></i>
                            <p>Mesas</p>
                        </a>
                    </li>
                @endcan

                @can('read-kitchen')
                    <li class="nav-item">
                        <a href="{{ route('kitchen.index') }}"
                            class="nav-link {{ Request::is('cocina') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-utensils"></i>
                            <p>Cocina</p>
                        </a>
                    </li>
                @endcan

                <li class="nav-header">CONFIGURACIÓN</li>

                @can('read-users')
                    <li class="nav-item">
                        <a href="{{ route('user.index') }}"
                            class="nav-link {{ Request::is('usuarios') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Usuarios</p>
                        </a>
                    </li>
                @endcan

                @can('read-roles')
                    <li class="nav-item">
                        <a href="{{ route('admin.roles.index') }}"
                            class="nav-link {{ Request::is('roles') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p>Roles</p>
                        </a>
                    </li>
                @endcan

                <li class="nav-item">
                    <a href="{{ route('user.index') }}" class="nav-link {{ Request::is('ajustes') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Ajustes</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>

</aside>
