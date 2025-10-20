<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        @can('ver-inicio')
            <li class="nav-item d-none d-sm-inline-block">
            <a href="/" class="nav-link">
                <i class="nav-icon fas fa-home"></i> Inicio
            </a>
        </li>
        @endcan

        @can('ver-mesa')
                    <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('tables.index') }}" class="nav-link">
                <i class="nav-icon fas fa-cart-plus"></i> Crear venta
            </a>
        </li>
        @endcan

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->

        @can('buscar-productos')
        <li class="nav-item">
            @livewire('search-component')
        </li>
        @endcan


        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                @livewire('notification-badge')
            </a>

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="width: 20rem;">
                @livewire('notifications')
            </div>
        </li>


        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <img src="{{ auth()->user()->imagen }}" class="user-image img-circle elevation-2" alt="User Image">
                <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                <!-- User image -->
                <li class="user-header bg-lightblue">
                    <img src="{{ auth()->user()->imagen }}" class="img-circle elevation-2" alt="User Image">

                    <p>
                        {{ auth()->user()->name }}
                        <small>
                            @forelse (auth()->user()->getRoleNames() as $roleName)
                                {{ $roleName}}
                            @empty
                                Sin rol
                            @endforelse
                        </small>
                    </p>
                </li>
                <!-- Menu Body -->

                <!-- Menu Footer-->
                <li class="user-footer">
                    <a href="{{ route('user.show', auth()->user()->id) }}" class="btn btn-default btn-flat">Perfil</a>
                    <a class="btn btn-default btn-flat float-right" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        Salir
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

    </ul>
</nav>
