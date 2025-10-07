<div class="container-fluid">

    <x-card
        cardTitle="Ordenes pendientes ({{ $PendingOrders }}) - Total de productos pendientes ({{ $pendingProducts }})">
        <x-slot:cardTools>
            <button type="button" class="btn btn-primary" wire:click="create">
                <span>
                    <i class="fas fa-plus circle"></i>
                    Crear producto
                </span>
            </button>
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
                                                <a wire:click='chancheProductStatus({{ $detail->id }},)'
                                                    class="btn btn-sm btn-primary">{{ $detail->status }}</a>
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
                                <a wire:click='chancheOrderStatus({{ $order->id }})'
                                    class="btn btn-primary btn-block">
                                    {{ $order->status }}
                                </a>
                            </div>
                    </div>
                </div>

            @empty
                <p>No hay órdenes</p>
            @endforelse
        </div>
        <x-slot:cardFooter>
            Footer
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
                        timer: 2000
                    });
                })

                .listen('ChangeOrderStatus', (e) => {
                    console.log("Cambio de estado en cocina:", e);
                });
        });
    </script>
@endsection
