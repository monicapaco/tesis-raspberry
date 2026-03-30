@extends('layouts.admin')

@section('contenido')

<h3 class="mb-4 text-center fw-semibold">
    Listado de artículos
</h3>

{{-- BUSCADOR + ACCIONES --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body">

        <div class="row align-items-center">

            <div class="col-lg-9">
                @include('almacen.articulo.search')
            </div>

            <div class="col-lg-3 text-end">

                @if ($articulos->count() > 0)
                    <a href="{{ route('articulo.imprimir') }}" target="_blank"
                       class="btn btn-outline-dark">
                        Imprimir listado
                    </a>
                @endif

            </div>

        </div>

    </div>
</div>

{{-- BOTÓN CREAR --}}
<div class="mb-3">
    <a href="{{ url('almacen/articulo/create') }}"
       class="btn btn-dark">
        + Agregar nuevo artículo
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
                        <th>Código</th>
                        <th>Categoría</th>
                        <th>Stock</th>
                        <th>Imagen</th>
                        <th>Descripción</th>
                        <th width="180">Opciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($articulos as $art)
                        <tr>

                            <td>{{ $art->id }}</td>
                            <td class="fw-semibold">{{ $art->name }}</td>
                            <td>{{ $art->codevar }}</td>
                            <td>{{ $art->categoria }}</td>

                            {{-- STOCK BADGE --}}
                            <td>
                                <span class="badge {{ $art->stock > 0 ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $art->stock }}
                                </span>
                            </td>

                            {{-- IMAGEN --}}
                            <td>
                                <img src="{{ asset('imagenes/articulos/'.$art->img) }}"
                                     alt="{{ $art->name }}"
                                     class="rounded-3 shadow-sm"
                                     width="70">
                            </td>

                            <td class="text-muted small">
                                {{ $art->description }}
                            </td>

                            {{-- BOTONES --}}
                            <td class="align-middle">
                                <div class="d-flex gap-2 justify-content-center">

                                    <a href="{{ route('articulo.edit',$art->id) }}"
                                    class="btn btn-dark btn-sm">
                                        Editar
                                    </a>

                                    <button class="btn btn-outline-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDelete{{ $art->id }}">
                                        Eliminar
                                    </button>

                                </div>
                            </td>

                        </tr>

                        @include('almacen.articulo.modal')

                    @endforeach
                </tbody>

            </table>
        </div>

    </div>
</div>

<div class="mt-3">
    {{ $articulos->links() }}
</div>

@endsection
