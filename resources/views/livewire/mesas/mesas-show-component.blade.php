<div class="container-fluid">

    <x-card cardTitle="{{ $table->code }}" cardTitleSize="display-1">
        <x-slot:cardTools>
            <a class="btn btn-danger mr-2" wire:click="closeTable({{ $table->id }})">
                <span><i class="fas fa-ban"></i> Cerrar mesa</span>
            </a>
            <a href="{{ route('tables.index') }}" class="btn btn-primary">
                <span><i class="fas fa-chevron-left"></i> Regresar a mesas</span>
            </a>
        </x-slot:cardTools>

        {{-- Contenido principal --}}
        <div class="row">

            <div class="col-md-6">
                {{-- Detalles de la mesa --}}
                @include('mesas.cart_detail')

                {{-- Detalles de la mesa --}}
                @include('mesas.card-pago')
            </div>

            <div class="col-md-6">
                {{-- Lista de productos --}}
                @include('mesas.list_products')
            </div>
        </div>

        <x-slot:cardFooter>

        </x-slot:cardFooter>
    </x-card>
</div>
