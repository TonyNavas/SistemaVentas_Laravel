<div class="container-fluid">

    <x-card cardTitle="Listado categorias ({{ $this->categoryCount }})">
        <x-slot:cardTools>
            <button type="button" class="btn btn-primary" wire:click="create">
                <span>
                    <i class="fas fa-plus circle"></i>
                    Crear
                </span>
            </button>
        </x-slot:cardTools>

        <x-table>
            <x-slot:thead>
                <th width="3%">ID</th>
                <th width="3%">Nombre</th>
                <th width="3%">Acciones</th>
            </x-slot:thead>

            @forelse ($categories as $index => $category)
            <tr wire:key="Category-{{$index}}" class="text-center">
                <td>{{$index}}</td>
                <td>{{$category->name}}</td>
                <td>
                    <div class="btn-group">
                        <a a href="{{route('category.show', $category)}}" class="btn btn-sm btn-dark">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="#"
                            wire:click="edit({{$category->id}})" class="btn btn-sm btn-primary">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a  wire:click="$dispatch('delete', {id : {{$category->id}},
                                eventName:'destroyCategory'})" class="btn btn-sm btn-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="5">Sin registros</td>
                </tr>
            @endforelse
        </x-table>

        <x-slot:cardFooter>
            {{ $categories->links() }}
        </x-slot:cardFooter>
    </x-card>

    <x-modal modalId="modalCategory" modalTitle="Categorias">
        <form wire:submit={{$Id == 0 ? "store" : "update($Id)"}}>
            <div class="form-row">
                <div class="form-group col-12">
                    <label for="name">Nombre:</label>
                    <input wire:model="name" type="text" class="form-control"
                        placeholder="Nombre categoria" id="name">
                    @error('name')
                        <div class="alert bg-danger text-white bold text-center w-100 mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>
            <button class="btn btn-dark float-end">{{$Id == 0 ? 'Guardar' : 'Editar'}}</button>
        </form>
    </x-modal>
</div>
