<div>
    <form>
        <div class="input-group">
            <input wire:model.live='search' type="search" class="form-control" placeholder="Buscar Producto...">
            <div class="input-group-append">
                <button class="btn btn-default" wire:click.prevent>
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <ul class="list-group" id="list-search">
        @foreach ($products as $product)
            <li class="list-group-item">
                <h6>
                    <a href="{{route('product.show', $product)}}" class="text-muted">
                        <x-image :item="$product" width="50" height="35" />
                        {{ Str::limit($product->name, 16, '...') }}
                    </a>
                </h6>
                <div class="d-flex justify-content-between">
                    <div class="mr-2">
                        Precio venta:
                        <span class="badge badge-pill badge-primary">
                            {!! $product->precio !!}
                        </span>
                    </div>
                    <div>
                        Stock:
                        {!! $product->stockLabel !!}
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
