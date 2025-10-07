<li class="list-group-item">
    <a href="" class="d-flex text-decoration-none">

        <figure>
            <x-image :item="$product" class="img-fluid ofc"
                style="width: 160px; max-width: 160px; height: 160px; max-height: 160px;" />
        </figure>
        <div class="ml-2">
            <h4 class="fw-bold text-muted">{{ $product->name }}</h4>
            <p style="color: gray;" class="mb-1">{{ Str::limit($product->desc, 60, '...') }}
            </p>
            <p style="color: gray;" class="mb-1">
                <span class="badge bg-info">{{ $product->category->name }}</span>

                {!! $stockLabel !!} Disponibles
            </p>

            <p class="mb-1">
                <span class="badge bg-success">{{ money($product->precio_venta) }}</span>
            </p>


        </div>
    </a>
            <button wire:click='addProduct({{$product->id}})' class="btn btn-primary btn-sm"
                title="Agregar" wire:loading.attr='disabled' wire:target='addProduct'>
                <i class="fas fa-plus-circle"></i>
                Agregar al carrito
            </button>
</li>
