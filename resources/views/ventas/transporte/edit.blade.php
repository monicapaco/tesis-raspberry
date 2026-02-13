@extends('layouts.admin')

@section('contenido')

    <h3 class="mb-4 text-center fw-semibold">
        Editar transporte
    </h3>

    {{-- ERRORES --}}
    @if ($errors->any())
        <div class="alert alert-danger rounded-3 shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transporte.update', $transporte->id) }}" method="POST" autocomplete="off">

        @csrf
        @method('PATCH')

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">

                <div class="row g-3">

                    {{-- NOMBRE --}}
                    <div class="col-lg-6">
                        <label class="form-label">Nombre *</label>

                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $transporte->name) }}" placeholder="Nombre del transporte..." required>
                    </div>

                    {{-- TIPO DOCUMENTO --}}
                    <div class="col-lg-6">
                        <label class="form-label">Tipo documento *</label>

                        <select name="type_document" class="form-select" required>

                            <option value="DNI"
                                {{ old('type_document', $transporte->type_document) == 'DNI' ? 'selected' : '' }}>
                                DNI
                            </option>

                            <option value="RUC"
                                {{ old('type_document', $transporte->type_document) == 'RUC' ? 'selected' : '' }}>
                                RUC
                            </option>

                            <option value="PAS"
                                {{ old('type_document', $transporte->type_document) == 'PAS' ? 'selected' : '' }}>
                                PAS
                            </option>

                        </select>
                    </div>

                    {{-- NUMERO DOCUMENTO --}}
                    <div class="col-lg-6">
                        <label class="form-label">Número documento *</label>

                        <input type="text" name="n_document" class="form-control"
                            value="{{ old('n_document', $transporte->n_document) }}" placeholder="Número de documento..."
                            required>
                    </div>

                </div>

            </div>

            {{-- BOTONES --}}
            <div class="card-footer bg-white border-0 d-flex justify-content-end gap-2">

                <button type="submit" class="btn btn-dark">
                    Guardar cambios
                </button>

                <a href="{{ route('transporte.index') }}" class="btn btn-outline-danger">
                    Cancelar
                </a>

            </div>

        </div>

    </form>

@endsection
