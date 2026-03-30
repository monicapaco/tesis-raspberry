@extends('layouts.admin')

@section('contenido')
    {{-- HEADER --}}
    <div class="d-flex justify-content-center align-items-center mb-4">

        <div>
            <h3 class="mb-0 fw-semibold">
                Listado de clientes
            </h3>
            <small class="text-muted">
                Administración de clientes registrados
            </small>
        </div>

    </div>


    {{-- BUSCADOR --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            @include('ventas.cliente.search')
        </div>
    </div>

    <a href="{{ url('ventas/cliente/create') }}" class="btn btn-dark shadow-sm mb-4">
        + Nuevo cliente
    </a>

    {{-- TABLA --}}
    <div class="card shadow-sm border-0 rounded-4">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-header-soft">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Documento</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Estado</th>
                        <th width="180">Opciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($personas as $per)
                        <tr>

                            {{-- ID --}}
                            <td class="fw-semibold text-muted">
                                {{ $per->id }}
                            </td>

                            {{-- Nombre --}}
                            <td>
                                <div class="fw-semibold">
                                    {{ $per->name }}
                                </div>
                            </td>

                            {{-- Documento --}}
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $per->type_document }}
                                </span>

                                <div class="small text-muted">
                                    {{ $per->n_document }}
                                </div>
                            </td>

                            {{-- Teléfono --}}
                            <td>
                                {{ $per->phone ?: '—' }}
                            </td>

                            {{-- Dirección --}}
                            <td>
                                <div>{{ $per->address }}</div>

                                <small class="text-muted">
                                    {{ $per->region }} - {{ $per->province }} - {{ $per->district }}
                                </small>
                            </td>

                            <td>
                                <span
                                    class="badge {{ strtolower($per->status) == 'activo' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $per->status }}
                                </span>
                            </td>

                            {{-- OPCIONES --}}
                            <td class="align-middle">
                                <div class="d-flex gap-2 justify-content-center">

                                    <a href="{{ route('cliente.edit', $per->id) }}" class="btn btn-dark btn-sm">
                                        Editar
                                    </a>

                                    @if (strtolower($per->status) == 'activo')
                                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalDelete{{ $per->id }}">
                                            Dar de baja
                                        </button>
                                    @else
                                        <form action="{{ route('cliente.activate', $per->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button class="btn btn-success btn-sm">
                                                Reactivar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL --}}
                        @include('ventas.cliente.modal')
                    @endforeach
                </tbody>

            </table>

        </div>

    </div>


    {{-- PAGINACIÓN --}}
    <div class="mt-3">
        {{ $personas->links() }}
    </div>
@endsection
