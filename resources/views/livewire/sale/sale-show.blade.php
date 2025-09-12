<x-card title="Ver venta">
    <x-slot:cardTools>

        <a href="{{ route('sales.list') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </x-slot:cardTools>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Factura: <b>FV-{{ $sale->id }}</b> </h3>
                    <div class="card-tools">
                        <!-- Buttons, labels, and many other things can be placed here! -->
                        <!-- Here is a label for example -->
                        <i class="fas fa-tshirt" title="Numero productos"></i>
                        <span class="badge badge-pill badge-primary mr-2">
                            {{ $sale->items->count() }}
                        </span>
                        <i class="fas fa-shopping-basket" title="Numero items"></i>
                        <span class="badge badge-pill badge-primary mr-2">
                            {{ $sale->items->sum('pivot.qty') }}
                        </span>
                        <i class="fas fa-clock" title="Fecha y hora de creacion"></i>
                        {{ $sale->created_at }}
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm table-striped text-center">

                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col"><i class="fas fa-image"></i></th>
                                    <th scope="col">Producto</th>
                                    <th scope="col">Precio venta</th>
                                    <th scope="col" width="15%">Qty</th>
                                    <th scope="col">Sub total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sale->orderDetails as $detail)
                                    <tr>
                                        <th scope="row">{{ $detail->id }}</th>
                                        <td>
                                            {{-- <img src="{{ asset($product->image) }}" width="50"
                                                class="img-fluid rounded"> --}}
                                                <i class="fas fa-image"></i>
                                        </td>
                                        <td>{{ $detail->product->name }}</td>
                                        <td>{{ money($detail->product->price) }}</td>
                                        <td>
                                            <span class="badge badge-pill badge-primary">
                                                {{ $detail->quantity }}
                                            </span>
                                        </td>
                                        <td>{{ money($detail->quantity * $detail->unitary_price) }}</td>
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
                                            <span class="badge badge-pill badge-secondary">
                                                {{ money($sale->total) }}
                                            </span>
                                        </h5>
                                    </td>

                                </tr>
                                <tr>

                                    <td colspan="7">
                                        <strong>Total en letras:</strong>
                                        {{ numeroLetras($sale->total) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

            </div>
            <!-- /.card -->

        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Vendedor</h3>
                    <div class="card-tools">

                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-hover table-sm table-striped text-center">

                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col"><i class="fas fa-image"></i></th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Perfil</th>
                                <th scope="col">Email</th>
                                <th scope="col">...</th>

                            </tr>

                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">{{ $sale->user->id }}</th>
                                <td>
                                    <x-image :item="$sale->user" />
                                </td>
                                <td>{{ $sale->user->name }}</td>
                                <td>{{ $sale->user->admin ? 'administrador' : 'vendedor' }}</td>

                                <td>{{ $sale->user->email }}</td>
                                <td>
                                    <a href="{{ route('user.show', $sale->user) }}" class="btn btn-success btn-xs">
                                        <i class="far fa-eye"></i>
                                    </a>
                                </td>


                            </tr>

                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->

            </div>
            <!-- /.card -->
        </div>
    </div>


    <x-slot:cardFooter>

    </x-slot>

</x-card>
