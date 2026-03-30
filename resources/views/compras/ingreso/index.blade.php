@extends('layouts.admin')

@section('contenido')

<h3 class="mb-4 text-center fw-semibold">
    Listado de Compras
</h3>

{{-- BUSCADOR --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body">
        @include('compras.ingreso.search')
    </div>
</div>

{{-- BOTÓN CREAR --}}
<div class="mb-3">
    <a href="{{ url('compras/ingreso/create') }}"
       class="btn btn-dark">
        + Agregar nueva compra
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
                        <th>Proveedor</th>
                        <th>Comprobante</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th width="220">Opciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($ingresos as $in)

                        <tr>
                            <td>{{ $in->id }}</td>

                            <td class="fw-semibold">
                                {{ $in->name }}
                            </td>

                            <td class="small">
                                {{ $in->type_voucher }}
                                :
                                {{ $in->serial_voucher }}-{{ $in->number_voucher }}
                            </td>

                            <td class="text-muted small">
                                {{ $in->created_at }}
                            </td>

                            <td class="fw-semibold">
                                S/. {{ $in->total }}
                            </td>

                            {{-- BADGE ESTADO --}}
                            <td>
                                <span class="badge
                                    {{
                                        $in->status == 'Credito' ? 'bg-warning text-dark' :
                                        ($in->status == 'Al contado' ? 'bg-success' :
                                        'bg-danger')
                                    }}">
                                    {{ $in->status }}
                                </span>
                            </td>

                            {{-- OPCIONES --}}
                            <td class="d-flex gap-2">

                                {{-- SOLO EDITAR SI NO ESTÁ ANULADO --}}
                                @if ($in->status != 'Anulado')
                                    <a href="{{ route('ingreso.edit',$in->id) }}"
                                       class="btn btn-dark btn-sm">
                                        Editar
                                    </a>
                                @endif


                                {{-- DETALLES SIEMPRE --}}
                                <a href="{{ route('ingreso.show',$in->id) }}"
                                   class="btn btn-outline-dark btn-sm">
                                    Detalles
                                </a>


                                {{-- ANULAR SOLO SI NO ESTÁ ANULADO --}}
                                @if ($in->status != 'Anulado')
                                    <button class="btn btn-outline-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDelete{{ $in->id }}">
                                        Anular
                                    </button>
                                @endif

                            </td>

                        </tr>

                        @include('compras.ingreso.modal')

                    @endforeach
                </tbody>

            </table>
        </div>

    </div>
</div>

{{-- PAGINACIÓN --}}
<div class="mt-3">
    {{ $ingresos->links() }}
</div>

@endsection
