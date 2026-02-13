@extends('layouts.admin')

@section('contenido')
    <h3 class="mb-4 text-center fw-semibold">
        Listado de ventas
    </h3>

    {{-- BUSCADOR --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    @include('ventas.venta.search')
                </div>
            </div>
        </div>
    </div>

    {{-- BOTÓN CREAR --}}
    <div class="mb-3">
        <a href="{{ route('venta.create') }}" class="btn btn-dark">
            + Agregar nueva venta
        </a>
    </div>

    {{-- TABLA --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-header-soft">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Documento</th>
                            <th>Comprobante</th>
                            <th>Fecha Registro</th>
                            <th>Fecha Pago</th>
                            <th>Total</th>
                            <th>Tipo Venta</th>
                            <th>Estado Pago</th>
                            <th width="280">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($ventas as $in)
                            <tr>

                                <td>{{ $in->id }}</td>

                                {{-- CLIENTE --}}
                                <td class="fw-semibold">
                                    {{ $in->client->name ?? 'Sin cliente' }}
                                </td>

                                {{-- DOCUMENTO --}}
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $in->client->type_document ?? '-' }}
                                    </span>

                                    <div class="small text-muted">
                                        {{ $in->client->n_document ?? '-' }}
                                    </div>
                                </td>

                                {{-- COMPROBANTE --}}
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $in->type_voucher }}
                                    </span>

                                    <div class="small text-muted">
                                        {{ $in->serial_voucher }} - {{ $in->number_voucher }}
                                    </div>
                                </td>

                                {{-- FECHA REGISTRO --}}
                                <td>
                                    {{ $in->created_at->format('d/m/Y H:i') }}
                                </td>

                                {{-- FECHA PAGO --}}
                                <td>
                                    @if ($in->paid_at)
                                        <span class="text-success fw-semibold">
                                            {{ $in->fecha_pago_formateada ?? '-' }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- TOTAL --}}
                                <td class="fw-semibold">
                                    S/. {{ number_format($in->total, 2) }}
                                </td>

                                {{-- ESTADO VENTA --}}
                                <td>
                                    <span
                                        class="badge
                                            {{ $in->status == 'Credito'
                                                ? 'bg-warning text-dark'
                                                : ($in->status == 'Al contado'
                                                    ? 'bg-success'
                                                    : 'bg-danger') }}">
                                        {{ $in->status }}
                                    </span>
                                </td>

                                {{-- ESTADO PAGO --}}
                                <td>
                                    @if ($in->payment_status == 'Pagado')
                                        <span class="badge bg-success">
                                            Pagado
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Pendiente
                                        </span>
                                    @endif
                                </td>

                                {{-- OPCIONES --}}
                                <td>

                                    <div class="d-flex gap-2">

                                        {{-- Detalles --}}
                                        <a href="{{ route('venta.show', $in->id) }}" class="btn btn-outline-dark btn-sm">
                                            Detalles
                                        </a>

                                        {{-- Dropdown Opciones --}}
                                        <div class="btn-group">

                                            <button class="btn btn-dark btn-sm dropdown-toggle" data-bs-toggle="dropdown"
                                                aria-expanded="false" {{ $in->status == 'Anulado' ? 'disabled' : '' }}>
                                                Opciones
                                            </button>

                                            @if ($in->status != 'Anulado')
                                                <ul class="dropdown-menu">

                                                    {{-- Editar --}}
                                                    {{-- <li>
                                                        <a class="dropdown-item" href="{{ route('venta.edit', $in->id) }}">
                                                            ✏️ Editar
                                                        </a>
                                                    </li> --}}

                                                    @if ($in->status == 'Credito' && $in->payment_status == 'Pendiente')
                                                        <li>
                                                            <form action="{{ route('venta.markPaid', $in->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PATCH')

                                                                <button class="dropdown-item">
                                                                    💰 Registrar Pago
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif

                                                    {{-- Imprimir --}}
                                                    <li>
                                                        <a class="dropdown-item" target="_blank"
                                                            href="{{ route('venta.imprimir', $in->id) }}">
                                                            🖨 Imprimir
                                                        </a>
                                                    </li>

                                                    {{-- Anular --}}
                                                    <li>
                                                        <button class="dropdown-item text-danger" data-bs-toggle="modal"
                                                            data-bs-target="#modalDelete{{ $in->id }}">
                                                            ❌ Anular
                                                        </button>
                                                    </li>

                                                </ul>
                                            @endif

                                        </div>

                                    </div>

                                </td>

                            </tr>

                            @include('ventas.venta.modal')
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    {{-- PAGINACIÓN --}}
    <div class="mt-3">
        {{ $ventas->links() }}
    </div>
@endsection
