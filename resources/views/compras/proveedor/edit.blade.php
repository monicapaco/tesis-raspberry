@extends('layouts.admin')
@section('contenido')

<div class="row">
    <div class="col-lg-6">
        <h3 class="fw-bold mb-4">
            Editar proveedor <br>
            <small class="text-muted">{{ $persona->name }}</small>
        </h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

<form action="{{ route('proveedor.update', $persona->id) }}"
      method="POST"
      autocomplete="off">

    @csrf
    @method('PATCH')

    <div class="row">

        {{-- Nombre --}}
        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Nombre *</label>
                <input type="text"
                       name="name"
                       class="form-control form-control-lg"
                       value="{{ $persona->name }}"
                       required maxlength="100">
            </div>
        </div>

        {{-- Tipo documento --}}
        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Tipo documento *</label>
                <select name="type_document"
                        class="form-control form-control-lg"
                        required>

                    <option value="DNI" {{ $persona->type_document == 'DNI' ? 'selected' : '' }}>DNI</option>
                    <option value="RUC" {{ $persona->type_document == 'RUC' ? 'selected' : '' }}>RUC</option>
                    <option value="PAS" {{ $persona->type_document == 'PAS' ? 'selected' : '' }}>PAS</option>

                </select>
            </div>
        </div>

        {{-- Numero documento --}}
        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Número documento *</label>
                <input type="text"
                       name="n_document"
                       class="form-control form-control-lg"
                       value="{{ $persona->n_document }}"
                       required maxlength="15">
            </div>
        </div>

        {{-- Dirección --}}
        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Dirección *</label>
                <input type="text"
                       name="address"
                       class="form-control form-control-lg"
                       value="{{ $persona->address }}"
                       required maxlength="70">
            </div>
        </div>

        {{-- Departamento --}}
        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Departamento *</label>
                <select id="region"
                        name="region"
                        class="form-control form-control-lg"
                        required>
                    <option value="{{ $persona->region }}">
                        {{ $persona->region }}
                    </option>
                </select>
            </div>
        </div>

        {{-- Provincia --}}
        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Provincia *</label>
                <select id="province"
                        name="province"
                        class="form-control form-control-lg"
                        required>
                    <option value="{{ $persona->province }}">
                        {{ $persona->province }}
                    </option>
                </select>
            </div>
        </div>

        {{-- Distrito --}}
        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Distrito *</label>
                <select id="district"
                        name="district"
                        class="form-control form-control-lg"
                        required>
                    <option value="{{ $persona->district }}">
                        {{ $persona->district }}
                    </option>
                </select>
            </div>
        </div>

        {{-- Teléfono --}}
        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Teléfono *</label>
                <input type="text"
                       name="phone"
                       class="form-control form-control-lg"
                       value="{{ $persona->phone }}"
                       required maxlength="15">
            </div>
        </div>

        {{-- Email --}}
        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Email *</label>
                <input type="email"
                       name="email"
                       class="form-control form-control-lg"
                       value="{{ $persona->email }}"
                       required maxlength="50">
            </div>
        </div>

        {{-- Botones --}}
        <div class="col-lg-12 text-end mt-2">
            <button type="submit"
                    class="btn btn-dark px-4">
                Guardar cambios
            </button>

            <a href="{{ route('proveedor.index') }}"
               class="btn btn-danger px-4">
                Cancelar
            </a>
        </div>

    </div>
</form>

@endsection

@push('scripts')
<script src="{{ asset('js/script.js') }}"></script>
@endpush
