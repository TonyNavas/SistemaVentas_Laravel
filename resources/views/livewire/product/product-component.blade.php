<div class="container-fluid">

    <x-card cardTitle="Listado productos ({{$this->productCount}})">
        <x-slot:cardTools>
            <button type="button" class="btn btn-primary" wire:click="create">
                <span>
                    <i class="fas fa-plus circle"></i>
                    Crear producto
                </span>
            </button>
        </x-slot:cardTools>

        <x-table>
            <x-slot:thead>
                <th>ID</th>
                <th>Image</th>
                <th>Nombre</th>
                <th>Precio venta</th>
                <th>Stock</th>
                <th>Categoria</th>
                <th>Estado</th>
                <th>Acciones</th>
            </x-slot:thead>

            @forelse ($products as $index => $product)
            <tr wire:key="Category-{{$index}}" class="text-center">
                <td>{{$index}}</td>
                <td>
                    <x-image :item="$product"/>
                </td>
                <td>{{$product->name}}</td>
                <td>{!! $product->precio !!}</td>
                <td>{!! $product->stockLabel !!}</td>
                <td>
                    <a class="badge badge-secondary" href="{{route('category.show', $product->category->id)}}">{{$product->category->name}}</a>
                </td>
                <td>{!! $product->activeLabel !!}</td>
                <td>
                    <div class="btn-group">
                        <a a href="{{route('product.show',$product)}}" class="btn btn-sm btn-info">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a wire:click="edit({{$product->id}})" class="btn btn-sm btn-primary">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a wire:click="$dispatch('delete', {id : {{$product->id}},
                                eventName:'destroyProduct'})" class="btn btn-sm btn-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
                <tr class="text-center">
                    <td colspan="8">Sin registros</td>
                </tr>
            @endforelse
        </x-table>

        <x-slot:cardFooter>
            {{ $products->links() }}
        </x-slot:cardFooter>
    </x-card>

    @include('products.modal')
</div>
