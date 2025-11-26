<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nota de Venta</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0 auto;
            padding: 0;
            width: 100%;
            max-width: 18cm;
        }
        .logo {
            width: 100%;
            margin-bottom: 20px;
            /* border: 1px solid #ddd; */
            /* background-color: #f9f9f9; */
            text-align: center;
            line-height: 100px;
            color: #999;
        }
        .header-container {
            width: 100%;
            margin-bottom: 20px;
        }
        .left, .right {
            width: 45%;
            display: inline-block;
            vertical-align: top;
        }
        .left {
            float: left;
        }
        .right {
            float: right;
        }
        .date-table, .document-info {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }
        .date-table th, .date-table td,
        .document-info td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
        .info-table, .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .info-table td {
            padding: 5px 0;
        }
        .products-table th, .products-table td {
            border: 1px solid #000;
            padding: 5px;
        }
        .products-table th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .total-row {
            font-weight: bold;
        }
        .total-cell {
            text-align: right;
            padding-right: 10px;
        }
        .signature {
            margin-top: 50px;
            width: 100%;
        }
        .sig-block {
            display: inline-block;
            width: 45%;
            text-align: center;
        }
        .underline {
            border-top: 1px solid #000;
            margin: 30px auto 5px auto;
            width: 80%;
        }
        .payment-option {
            display: inline-block;
            margin-right: 20px;
        }
        .checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            text-align: center;
            line-height: 12px;
            font-size: 10px;
            margin-right: 5px;
        }
        .clearfix {
            clear: both;
        }
    </style>
</head>
<body>

    <div class="logo">
        <img src="{{ $logo_base64 }}" alt="Logo">
    </div>

    <div class="header-container">
        <div class="left">
            <table class="date-table">
                <tr>
                    <th>DÍA</th>
                    <th>MES</th>
                    <th>AÑO</th>
                </tr>
                <tr>
                    <td>{{ $dia }}</td>
                    <td>{{ $mes }}</td>
                    <td>{{ $anio }}</td>
                </tr>
            </table>
        </div>

        <div class="right">
            <table class="document-info">
                <tr>
                    <td><strong>RUC:</strong></td>
                    <td>10198711921</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>NOTA DE VENTA</strong></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>N° {{ $num_nota }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="clearfix"></div>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Señor(es):</strong> {{ $comprador }}</td>
        </tr>
        <tr>
            <td><strong>Dirección:</strong> {{ $direccion }}</td>
        </tr>
        <tr>
            <td><strong>RUC:</strong> {{ $ruc }}</td>
        </tr>
        <tr>
            <td><strong>Celular:</strong> {{ $celular }}</td>
        </tr>
        <tr>
            <td>
                <strong>Condición de Pago:</strong>
                @php $status = strtolower($status); @endphp
                <span style="display: inline-block; margin-left: 10px;">
                    <span class="checkbox">{{ $status == 'al contado' ? 'X' : '' }}</span> Contado
                </span>
                <span style="display: inline-block; margin-left: 15px;">
                    <span class="checkbox">{{ $status == 'credito' ? 'X' : '' }}</span> Crédito     _______ días
                </span>
            </td>
        </tr>
    </table>

    <table class="products-table">
        <thead>
            <tr>
                <th>CANTIDAD</th>
                <th>DESCRIPCIÓN</th>
                <th>PRECIO UNITARIO</th>
                <th>IMPORTE</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detalles as $item)
                <tr>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: center;">{{ $item->descripcion }}</td>
                    <td style="text-align: center;">{{ number_format($item->sale_price, 2) }}</td>
                    <td style="text-align: center;">{{ number_format($item->sale_price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2" style="border-top: none; border-bottom: none; border-left: none;"></td>
                <td class="total-cell">TOTAL</td>
                <td style="text-align: center;">{{ $pre_total }}</td>
            </tr>
        </tbody>
    </table>

    </br>
    </br>
    <div class="signature">
        <div class="sig-block">
            <div class="underline"></div>
            <strong>Firma del Vendedor</strong>
        </div>
        <div class="sig-block">
            <div class="underline"></div>
            <strong>Recibí Conforme</strong>
        </div>
    </div>

</body>
</html>
