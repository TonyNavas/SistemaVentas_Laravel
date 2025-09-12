<div>
    <x-card cardTitle="Listado ventas ({{ $this->SalesCount }})">
        <x-slot:cardTools>

            <div class="d-flex align-items-center">

                <span class="badge badge-info" style="font-size: 1.2rem;">
                    Total: {{money($this->totalVentas)}}
                </span>

                <div class="mx-3">
                    {{$this->dateInicio.'-'.$this->dateFin}}
                    <button class="btn btn-default" id="daterange-btn" wire:ignore>
                        <i class="far fa-calendar-alt"></i>
                        <span>
                            D-M-A - D-M-A
                        </span>
                        <i class="fas fa-caret-down"></i>
                    </button>
                </div>


                <a href="route({{ 'sales.create' }})" class="btn btn-primary" wire:click='create'>
                    <i class="fas fa-plus-circle"></i> Crear venta
                </a>

            </div>
        </x-slot:cardTools>

        <x-table>
            <x-slot:thead>
                <th>ID</th>
                <th>Cabaña/Mesa</th>
                <th>Total</th>
                <th>Productos</th>
                <th>Articulos</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </x-slot>

            @forelse ($sales as $sale)
                <tr>
                    <td>
                        <span class="badge badge-primary">
                            FV-{{ $sale->id }}
                        </span>
                    </td>
                    <td>{{ $sale->table->code }}</td>
                    <td>
                        <span class="badge badge-secondary">
                            {{ money($sale->total) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-pill bg-purple">
                            {{ $sale->orderDetails->count('product_id') }}
                        </span>

                    </td>
                    <td>
                        <span class="badge badge-pill bg-purple">
                            {{ $sale->orderDetails->sum('quantity') }}
                        </span>

                    </td>
                    <td>{{ $sale->fecha }}</td>

                    <td>
                        <div class="btn-group">
                            <a href="{{route('sales.invoice',$sale)}}" class="btn bg-navy btn-sm" title="Generar factura" target="_blank">
                                <i class="fas fa-print"></i>
                            </a>
                            <a a href="{{route('sales.show',$sale)}}" class="btn btn-sm btn-success">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="#" wire:click="edit({{ $sale->id }})" class="btn btn-sm btn-primary"
                                title="Editar" disabled>
                                <i class="fa fa-edit"></i>
                            </a>
                            <a wire:click="$dispatch('delete', {id : {{ $sale->id }},
                                eventName:'destroySale'})"
                                class="btn btn-sm btn-danger">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>

            @empty

                <tr class="text-center">
                    <td colspan="10">Sin registros</td>
                </tr>
            @endforelse

        </x-table>

        <x-slot:cardFooter>
            {{ $sales->links() }}

        </x-slot>
    </x-card>

    @section('styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}" />
    @endsection

    @section('js')
        <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
        <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

        <script>
            $('#daterange-btn').daterangepicker({
                    ranges: {
                        'Default': [moment().startOf('year'), moment()],
                        'Hoy': [moment(), moment()],
                        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Ultimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                        'Ultimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                        'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                        'Ultimos Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                            'month')]
                    },
                    startDate: moment().startOf('year'),
                    endDate: moment()
                },
                function(start, end) {
                    dateStart = start.format('YYYY-MM-DD');
                    dateEnd = end.format('YYYY-MM-DD');

                    $('#daterange-btn span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));

                    Livewire.dispatch('setDates', {fechaInicio: dateStart, fechaFinal: dateEnd});
                }

            );
        </script>
    @endsection

</div>
