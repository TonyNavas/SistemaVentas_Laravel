<div>
    <x-card cardTitle="Bienvenid@s" cardFooter="">
        <x-slot:cardTools>
            <a href="{{ route('sales.list') }}" class="btn btn-primary">
                <i class="fas fa-shopping-cart"></i> Ir a ventas
            </a>

            <a href="{{ route('tables.index') }}" class="btn bg-purple">
                <i class="fas fa-cart-plus"></i> Crear venta
            </a>
        </x-slot:cardTools>

        {{-- Row Cards Ventas Hoy --}}
        @include('home.row-cards-sales')

        {{-- Card grafica --}}

        @include('home.card-graph')
        {{ $listTotalVentasMes }}

        {{-- Cajas de reportes --}}

        @include('home.cajas-reports')

    </x-card>
</div>
