@extends('layouts.admin')

@section('contenido')

<h3 class="mb-4 text-center fw-semibold">
    Editar Ingreso
</h3>

@if ($errors->any())
<div class="alert alert-danger rounded-3 shadow-sm">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('ingreso.update',$ingreso->id) }}" method="POST">
@csrf
@method('PATCH')

{{-- ================= INFO INGRESO ================= --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body">

        <h5 class="mb-4 text-muted fw-semibold">
            Información del ingreso
        </h5>

        <div class="row g-3">

            <div class="col-lg-12">
                <label class="form-label">Proveedor</label>
                <input class="form-control bg-light" value="{{ $ingreso->name }}" disabled>
            </div>

            <div class="col-lg-4">
                <label class="form-label">Tipo comprobante</label>
                <input class="form-control bg-light" value="{{ $ingreso->type_voucher }}" disabled>
            </div>

            <div class="col-lg-4">
                <label class="form-label">Comprobante</label>
                <input class="form-control bg-light"
                       value="{{ $ingreso->serial_voucher }} - {{ $ingreso->number_voucher }}"
                       disabled>
            </div>

            <div class="col-lg-4">
                <label class="form-label">Estado del pedido</label>

                <select name="status" class="form-select">
                    <option value="Credito"
                        {{ $ingreso->status == 'Credito' ? 'selected' : '' }}>
                        Crédito
                    </option>

                    <option value="Al contado"
                        {{ $ingreso->status == 'Al contado' ? 'selected' : '' }}>
                        Al contado
                    </option>
                </select>
            </div>

        </div>

    </div>
</div>

{{-- ================= DETALLE ================= --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body">

        <h5 class="mb-4 text-muted fw-semibold">
            Detalle del ingreso
        </h5>

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-header-soft">
                    <tr>
                        <th>Artículo</th>
                        <th>Cantidad</th>
                        <th>P. Compra</th>
                        <th>P. Venta</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($detalles as $det)
                        <tr>
                            <td>{{ $det->articulo }}</td>
                            <td>{{ $det->quantity }}</td>
                            <td>S/. {{ number_format($det->purchase_price,2) }}</td>
                            <td>S/. {{ number_format($det->sale_price,2) }}</td>
                            <td class="fw-semibold">
                                S/. {{ number_format($det->purchase_price * $det->quantity,2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr class="fw-semibold">
                        <td colspan="4" class="text-end">
                            TOTAL
                        </td>
                        <td>
                            <h5 class="mb-0">
                                S/. {{ number_format($ingreso->total,2) }}
                            </h5>
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

    <a href="{{ url('compras/ingreso') }}"
       class="btn btn-outline-danger px-4">
        Cancelar
    </a>
</div>

</form>

@endsection
