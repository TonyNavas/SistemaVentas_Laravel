<x-modal modalId="modalRole" modalTitle="Roles de usuario" modalSize="modal-lg">
    <form wire:submit={{ $Id == 0 ? 'store' : "update($Id)" }}>
        <div class="form-row">

            {{-- Input name --}}
            <div class="form-group col-12">
                <label for="name">Nombre:</label>
                <input wire:model='name' type="text" class="form-control" value="{{ old('name') }}"
                    placeholder="Nombre del rol" id="name">
                @error('name')
                    <div class="alert bg-danger text-white bold text-center w-100 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-12">
                <p class="font-weight-bold text-sm">Permisos</p>

                <div class="container">
                    <ul class="list-unstyled permission-columns">
                        @foreach ($Allpermissions as $permission)
                            <li class="mb-2">
                                <div class="form-check">
                                    <input wire:model='permissions' class="form-check-input" type="checkbox" value="{{ $permission->id }}"
                                        @checked(in_array($permission->id, old('permissions', $rol->permissions->pluck('id')->toArray())))>
                                    <label class="form-check-label">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>

        <hr>
        <button wire:loading.attr = 'disabled' class="btn btn-dark float-end">{{ $Id == 0 ? 'Guardar' : 'Editar' }}
        </button>
    </form>
</x-modal>

@section('styles')
    <style>
        .permission-columns {
            columns: 1;
            /* Móviles (default) */
            -webkit-columns: 1;
            -moz-columns: 1;
        }

        @media (min-width: 768px) {

            /* md → tablets */
            .permission-columns {
                columns: 2;
                -webkit-columns: 2;
                -moz-columns: 2;
            }
        }

        @media (min-width: 1200px) {

            /* xl → desktops grandes */
            .permission-columns {
                columns: 4;
                -webkit-columns: 4;
                -moz-columns: 4;
            }
        }
    </style>
@endsection
