<div class="container-fluid">

    <x-card cardTitle="{{$table->code}}" cardTitleSize="display-1">
        <x-slot:cardTools>
            <a class="btn btn-danger mr-2" wire:click="closeTable({{$table->id}})">
                <span><i class="fas fa-times-circle"></i> Cerrar mesa</span>
            </a>
            <a class="btn btn-primary" wire:click="create">
                <span><i class="fas fa-plus circle"></i> Crear</span>
            </a>
        </x-slot:cardTools>

        {{-- Contenido principal --}}
        <div class="row">
            {{-- Detalles de la mesa --}}
            <div class="col-md-6">
                @include('mesas.cart_detail')
            </div>
            {{-- Lista de productos --}}
            <div class="col-md-6">
                @include('mesas.list_products')
            </div>
        </div>

        <x-slot:cardFooter>

        </x-slot:cardFooter>
    </x-card>
</div>
