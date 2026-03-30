@extends('layouts.admin')

@section('contenido')
    <h3 class="mb-4 text-center fw-semibold">
        Listado de transporte
    </h3>

    {{-- BUSCADOR --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-lg-12">
                    @include('ventas.transporte.search')
                </div>

            </div>

        </div>
    </div>

    {{-- BOTÓN CREAR --}}
    <div class="mb-3">
        <a href="{{ route('transporte.create') }}" class="btn btn-dark">
            + Agregar transporte
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
                            <th>Nombre</th>
                            <th>Tipo Doc</th>
                            <th>N° Documento</th>
                            <th>Estado</th>
                            <th width="200">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($transportes as $tr)
                            <tr class="{{ $tr->status == '0' ? 'table-light' : '' }}">

                                <td>{{ $tr->id }}</td>

                                <td class="fw-semibold">
                                    {{ $tr->name }}
                                </td>

                                <td>
                                    {{ $tr->type_document }}
                                </td>

                                <td class="text-muted small">
                                    {{ $tr->n_document }}
                                </td>

                                {{-- ESTADO --}}
                                <td>
                                    <span class="badge {{ $tr->status == '1' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $tr->status == '1' ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>

                                {{-- OPCIONES --}}
                                <td class="d-flex gap-2">

                                    {{-- EDITAR --}}
                                    <a href="{{ route('transporte.edit', $tr->id) }}" class="btn btn-dark btn-sm">
                                        Editar
                                    </a>

                                    {{-- BOTONES SEGÚN ESTADO --}}
                                    @if ($tr->status == '1')
                                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalDelete{{ $tr->id }}">
                                            Dar de baja
                                        </button>
                                    @else
                                        <form action="{{ route('transporte.activate', $tr->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button class="btn btn-success btn-sm">
                                                Reactivar
                                            </button>
                                        </form>
                                    @endif

                                </td>

                            </tr>

                            {{-- MODAL --}}
                            @include('ventas.transporte.modal')
                        @endforeach

                    </tbody>

                </table>
            </div>

        </div>
    </div>

    {{-- PAGINACIÓN --}}
    <div class="mt-3">
        {{ $transportes->links() }}
    </div>
@endsection
