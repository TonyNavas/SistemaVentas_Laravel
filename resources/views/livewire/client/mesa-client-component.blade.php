<div class="container" style="margin-top: 2rem;">
    <h3>Mesa:{{ $this->table->code }}</h3>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="fas fa-user"></i>
            </span>
        </div>
        <input wire:model.live='client_name' type="text" class="form-control" placeholder="Introduce tu nombre"
            aria-label="Nombre del cliente">
    </div>

    <div class="row">

        <div class="col-lg-7 col-md-7 col-sm-12 mt-2">

            <div class="mb-2">
                <p>Ordernar por</p>
                <select wire:model.live='category_id' class="form-control">
                    <option value="">--Ordenar por categoria--</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                @include('client.list_products')
                <div wire:navigate class="mt-2">
                    {{ $products->links() }}
                </div>
            </div>

        </div>

        <div class="col-lg-5 col-md-5 col-sm-12">
            @include('client.cart_detail')
            @include('client.card_orders')
        </div>


    </div>
    <style>
        .bfc {
            object-fit: cover;
        }
    </style>
</div>

@section('js')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let mesaToken = @js($table->token);

            Echo.channel('orders.' + mesaToken)
                .listen('CreateOrder', (e) => {
                    console.log("Nueva orden recibida en esta mesa:", e);

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
