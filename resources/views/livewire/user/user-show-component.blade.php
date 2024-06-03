<div>
    <x-card cardTitle="Detalles del usuario">
        <x-slot:cardTools>
            <a href="{{ route('user.index') }}" class="btn btn-primary">
                <span>
                    <i class="fas fa-arrow-alt-circle-left"></i>
                    Regresar
                </span>
            </a>
        </x-slot:cardTools>

        <div class="row">
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <x-image :item="$user" width="150" height="150" class="img-thumbnail img-fluid rounded-circle"/>
                        </div>
                        <h2 class="profile-username text-center">{{ $user->name }}</h2>
                        <p class="text-muted text-center">{{ $user->admin ? 'Administrador' : 'Venderdor' }}</p>
                        <ul class="list-group mb-3">
                            <li class="list-group-item">
                                <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Estado</b> <a class="float-right">{!! $user->activeLabel !!}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Creado</b> <a class="float-right">{{$user->created_at->diffForHumans()}}</a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="col-md-8">
                <table class="table table-sm table-striped text-center">
                    <thead class="bg-primary">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Producto</th>
                            <th>Precio venta</th>
                            <th>Stock</th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- @foreach ($category->products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <x-image :item="$product" />
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{!! $product->precio !!}</td>
                                <td>{!! $product->stockLabel !!}</td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </x-card>
</div>
