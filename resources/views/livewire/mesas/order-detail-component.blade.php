<div>
    <button wire:click="openModal" class="btn bg-purple btn-sm">
        <i class="fas fa-info-circle"></i> Ver detalle
    </button>

    <!-- Modal moneda -->
    <x-modal :modalId="'modalOrderDetail-'.$orderId" modalTitle="Detalles de la Orden #{{ $orderId }}" modalStyle="bg-purple"
        modalSize="modal-lg modal-dialog-centered">
        <div class="d-flex justify-content-center align-items-center flex-wrap">
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th><i class="far fa-image"></i></th>
                            <th>Estado</th>
                            <th>Producto</i></th>
                            <th><i class="fas fa-dollar-sign"></i></th>
                            <th>Qty</th>
                            <th>SubTotal</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $detail)
                            <tr>
                                <td><img src="{{ asset($detail->image) }}" width="50" class="img-fluid rounded">
                                </td>
                                <td><span class="badge bg-success">{{ $detail->status }}</span></td>
                                <td>{{ $detail->name }}</td>
                                <td>{{ money($detail->price) }}</td>
                                <td>x{{ $detail->quantity }}</td>
                                <td>{{ money($detail->subtotal) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Sin detalles</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-modal>
</div>
