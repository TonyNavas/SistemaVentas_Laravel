<div>
    <x-card cardTitle="Detalles de la categoria">
        <x-slot:cardTools>
            <a href="{{ route('category.index') }}" class="btn btn-primary">
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
                        <h2 class="profile-username text-center">{{ $category->name }}</h2>
                        <ul class="list-group mb-3">
                            <li class="list-group-item">
                                <b>Productos</b> <a class="float-right">{{$category->products->count()}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Articulos</b> <a class="float-right">0</a>
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
                        @foreach ($category->products as $product)
                            <tr>
                                <td>{{$product->id}}</td>
                                <td>
                                    <x-image :item="$product"/>
                                </td>
                                <td>{{$product->name}}</td>
                                <td>{!! $product->precio !!}</td>
                                <td>{!! $product->stockLabel !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-card>
</div>
