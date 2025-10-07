<div class="container">
    <div class="input-group mb-3">
        <input wire:model.live='search' type="text" class="form-control shadow-sm p-2"
            placeholder="Buscar producto">
    </div>
    <ul class="list-group">
        @foreach ($products as $index => $product)
        <livewire:client.client-row-product :product="$product" :wire:key="$product->id">
        @endforeach
    </ul>
</div>
