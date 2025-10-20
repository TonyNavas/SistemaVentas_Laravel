<div class="mb-2">
    <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <p class="card-title"><i class="fas fa-cart-plus"></i>Tus pedidos </p>
        <div class="card-tools">
            <!-- Conteo de productos -->
            <i class="fas fa-hamburger" title="Numero de productos"></i>
            <span class="badge bg-primary">{{ $cart->count() }} </span>
            <!-- Conteo de articulos -->
            <i class="fas fa-shopping-basket ml-2" title="Cantidad de producto"></i>
            <span class="badge bg-primary">{{ Cart::instance($this->table->code)->count() }}</span>

                        <!-- Boton realizar pedido -->
<button
    wire:click='createOrder'
    wire:loading.attr="disabled"
    class="btn btn-primary ms-2"
>
    <span wire:loading.remove>
        <i class="fas fa-cart-plus"></i>
        Realizar pedido
    </span>
    <span wire:loading>
        <i class="fas fa-spinner fa-spin"></i>
        Procesando...
    </span>
</button>

        </div>
    </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Image</th>
                            <th scope="col">Producto</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Subtotal</th>
                            <th scope="col">...</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cart as $product)
                            <tr>
                                <td>
                                    <x-image :item="$product->options->image" />
                                </td>
                                <td>{{ $product->name }}</td>
                                <td><b>C${{ number_format($product->price, 2) }}</b></td>
                                <td>
                                    <!-- Botones para aumentar o disminuir la cantidad del producto en el carrito -->
                                    <div class="btn-group">
                                        <button wire:click='decrement({{ $product->id }})'
                                            class="btn btn-primary btn-sm mr-1" wire:loading.attr='disabled'
                                            wire:target='decrement'>
                                            <i class="fas fa-minus-circle"></i>
                                        </button>

                                        <span class="mx-1">{{ $product->qty }}</span>

                                        <button wire:click='increment({{ $product->id }})'
                                            class="btn btn-primary btn-sm ml-1" wire:loading.attr='disabled'
                                            wire:target='increment'
                                            {{ $product->qty >= $product->options->image->stock ? 'disabled' : '' }}>
                                            <i class="fas fa-plus-circle"></i>
                                        </button>
                                    </div>
                                </td>
                                <td><b>{{ money($product->qty * $product->price) }}</b></td>
                                <td>
                                    <!-- Boton para eliminar el producto del carrito -->
                                    <button class="btn btn-danger btn-sm" title="Eliminar"
                                        wire:click="removeItem({{ $product->id }}, {{ $product->qty }})">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr class="text-center">
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
                                    <span class="badge bg-secondary">
                                        {{ money($cartTotal) }}
                                    </span>
                                </h5>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
