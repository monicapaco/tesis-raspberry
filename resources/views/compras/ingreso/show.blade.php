@extends('layouts.admin')

@section('contenido')

<h3 class="mb-4 text-center fw-semibold">
    Detalle del Ingreso
</h3>

<div class="card shadow-sm border-0 rounded-4">

    <div class="card-body">

        {{-- ===== CABECERA COMPROBANTE ===== --}}
        <div class="row mb-4">

            <div class="col-md-6">
                <h5 class="fw-semibold mb-1">Proveedor</h5>
                <p class="text-muted mb-0">
                    {{ $ingreso->name }}
                </p>
            </div>

            <div class="col-md-6 text-md-end">
                <h5 class="fw-semibold mb-1">
                    {{ $ingreso->type_voucher }}
                </h5>

                <p class="text-muted mb-0">
                    {{ $ingreso->serial_voucher }} - {{ $ingreso->number_voucher }}
                </p>

                @php
                    $statusClass = [
                        'Credito' => 'bg-warning text-dark',
                        'Al contado' => 'bg-success',
                        'Anulado' => 'bg-secondary'
                    ];
                @endphp

                <span class="badge mt-2 {{ $statusClass[$ingreso->status] ?? 'bg-secondary' }}">
                    {{ $ingreso->status }}
                </span>
            </div>

        </div>

        <hr class="mb-4">

        {{-- ===== TABLA DETALLE ===== --}}
        <div class="table-responsive">

            <table class="table table-hover align-middle rounded-table">

                <thead class="table-header-soft">
                    <tr>
                        <th>Artículo</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-end">P. Compra</th>
                        <th class="text-end">P. Venta</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($detalles as $det)
                        <tr>
                            <td>{{ $det->articulo }}</td>

                            <td class="text-center">
                                {{ $det->quantity }}
                            </td>

                            <td class="text-end">
                                S/. {{ number_format($det->purchase_price, 2) }}
                            </td>

                            <td class="text-end">
                                S/. {{ number_format($det->sale_price, 2) }}
                            </td>

                            <td class="text-end fw-semibold">
                                S/. {{ number_format($det->purchase_price * $det->quantity, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr class="fw-bold">
                        <td colspan="4" class="text-end">
                            TOTAL
                        </td>
                        <td class="text-end fs-5">
                            S/. {{ number_format($ingreso->total, 2) }}
                        </td>
                    </tr>
                </tfoot>

            </table>

        </div>

    </div>

</div>

{{-- BOTÓN VOLVER --}}
<div class="text-end mt-4">
    <a href="{{ url('compras/ingreso') }}"
       class="btn btn-outline-dark px-4">
        Volver
    </a>
</div>

@endsection
