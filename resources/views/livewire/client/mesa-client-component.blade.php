<div class="container" style="margin-top: 2rem; max-width: 110rem;">
    <h3>Mesa:{{$this->table->code}}</h3>
    <div class="row">
        <div class="col-lg-5 col-md-5 col-sm-12">

            @include('mesas.cart_detail')

            @include('mesas.card_orders')
            <div>
                <p>Ordernar por</p>
                <select wire:model.live='category_id' class="form-select shadow-sm" name="" id="">
                    <option value="">--Seleccionar categoria--</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>



        </div>
        <div class="col-lg-7 col-md-7 col-sm-12 mt-2">
            @include('client.list_products')
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

        Echo.private('orders.' + mesaToken)
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
