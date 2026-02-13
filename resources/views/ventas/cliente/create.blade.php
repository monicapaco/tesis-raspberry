@extends('layouts.admin')

@section('contenido')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="mb-4">
        <h3 class="fw-semibold mb-0">
            Nuevo cliente
        </h3>
        <small class="text-muted">
            Registrar información del cliente
        </small>
    </div>


    {{-- ERRORES --}}
    @if ($errors->any())
        <div class="alert alert-danger rounded-3 shadow-sm">
            <strong>Se encontraron errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ url('ventas/cliente') }}"
            method="POST"
            autocomplete="off">

        @csrf


        <div class="card shadow-sm border-0 rounded-4">

            <div class="card-body p-4">

                <div class="row g-3">

                    {{-- Nombre --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Nombre *
                        </label>
                        <input type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name') }}"
                                required>
                    </div>


                    {{-- Tipo documento --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Tipo documento *
                        </label>
                        <select name="type_document"
                                class="form-select"
                                required>
                            <option value="">Seleccionar</option>
                            <option value="DNI">DNI</option>
                            <option value="RUC">RUC</option>
                            <option value="PAS">PASAPORTE</option>
                        </select>
                    </div>


                    {{-- Numero documento --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Número documento *
                        </label>
                        <input type="text"
                                name="n_document"
                                class="form-control"
                                value="{{ old('n_document') }}"
                                required>
                    </div>


                    {{-- Dirección --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Dirección *
                        </label>
                        <input type="text"
                                name="address"
                                class="form-control"
                                value="{{ old('address') }}"
                                required>
                    </div>


                    {{-- Departamento --}}
                    <div class="col-md-4">
                        <label class="form-label">
                            Departamento *
                        </label>
                        <select id="region"
                                name="region"
                                class="form-select"
                                required>
                            <option value="">Seleccionar</option>
                        </select>
                    </div>


                    {{-- Provincia --}}
                    <div class="col-md-4">
                        <label class="form-label">
                            Provincia *
                        </label>
                        <select id="province"
                                name="province"
                                class="form-select"
                                disabled
                                required>
                            <option value="">Seleccionar</option>
                        </select>
                    </div>


                    {{-- Distrito --}}
                    <div class="col-md-4">
                        <label class="form-label">
                            Distrito *
                        </label>
                        <select id="district"
                                name="district"
                                class="form-select"
                                disabled
                                required>
                            <option value="">Seleccionar</option>
                        </select>
                    </div>


                    {{-- Teléfono --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Teléfono *
                        </label>
                        <input type="text"
                                name="phone"
                                class="form-control"
                                value="{{ old('phone') }}"
                                required>
                    </div>


                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label">
                            Email *
                        </label>
                        <input type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                required>
                    </div>

                </div>

            </div>


            {{-- FOOTER BOTONES --}}
            <div class="card-footer bg-white border-0 p-4">

                <div class="d-flex justify-content-end gap-2">

                    <button type="submit" class="btn btn-dark">
                        Guardar cliente
                    </button>

                    <a href="{{ route('cliente.index') }}" class="btn btn-outline-danger">
                        Cancelar
                    </a>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection


@push('scripts')
<script src="{{ asset('js/script.js') }}"></script>
@endpush
