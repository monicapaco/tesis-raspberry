<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Inventario</title>

    <style>
        @page {
            margin: 1.5cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        /* HEADER */
        .header {
            width: 100%;
            position: relative;
            margin-bottom: 25px;
        }

        .titulo {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
        }

        .fecha {
            position: absolute;
            right: 0;
            top: 0;
            font-size: 10px;
            color: #666;
        }

        /* TABLA */
        .products-table {
            width: 100%;
            border-collapse: collapse;
        }

        .products-table th,
        .products-table td {
            border: 1px solid #000;
            padding: 6px;
        }

        .products-table th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .nombre {
            font-weight: bold;
        }

        .descripcion {
            font-size: 10px;
            color: #777;
            margin-top: 2px;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">

        <div class="titulo">
            Inventario
        </div>

        <div class="fecha">
            Fecha generación: {{ $fechaGeneracion->format('d/m/Y H:i') }}
        </div>

    </div>


    <!-- TABLA -->
    <table class="products-table">

        <thead>
            <tr>
                <th>ID</th>
                <th>CODIGO</th>
                <th>NOMBRE</th>
                <th>STOCK</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($articulos as $item)
                <tr>

                    <td style="text-align:center;">
                        {{ $item->id }}
                    </td>

                    <td style="text-align:center;">
                        {{ $item->codevar }}
                    </td>

                    <td>

                        <div class="nombre">
                            {{ $item->name }}
                        </div>

                        @if ($item->description)
                            <div class="descripcion">
                                {{ $item->description }}
                            </div>
                        @endif

                    </td>

                    <td style="text-align:center;">
                        {{ $item->stock }}
                    </td>

                </tr>
            @endforeach
        </tbody>

    </table>

</body>

</html>
