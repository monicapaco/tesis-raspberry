@extends('layouts.admin')

@section('contenido')
    <h3 class="mb-4 text-center fw-semibold">
        Listado de categorías
    </h3>

    {{-- BUSCADOR --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-lg-12">
                    @include('almacen.categoria.search')
                </div>

            </div>

        </div>
    </div>

    {{-- BOTÓN CREAR --}}
    <div class="mb-3">
        <a href="{{ url('almacen/categoria/create') }}" class="btn btn-dark">
            + Agregar nueva categoría
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
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th width="200">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($categorias as $cat)
                            <tr>

                                <td>{{ $cat->id }}</td>

                                <td class="fw-semibold">
                                    {{ $cat->name }}
                                </td>

                                <td class="text-muted small">
                                    {{ $cat->description }}
                                </td>

                                {{-- ESTADO --}}
                                <td>
                                    <span class="badge {{ $cat->status ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $cat->status ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>

                                {{-- OPCIONES --}}
                                <td class="d-flex gap-2">

                                    <a href="{{ route('categoria.edit', $cat->id) }}" class="btn btn-dark btn-sm">
                                        Editar
                                    </a>

                                    @if ($cat->status)
                                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalDelete{{ $cat->id }}">
                                            Dar de baja
                                        </button>
                                    @else
                                        <form action="{{ route('categoria.activate', $cat->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <button class="btn btn-success btn-sm">
                                                Reactivar
                                            </button>
                                        </form>
                                    @endif

                                </td>

                            </tr>

                            @include('almacen.categoria.modal')
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    {{-- PAGINACIÓN --}}
    <div class="mt-3">
        {{ $categorias->links() }}
    </div>
@endsection
