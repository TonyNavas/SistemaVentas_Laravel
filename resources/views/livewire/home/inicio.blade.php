<div>
    <x-card cardTitle="Bienvenid@s" cardFooter="">
        <x-slot:cardTools>

        @can('ver-ventas')
            <a href="{{ route('sales.list') }}" class="btn btn-primary">
                <i class="fas fa-shopping-cart"></i> Ir a ventas
            </a>
        @endcan

        @can('ver-mesa')
            <a href="{{ route('tables.index') }}" class="btn bg-purple">
                <i class="fas fa-cart-plus"></i> Crear venta
            </a>
        @endcan
        </x-slot:cardTools>

        {{-- Row Cards Ventas Hoy --}}
        @can('ver-ventasHoyReport')
            @include('home.row-cards-sales')
        @endcan

        {{-- Cajas de reportes --}}
        @can('ver-boxes-reporte')
            @include('home.cajas-reports')
        @endcan

        {{-- Card grafica --}}
        @can('ver-grafico-mes')
            @include('home.card-graph')
        @endcan

        {{-- Tablas reportes productos --}}
        @can('ver-tablas-reporte')
            @include('home.tables-report')
        @endcan

    </x-card>
</div>
