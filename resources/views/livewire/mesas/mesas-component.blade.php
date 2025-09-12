<div class="container-fluid">

    <x-card cardTitle="Listado de cabañas ({{ $this->mesasCount }})">
        <x-slot:cardTools>
            <button type="button" class="btn btn-primary" wire:click="$dispatch('create',{eventName:'createNewTable'})">
                <span>
                    <i class="fas fa-plus circle"></i>
                    Agregar mesa
                </span>
            </button>
        </x-slot:cardTools>

        <div class="row">
            @foreach ($mesas as $index => $table)
                <div class="col-lg-3 col-md-3 col-sm-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="text-center font-weight-bold">{{ $table->code }}</h5>
                        </div>
                        <figure class="text-center">
                            @if ($table->status == 'closed')
                                <img style="width: 40%; border-radius: 50%;" class="card-img-top mt-5"
                                    src="{{ asset('dist/img/cabinOpen1.png') }}" alt="Card image cap">
                            @else
                                <img style="width: 40%; border-radius: 50%;" class="card-img-top mt-5"
                                    src="{{ asset('dist/img/cabinClose1.png') }}" alt="Card image cap">
                            @endif
                        </figure>
                        <div class="card-body text-center">
                            <p class="card-text">
                                <span class="badge bg-info">
                                    {{ $table->status == 'closed' ? 'DISPONIBLE' : 'OCUPADA' }}
                                </span>
                            </p>
                            <div class="d-flex justify-content-between">
                                @if ($table->status == 'closed')
                                    <a class="btn btn-success w-100" wire:click="openTable({{ $table->id }})">Abrir
                                        Mesa</a>
                                @else
                                    <a class="btn btn-info w-100" wire:click="goToTable({{ $table->id }})">Regresar
                                        al detalle</a>
                                @endif

                                <a wire:click="$dispatch('delete', {id : {{ $table->id }}, eventName: 'deleteTable'})"
                                    class="btn btn-danger ml-2 float-right">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <x-slot:cardFooter>
            {{ $mesas->links() }}
        </x-slot:cardFooter>
    </x-card>
</div>
