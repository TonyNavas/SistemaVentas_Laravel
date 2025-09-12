<div class="card card-purple">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-cart-plus"></i> Ordenes </h3>
        <div class="card-tools">
            <!-- Conteo de productos -->
            <i class="fas fa-hamburger" title="Numero de productos"></i>
            <span class="badge badge-pill bg-purple">{{ $cart->count() }} </span>
            <!-- Conteo de articulos -->
            <i class="fas fa-shopping-basket ml-2" title="Cantidad de producto"></i>
            <span class="badge badge-pill bg-purple">{{ Cart::instance($this->table->code)->count() }}</span>
        </div>
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
                            <td><span class="badge bg-purple">Order-{{ $order->id }}</span></td>
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
