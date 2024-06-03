<x-modal modalId="modalProduct" modalTitle="Productos" modalSize="modal-lg">
    <form wire:submit={{ $Id == 0 ? 'store' : "update($Id)" }}>
        <div class="form-row">

            {{-- Input name --}}
            <div class="form-group col-md-7">
                <label for="name">Nombre:</label>
                <input wire:model='name' type="text" class="form-control" placeholder="Nombre producto" id="name">
                @error('name')
                    <div class="alert bg-danger text-white bold text-center w-100 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Select category --}}
            <div class="form-group col-md-5">
                <label for="category_id">Categoria:</label>
                <select wire:model='category_id' id="category_id" class="form-control">
                    <option value="0">Selecionar</option>
                    @foreach ($this->categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="alert bg-danger text-white bold text-center w-100 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Textarea description --}}
            <div class="form-group col-md-12">
                <label for="desc">Descripción:</label>
                <textarea wire:model='desc' id="desc" class="form-control" rows="3"></textarea>
                @error('desc')
                    <div class="alert bg-danger text-white bold text-center w-100 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input Precio compra --}}
            <div class="form-group col-md-4">
                <label for="precio_compra">Precio compra:</label>
                <input wire:model='precio_compra' min="0" step="any" type="number" class="form-control"
                    placeholder="Precio de compra" id="precio_compra">
                @error('precio_compra')
                    <div class="alert bg-danger text-white bold text-center w-100 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input precio venta --}}
            <div class="form-group col-md-4">
                <label for="precio_venta">Precio venta:</label>
                <input wire:model='precio_venta' min="0" step="any" type="number" class="form-control"
                    placeholder="Precio de venta" id="precio_venta">
                @error('precio_venta')
                    <div class="alert bg-danger text-white bold text-center w-100 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input codigo de barras --}}
            <div class="form-group col-md-4">
                <label for="codigo_barras">Codigo de barras:</label>
                <input wire:model='codigo_barras' type="text" class="form-control" placeholder="Codigo de barras"
                    id="codigo_barras">
                @error('codigo_barras')
                    <div class="alert bg-danger text-white bold text-center w-100 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input stock --}}
            <div class="form-group col-md-4">
                <label for="stock">Stock:</label>
                <input wire:model='stock' min="0" type="number" class="form-control"
                    placeholder="Stock del producto" id="stock">
                @error('stock')
                    <div class="alert bg-danger text-white bold text-center w-100 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input stock minimo --}}
            <div class="form-group col-md-4">
                <label for="stock_minimo">Stock minimo:</label>
                <input wire:model='stock_minimo' min="0" type="number" class="form-control"
                    placeholder="Stock minimo del producto" id="stock_minimo">
                @error('stock_minimo')
                    <div class="alert bg-danger text-white bold text-center w-100 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input stock minimo --}}
            <div class="form-group col-md-4">
                <label for="fecha_vencimiento">Fecha de vencimiento:</label>
                <input wire:model='fecha_vencimiento' type="date" class="form-control" id="fecha_vencimiento">
                @error('fecha_vencimiento')
                    <div class="alert bg-danger text-white bold text-center w-100 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Checkbox active --}}
            <div class="form-group col-md-3">
                <div class="icheck-primary">
                    <input wire:model='active' type="checkbox" id="active" checked>
                    <label for="active">
                        ¿Esta activo?
                    </label>

                </div>
                @error('active')
                    <div class="alert bg-danger text-white bold text-center w-100 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Checkbox imagen --}}
            <div class="form-group col-md-3">

                <label for="image">Imagen</label>
                <input wire:model='image' type="file" id="imagen" accept="image/*">

                @error('image')
                    <div class="alert bg-danger text-white bold text-center w-100 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Mostrar preview imagen --}}
            <div class="form-group col-md-6">

                @if ($Id > 0)
                    <x-image :item="$product= App\Models\Product::find($Id)" width="200" height="200" position="float-right"/>
                @endif

                @if ($this->image)
                    <img src="{{ $image->temporaryUrl() }}" class="rounded float-right img-fluid" width="200">
                @endif
            </div>

        </div>

        <hr>
        <button wire:loading.attr = 'disabled' class="btn btn-dark float-end">{{ $Id == 0 ? 'Guardar' : 'Editar' }}</button>
    </form>
</x-modal>
