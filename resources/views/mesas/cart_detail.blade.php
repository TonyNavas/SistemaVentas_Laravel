<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-cart-plus"></i> Carrito de pedidos </h3>
        <div class="card-tools">
            <!-- Conteo de productos -->
            <i class="fas fa-hamburger" title="Numero de productos"></i>
            <span class="badge badge-pill bg-purple">{{ $cart->count() }} </span>
            <!-- Conteo de articulos -->
            <i class="fas fa-shopping-basket ml-2" title="Cantidad de producto"></i>
            <span class="badge badge-pill bg-purple">{{ Cart::instance($this->table->code)->count() }}</span>

            <!-- Boton realizar pedido -->
            <button wire:click='createOrder' class="btn bg-purple ml-2">
                <i class="fas fa-cart-plus"></i>
                Realizar pedido
            </button>
        </div>
    </div>
    <!-- card-body -->
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-sm table-striped text-center">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"><i class="fas fa-image"></i></th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Precio.vt</th>
                        <th scope="col" width="15%">Qty</th>
                        <th scope="col">Sub total</th>
                        <th scope="col">...</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cart as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                <x-image :item="$product->options->image" />
                            </td>
                            <td>{{ $product->name }}</td>
                            <td><b>C${{ number_format($product->price, 2) }}</b></td>
                            <td>
                                <!-- Botones para aumentar o disminuir la cantidad del producto en el carrito -->
                                <div class="btn-group">
                                    <button wire:click='decrement({{ $product->id }})'
                                        class="btn btn-primary btn-xs mr-1" wire:loading.attr='disabled'
                                        wire:target='decrement'>
                                        <i class="fas fa-minus-circle"></i>
                                    </button>

                                    <span class="mx-1">{{ $product->qty }}</span>

                                    <button wire:click='increment({{ $product->id }})'
                                        class="btn btn-primary btn-xs ml-1" wire:loading.attr='disabled'
                                        wire:target='increment'
                                        {{ $product->qty >= $product->options->image->stock ? 'disabled' : '' }}>
                                        <i class="fas fa-plus-circle"></i>
                                    </button>
                                </div>
                            </td>
                            <td><b>{{ money($product->qty * $product->price) }}</b></td>
                            <td>
                                <!-- Boton para eliminar el producto del carrito -->
                                <button class="btn btn-danger btn-xs" title="Eliminar"
                                    wire:click="removeItem({{ $product->id }}, {{ $product->qty }})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">Sin Registros</td>
                        </tr>
                    @endforelse

                    <tr>
                        <td colspan="4"></td>
                        <td>
                            <h5>Total:</h5>
                        </td>
                        <td>
                            <h5>
                                <span class="badge badge-pill badge-secondary">{{ money($cartTotal) }}</span>
                            </h5>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="7">
                            <strong>Total en letras:</strong>
                            {{ numeroLetras($cartTotal) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- end-card-body -->
</div>
