<div>
    <button wire:click="openModal" class="btn bg-purple btn-sm">
        <i class="far fa-keyboard"></i>
    </button>

    <!-- Modal moneda -->
    <x-modal modalId="modalCurrency" modalTitle="Pago" modalStyle="bg-purple">
        <div class="d-flex justify-content-center align-items-center flex-wrap">

            @foreach ($this->valores as $valor)
            <button wire:click="setPago({{$valor}})" type="button" class="btn btn-success m-1" {{$valor <= $total ? 'disabled' : ''}}>
                {{money($valor,2)}}
            </button>
            @endforeach

            <button wire:click="setPago({{$total}})" type="button" class="btn btn-success m-1">Exacto</button>
        </div>
    </x-modal>
</div>
