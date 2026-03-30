@extends('layouts.admin')

@section('contenido')
    <h3 class="mb-4 text-center fw-semibold">
        Nueva categoría
    </h3>

    {{-- ERRORES --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm rounded-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('almacen/categoria') }}" method="post" autocomplete="off">
        @csrf

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">

                <h5 class="mb-4 text-muted fw-semibold">
                    Información de la categoría
                </h5>

                <div class="row g-3">

                    {{-- NOMBRE --}}
                    <div class="col-lg-12">
                        <label class="form-label fw-semibold">
                            Nombre
                        </label>

                        <input type="text" name="name" class="form-control"
                            placeholder="Ej: Silicona, Aceites, Lubricantes..." value="{{ old('name') }}" required>
                    </div>

                    {{-- DESCRIPCIÓN --}}
                    <div class="col-lg-12">
                        <label class="form-label fw-semibold">
                            Descripción
                        </label>

                        <textarea name="description" rows="4" class="form-control resize-none"
                            placeholder="Describe brevemente la categoría" required>{{ old('description') }}</textarea>
                    </div>

                </div>

            </div>
        </div>

        {{-- BOTONES --}}
        <div class="text-end mt-4">

            <button type="submit" class="btn btn-dark px-4">
                Guardar categoría
            </button>

            <a href="{{ url('almacen/categoria') }}" class="btn btn-outline-danger px-4">
                Cancelar
            </a>

        </div>

    </form>
@endsection
