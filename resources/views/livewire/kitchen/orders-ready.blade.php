<div class="container-fluid">

    <x-card
        cardTitle="Listado de pedidos listos para entregar">
        <x-slot:cardTools>

        </x-slot:cardTools>
        <div class="row">
            @forelse ($orders as $order)
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column"">
                            <h5>Orden#{{ $order->id }} - Mesa{{ $order->table->code }}</h5>
                            <span>{{ $order->created_at->diffForHumans() }}</span>
                        </div>

                        <ul class="list-group list-group-flush">

                            <table class="table">
                                <tbody>
                                    @forelse ($order->details as $detail)
                                        <tr>
                                            <td><img src="{{ asset($detail->image) }}" width="50"
                                                    class="img-fluid rounded"></td>
                                            <td>{{ $detail->name }}</td>
                                            <td>x{{ $detail->quantity }}</td>
                                            <td>
                                                @can('cambiar-estado-productos')
                                                    <a wire:click='chancheProductStatus({{ $detail->id }},)'
                                                        class="btn btn-sm btn-primary">{{ $detail->status }}
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">Sin registros</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="card-body mt-auto">
                                @can('cambiar-estado-orden-lista')
                                    <a wire:click='chancheOrderStatus({{ $order->id }})'
                                        class="btn btn-primary btn-block">
                                        {{ $order->status }}
                                    </a>
                                @endcan
                            </div>
                    </div>
                </div>

            @empty
                <p>No hay órdenes</p>
            @endforelse
        </div>
        <x-slot:cardFooter>

        </x-slot:cardFooter>
    </x-card>

</div>

@section('js')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            Echo.channel('orders') // canal público/global
                .listen('CreateOrder', (e) => {
                    console.log("Nueva orden para cocina:", e);

                    Swal.fire({
                        position: "center",
                        title: "Nuevo pedido recibido",
                        icon: "info",
                        text: "Ha llegado un nuevo pedido!",
                        showConfirmButton: false,
                        timer: 10000
                    });
                })

                .listen('ChangeOrderStatus', (e) => {
                    console.log("Cambio de estado en cocina:", e);
                });
        });
    </script>
@endsection
