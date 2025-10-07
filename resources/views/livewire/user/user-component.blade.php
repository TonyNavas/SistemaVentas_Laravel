<div>
    <x-card cardTitle="Listado de usuarios ({{ $this->usersCount }})">
        <x-slot:cardTools>
            <a href="#" class="btn btn-primary" wire:click='create'>
                <i class="fas fa-plus-circle"></i> Crear usuario
            </a>
        </x-slot>

        <x-table>
            <x-slot:thead>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Perfil</th>
                <th>Estado</th>
                <th>Acciones</th>

            </x-slot>

            @forelse ($users as $index => $user)
                <tr wire:key='User-{{ $index }}'>
                    <td>{{ $user->id }}</td>
                    <td>
                        <x-image :item="$user" class="rounded-circle" />
                    </td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    @forelse ($user->getRoleNames() as $roleName)
                        <td>
                            <span class="badge bg-purple">
                                {{ $roleName }}
                            </span>
                        </td>
                    @empty
                        <td>
                            <span class="badge badge-danger">
                                Sin rol
                            </span>
                        </td>
                    @endforelse
                    <td>{!! $user->activeLabel !!}</td>
                    <td class="btn-group">
                        <a href="{{ route('user.show', $user) }}" class="btn btn-info btn-sm" title="Ver">
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="#" wire:click='edit({{ $user->id }})' class="btn btn-primary btn-sm"
                            title="Editar">
                            <i class="far fa-edit"></i>
                        </a>
                        <a wire:click="$dispatch('delete',{id: {{ $user->id }}, eventName:'destroyUser'})"
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
            {{ $users->links() }}

        </x-slot>
    </x-card>


    <x-modal modalId="modalUser" modalTitle="Usuarios">
        <form wire:submit={{ $Id == 0 ? 'store' : "update($Id)" }}>
            <div class="form-row">
                {{-- Input name --}}
                <div class="form-group col-md-6">
                    <label for="name">Nombre:</label>
                    <input wire:model='name' type="text" class="form-control" placeholder="Nombre" id="name">
                    @error('name')
                        <div class="alert alert-danger w-100 mt-2">{{ $message }}</div>
                    @enderror
                </div>
                {{-- Input email --}}
                <div class="form-group col-md-6">
                    <label for="email">Correo:</label>
                    <input wire:model='email' type="text" class="form-control" placeholder="tony@gmail.com"
                        id="email">
                    @error('email')
                        <div class="alert alert-danger w-100 mt-2">{{ $message }}</div>
                    @enderror
                </div>
                {{-- Input password --}}
                <div class="form-group col-md-6">
                    <label for="password">Contraseña:</label>
                    <input wire:model='password' type="password" class="form-control"
                        placeholder="Ingrese una contraseña" id="password">
                    @error('password')
                        <div class="alert alert-danger w-100 mt-2">{{ $message }}</div>
                    @enderror
                </div>
                {{-- Input re-password --}}
                <div class="form-group col-md-6">
                    <label for="re_password">Confirmar contraseña:</label>
                    <input wire:model='re_password' type="password" class="form-control"
                        placeholder="Repetir contraseña" id="re_password">
                    @error('re_password')
                        <div class="alert alert-danger w-100 mt-2">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Checkbox Active --}}
                <div class="form-group form-check col-md-6">
                    <div class="icheck-primary">
                        <input wire:model='active' type="checkbox" id="active">
                        <label class="form-check-label" for="active">Esta activo?</label>
                    </div>
                </div>


                {{-- Checkbox Admin --}}
                <div class="form-group col-md-6">
                    <label for="role">Rol</label>
                    <select wire:model="role" class="form-control" id="role">
                        <option value="">---Seleccionar rol---</option>
                        @foreach ($roles as $name => $label)
                            <option value="{{ $name }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>


                {{-- Input image --}}
                <div class="form-group col-md-6">
                    <label for="image">Imagen:</label><br>
                    <input wire:model='image' type="file" id="image" accept="image/*">
                </div>

                <div class="col-md-12">
                    @if ($Id > 0)
                        <div class="float-right">
                            <label class="text-muted bold">Imagen actual</label><br>
                            <x-image :item="$user = App\Models\User::find($Id)" width="100" height="100" position="img-fluid" />
                        </div>
                    @endif
                    @if ($this->image)
                        <div class="float-left">
                            <label class="text-muted bold">Nueva imagen</label><br>
                            <img src="{{ $image->temporaryUrl() }}" class="rounded img-fluid" width="100"
                                height="100">
                        </div>
                    @endif
                </div>

            </div>

            <hr>
            <button class="btn btn-primary float-right">{{ $Id == 0 ? 'Guardar' : 'Editar' }}</button>
        </form>
    </x-modal>

</div>
