@extends('layouts.admin')

@section('contenido')

<h3 class="mb-4 text-center fw-semibold">
    Listado de Proveedores
</h3>

{{-- BUSCADOR --}}
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-body">
        @include('compras.proveedor.search')
    </div>
</div>

{{-- BOTÓN CREAR --}}
<div class="mb-3">
    <a href="{{ url('compras/proveedor/create') }}"
       class="btn btn-dark">
        + Agregar nuevo proveedor
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
                        <th>Documento</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Estado</th>
                        <th width="220">Opciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($personas as $per)

                        <tr>
                            <td>{{ $per->id }}</td>

                            <td class="fw-semibold">
                                {{ $per->name }}
                            </td>

                            {{-- DOCUMENTO --}}
                            <td class="small">
                                <span class="text-muted">
                                    {{ $per->type_document }}
                                </span>
                                <br>
                                <strong>{{ $per->n_document }}</strong>
                            </td>

                            <td>
                                {{ $per->phone ?? '-' }}
                            </td>

                            {{-- DIRECCIÓN MÁS LIMPIA --}}
                            <td class="small text-muted">
                                {{ $per->address }}<br>
                                {{ $per->district }} - {{ $per->province }} - {{ $per->region }}
                            </td>

                            {{-- ESTADO --}}
                            <td>
                                <span class="badge
                                    {{ $per->status == 'ACTIVO'
                                        ? 'bg-success'
                                        : 'bg-secondary' }}">
                                    {{ $per->status }}
                                </span>
                            </td>

                            {{-- OPCIONES --}}
                            <td class="align-middle">

                                <div class="d-flex gap-2 justify-content-center">

                                    <a href="{{ route('proveedor.edit',$per->id) }}"
                                    class="btn btn-dark btn-sm">
                                        Editar
                                    </a>

                                    @if($per->status == 'ACTIVO')

                                        <button class="btn btn-outline-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDelete{{ $per->id }}">
                                            Dar de baja
                                        </button>

                                    @else

                                        <form action="{{ route('proveedor.activate',$per->id) }}"
                                            method="POST">
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

                        @include('compras.proveedor.modal')

                    @endforeach
                </tbody>

            </table>
        </div>

    </div>
</div>

{{-- PAGINACIÓN --}}
<div class="mt-3">
    {{ $personas->links() }}
</div>

@endsection
