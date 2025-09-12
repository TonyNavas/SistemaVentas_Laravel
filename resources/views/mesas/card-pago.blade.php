<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-wallet"></i> Pago </h3>

        <div class="card-tools d-flex justify-content-center align-self-center">

            <span class="mr-2">Total: <b>{{ money($ordersTotal) }}</b></span>

            @livewire('mesas.currency', ['total' => $ordersTotal])

            <button wire:click="createSale" class="btn bg-success btn-sm ml-2">
                <i class="fas fa-money-bill-alt"></i>
                Pagar y cerrar mesa
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">

            <div class="col-6">
                <label for="pago">Pago:</label>
                <div class="input-group ">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            C$
                        </span>
                    </div>
                    <input type="number" wire:model.live="pago" class="form-control" id="pago"
                        min="{{ $ordersTotal }}">
                </div>
                <p>{{ NumeroLetras($pago) }}</p>
            </div>

            <div class="col-6">
                <label for="pago">Devuelve:</label>
                <div class="input-group ">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            C$
                        </span>
                    </div>
                    <input type="number" wire:model='cambio' class="form-control" min="0" readonly>
                </div>
                <p>{{ NumeroLetras($cambio) }}</p>
            </div>
        </div>
    </div>
</div>
