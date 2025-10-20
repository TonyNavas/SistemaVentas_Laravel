<div class="container-fluid">
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
                <i class="fas fa-user"></i>
            </span>
        </div>
        <input wire:model.live='client_name' type="text" class="form-control" placeholder="Ingresa el nombre del cliente" aria-label="ClientName">
    </div>

    <x-card cardTitle="{{ $table->code }}" cardTitleSize="display-1">
        <x-slot:cardTools>
            @can('cerrar-mesa')
                <a class="btn btn-danger mr-2" wire:click="closeTable({{ $table->id }})">
                <span><i class="fas fa-ban"></i> Cerrar mesa</span>
            </a>
            @endcan

            <a href="{{ route('tables.index') }}" class="btn btn-primary">
                <span><i class="fas fa-chevron-left"></i> Regresar a mesas</span>
            </a>
        </x-slot:cardTools>

        {{-- Contenido principal --}}
        <div class="row">

            <div class="col-md-6">
                {{-- Detalles de la mesa --}}
                @include('mesas.cart_detail')

                {{-- Mesa card Orders --}}
                @include('mesas.card_orders')

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

@section('js')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let mesaToken = @js($table->token);

            Echo.private('orders.' + mesaToken)
                .listen('CreateOrder', (e) => {
                    console.log("Nueva orden enviada de esta mesa:", e);

                })
                .listen('ChangeOrderStatus', (e) => {
                    console.log("Estado del pedido actualizado:", e);

                    Swal.fire({
                        position: "center",
                        title: "Actualizacion de pedido",
                        icon: "info",
                        text: "Orden #" + e.order.id + " " + e.order.status,
                        showConfirmButton: false,
                        timer: 2000
                    });
                });
        });
    </script>
@endsection
