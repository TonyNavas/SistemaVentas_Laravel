<div>
    <x-card cardTitle="Listado de roles (0)">
        <x-slot:cardTools>
            <button type="button" class="btn btn-primary" wire:click="create">
                <span>
                    <i class="fas fa-plus circle"></i>
                    Crear nuevo rol
                </span>
            </button>
        </x-slot:cardTools>

        <x-table>
            <x-slot:thead>
                <th>ID</th>
                <th>Nombre</th>
                <th>Usuarios con este rol</th>
                <th>Permisos</th>
                <th>...</th>
            </x-slot:thead>

            @forelse ($roles as $rol)
                <tr>
                    <td>{{ $rol->id }}</td>
                    <td>{{ $rol->name }}</td>
                    <td>
                        <span class="badge badge-pill badge-success">
                            {{ $rol->users_count }}
                        </span>
                    </td>
                    <td class="text-wrap" style="width: 50%">
                        <div class="d-flex flex-wrap">
                            @forelse ($rol->permissions as $permiso)
                                <span class="badge bg-primary ml-1 mb-1">{{ $permiso->name }}</span>
                            @empty
                                <span class="text-muted">Sin permisos asignados</span>
                            @endforelse
                        </div>
                    </td>


                    <td class="btn-group">
                        <a href="" class="btn btn-success btn-sm" title="Ver">
                            <i class="far fa-eye"></i>
                        </a>
                        <a wire:click='edit({{ $rol->id }})' class="btn btn-primary btn-sm" title="Editar">
                            <i class="far fa-edit"></i>
                        </a>
                        <a wire:click="$dispatch('delete',{id: {{ $rol->id }}, eventName:'destroyRol'})"
                            class="btn btn-danger btn-sm" title="Eliminar">
                            <i class="far fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>

            @empty
                <tr class="text-center">
                    <td colspan="6">Sin registros</td>
                </tr>
            @endforelse
        </x-table>
        <x-slot:cardFooter>
            {{ $roles->links() }}

        </x-slot>
    </x-card>

    @include('admin.role-modal')

    @section('styles')
    @endsection

    @section('js')
    @endsection

</div>
