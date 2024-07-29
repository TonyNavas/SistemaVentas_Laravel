<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-wallet"></i> Pago </h3>

        <div class="card-tools d-flex justify-content-center align-self-center">

            <span class="mr-2">Total: <b>C${{ $cartTotal }}</b></span>

            @livewire('mesas.currency', ['cartTotal' => $cartTotal])
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

                    <input type="number" wire:model.live="pago" class="form-control" id="pago" min="{{$cartTotal}}">

                </div>
                <p>{{$this->numerosLetras($pago)}}</p>
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
                <p>{{$this->numerosLetras($cambio)}}</p>
            </div>
        </div>
    </div>
</div>
