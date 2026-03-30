@extends('layouts.admin')

@section('contenido')
    <h3 class="mb-4 text-center fw-semibold">
        Resumen de venta
    </h3>

    {{-- ================= INFO VENTA ================= --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">

            <div class="row g-3">

                {{-- Cliente --}}
                <div class="col-lg-12">
                    <label class="form-label text-muted small">
                        Cliente
                    </label>

                    <div class="fw-semibold fs-5">
                        {{ $venta->name }}
                    </div>
                </div>

                {{-- Tipo comprobante --}}
                <div class="col-lg-4">
                    <label class="form-label text-muted small">
                        Tipo comprobante
                    </label>

                    <div class="fw-semibold">
                        {{ $venta->type_voucher }}
                    </div>
                </div>

                {{-- Comprobante --}}
                <div class="col-lg-4">
                    <label class="form-label text-muted small">
                        N° comprobante
                    </label>

                    <div class="fw-semibold">
                        {{ $venta->serial_voucher }} - {{ $venta->number_voucher }}
                    </div>
                </div>

                {{-- Estado --}}
                <div class="col-lg-4">
                    <label class="form-label text-muted small">
                        Estado
                    </label>

                    <div>
                        <span
                            class="badge
                        {{ $venta->status == 'Credito' ? 'bg-warning text-dark' : ($venta->status == 'Al contado' ? 'bg-success' : 'bg-danger') }}">
                            {{ $venta->status }}
                        </span>
                    </div>
                </div>

            </div>

        </div>
    </div>


    {{-- ================= DETALLE VENTA ================= --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">

            <h5 class="mb-4 text-muted fw-semibold">
                Detalle de la venta
            </h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle rounded-table">

                    <thead class="table-header-soft">
                        <tr>
                            <th>Artículo</th>
                            <th>Cantidad</th>
                            <th>Precio venta</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($detalles as $det)
                            <tr>

                                <td class="fw-semibold">
                                    {{ $det->articulo }}
                                </td>

                                <td>
                                    {{ $det->quantity }}
                                </td>

                                <td>
                                    S/. {{ number_format($det->sale_price, 2) }}
                                </td>

                                <td class="fw-semibold">
                                    S/. {{ number_format($det->sale_price * $det->quantity, 2) }}
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="3" class="text-end">
                                TOTAL
                            </td>
                            <td class="fs-5">
                                S/. {{ number_format($venta->total, 2) }}
                            </td>
                        </tr>
                    </tfoot>

                </table>
            </div>

        </div>
    </div>


    {{-- BOTONES --}}
    <div class="text-end">

        <a href="{{ route('venta.imprimir', $venta->id) }}" target="_blank" class="btn btn-dark px-4">
            Imprimir
        </a>

        <a href="{{ url('ventas/venta') }}" class="btn btn-outline-secondary px-4">
            Volver
        </a>

    </div>
@endsection
