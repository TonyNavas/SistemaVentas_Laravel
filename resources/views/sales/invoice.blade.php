<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Factura venta</title>

    <style>
        body {
            /* font-family: Arial, sans-serif; */
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .ticket {
            width: 100%;
            text-align: center;
        }

        .logo {
            margin: 5px 0;
        }

        .titulo {
            font-size: 14px;
            font-weight: bold;
        }

        hr {
            border: none;
            border-top: 1px dotted black;
            margin: 5px 0;
        }


        .productos{
            text-align: center;
        }

        .productos th{
        border-bottom: 1px dotted black;
        }

        .productos tr:nth-child(even){
            background: #f3e9e9;
        }

        th,td {
            padding: 3px;
            font-size: 11px;
        }

        .badge{
            background-color: #5256598d;
            color: white;
            padding: 3px;
            border-radius: 100%;
            font-weight: bold;
            width: 10px;
            margin: 0 auto;
        }

        .total {
            font-weight: bold;
            font-size: 13px;
            border-top: 1px solid black;
        }

    </style>
</head>

<body>
    <div class="ticket">
        <!-- Logo -->
        <div class="logo">
            <img src="{{ public_path('logo.png') }}" width="200">
        </div>

        <!-- Encabezado -->
        <div class="titulo"></div>
        <b>TEL: 8956-0820</b>
        <div>Direccion: Siuna, barrio dolores, 3KM al norte, vía hacia campo Uno.</div>
        <div>elcortez@gmail.com</div>
        <hr>

        <!-- Datos factura -->
        <div><strong>Factura:</strong><span>FV-{{ $sale->id }}</span>
        <strong>Fecha:</strong>{{ $sale->fecha }}</div>
        <hr>
        <strong>Cliente:</strong>{{ $sale->table->client_name }}</div>
        <hr>

        <!-- Productos -->
        <table width="100%" class="productos">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cant</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sale->orderDetails as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ money($item->price) }}</td>
                        <td class="td-item">
                            <span>
                                {{ $item->quantity }}
                            </span>
                        </td>
                        <td>{{money($item->subtotal)}}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Sin registros</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <hr>

        <!-- Totales -->
        <div class="total">TOTAL A PAGAR:{{money($sale->total)}}</div>
        <hr>

        <!-- Pie -->
        <div><small>ORIGINAL: CLIENTE | COPIA: EMISOR</small></div>
        <div style="margin-top:20px;">_______________________<br>
            <div>{{$sale->user->name}}</div>
            <strong>Vendedor</strong>
        </div>

        <p style="margin-top: 2rem;">*Muchas gracias por tu visita!*</p>

    </div>
</body>

</html>

