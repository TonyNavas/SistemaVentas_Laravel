<tr>
    <td>{{$product->id}}</td>
    <td>
        <x-image :item="$product"/>
    </td>
    <td>{{$product->name}}</td>
    <td>{!! $product->precio !!}</td>
    <td>{!! $stockLabel !!}</td>
    <td>
        <button
            wire:click='addProduct({{$product->id}})'
            class="btn btn-primary btn-sm" title="Agregar"
            wire:loading.attr='disabled'
            wire:target='addProduct'>
            <i class="fas fa-plus-circle"></i>
        </button>
    </td>

</tr>
