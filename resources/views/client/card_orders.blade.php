<div class="card card-purple">
    <div class="card-header d-flex justify-content-between align-items-center">
        <p class="card-title"><i class="fas fa-cart-plus"></i>Tus pedidos </p>
    </div>
    <!-- card-body -->

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-sm table-striped text-center">
                <thead>
                    <tr>
                        <th scope="col">ID orden</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Total</th>
                        <th scope="col">Tiempo de pedido</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td><span class="badge bg-primary">Order-{{ $order->id }}</span></td>
                            <td><span class="badge bg-success">{{ $order->status }}</span></td>
                            <td>{{ money($order->total) }}</td>
                            <td>{{ $order->created_at->diffForHumans() }}</td>
                            <td>
                                @livewire('mesas.order-detail-component', ['id' => $order->id], key('order-detail-'.$order->id))
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">Sin Registros</td>
                        </tr>
                    @endforelse

                    <tr>
                        <td colspan="3"></td>
                        <td>
                            <h5>Total:</h5>
                        </td>
                        <td>
                            <h5>
                                <span class="badge badge-pill badge-secondary">{{ money($this->ordersTotal) }}</span>
                            </h5>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="10">
                            <strong>Total en letras:</strong>
                            {{ numeroLetras($ordersTotal) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- end-card-body -->
</div>
