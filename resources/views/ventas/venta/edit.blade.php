@extends('layouts.admin')

@section('contenido')
    <h3 class="mb-4 text-center fw-semibold">
        Editar estado de venta
    </h3>

    @if (count($errors) > 0)
        <div class="alert alert-danger rounded-3 shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('venta.update', $venta->id) }}" method="POST">
        @csrf
        @method('PATCH')

        {{-- ================= INFO VENTA ================= --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body">

                <h5 class="mb-4 text-muted fw-semibold">
                    Información de la venta
                </h5>

                <div class="row g-3">

                    {{-- Cliente --}}
                    <div class="col-lg-12">
                        <label class="form-label">Cliente</label>
                        <div class="form-control bg-light">
                            {{ $venta->name }}
                        </div>
                    </div>

                    {{-- Tipo comprobante --}}
                    <div class="col-lg-3">
                        <label class="form-label">Tipo comprobante</label>
                        <div class="form-control bg-light">
                            {{ $venta->type_voucher }}
                        </div>
                    </div>

                    {{-- Comprobante --}}
                    <div class="col-lg-3">
                        <label class="form-label">Comprobante</label>
                        <div class="form-control bg-light">
                            {{ $venta->serial_voucher }} - {{ $venta->number_voucher }}
                        </div>
                    </div>

                    {{-- Estado --}}
                    <div class="col-lg-3">
                        <label class="form-label">Estado del pago *</label>

                        <select name="status" class="form-control" required>

                            <option value="Credito" {{ $venta->status == 'Credito' ? 'selected' : '' }}>
                                Crédito
                            </option>

                            <option value="Al contado" {{ $venta->status == 'Al contado' ? 'selected' : '' }}>
                                Al contado
                            </option>

                        </select>
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
                            <th>Artículo</th>
                            <th>Cantidad</th>
                            <th>Precio venta</th>
                            <th>Subtotal</th>
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

                                    <td>
                                        S/. {{ number_format($det->sale_price * $det->quantity, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr class="fw-semibold">
                                <td colspan="3" class="text-end">
                                    TOTAL
                                </td>
                                <td>
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

            <button type="submit" class="btn btn-dark px-4">
                Guardar cambios
            </button>

            <a href="{{ url('ventas/venta') }}" class="btn btn-outline-danger px-4">
                Cancelar
            </a>

        </div>

    </form>
@endsection
