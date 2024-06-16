<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-cart-plus"></i> Detalles venta </h3>
        <div class="card-tools">
            <!-- Conteo de productos -->
            <i class="fas fa-tshirt" title="Numero productos"></i>
            <span class="badge badge-pill bg-purple">0 </span>
            <!-- Conteo de articulos -->
            <i class="fas fa-shopping-basket ml-2" title="Numero items"></i>
            <span class="badge badge-pill bg-purple">0 </span>
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
                                <button class="btn btn-primary btn-xs">
                                    -
                                </button>
                                <span class="mx-1">{{ $product->qty }}</span>
                                <button class="btn btn-primary btn-xs">
                                    +
                                </button>
                            </td>
                            <td><b>C$ {{ number_format($product->qty * $product->price, 2) }}</b></td>
                            <td>
                                <!-- Boton para eliminar el producto del carrito -->
                                <button class="btn btn-danger btn-xs" title="Eliminar">
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
                        <td><h5>Total:</h5></td>
                        <td>
                            <h5>
                                <span class="badge badge-pill badge-secondary">C${{ number_format($cartTotal, 2) }}</span>
                            </h5>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="7">
                            <strong>Total en letras:</strong>
                            {{$this->numerosLetras($cartTotal)}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- end-card-body -->
</div>
