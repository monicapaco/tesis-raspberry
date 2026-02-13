@extends('layouts.admin')

@section('contenido')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="mb-4">
        <h3 class="fw-bold mb-1">
            Editar cliente
        </h3>
        <p class="text-muted mb-0">
            Modifique la información del cliente
        </p>
    </div>

    {{-- CARD --}}
    <div class="card shadow-sm border-0 rounded-3">

        <form action="{{ route('cliente.update',$persona->id) }}"
              method="POST"
              autocomplete="off">

            @csrf
            @method('PATCH')

            <div class="card-body p-4">

                {{-- ERRORES --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <div class="row g-3">

                    {{-- Nombre --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Nombre *
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name',$persona->name) }}"
                               class="form-control"
                               required>
                    </div>


                    {{-- Tipo documento --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Tipo documento *
                        </label>

                        <select name="type_document"
                                class="form-select"
                                required>

                            @foreach(['DNI','RUC','PAS'] as $doc)
                                <option value="{{ $doc }}"
                                    {{ old('type_document',$persona->type_document) == $doc ? 'selected' : '' }}>
                                    {{ $doc }}
                                </option>
                            @endforeach

                        </select>
                    </div>


                    {{-- Número documento --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Número documento *
                        </label>

                        <input type="text"
                               name="n_document"
                               value="{{ old('n_document',$persona->n_document) }}"
                               class="form-control"
                               required>
                    </div>


                    {{-- Dirección --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Dirección *
                        </label>

                        <input type="text"
                               name="address"
                               value="{{ old('address',$persona->address) }}"
                               class="form-control"
                               required>
                    </div>


                    {{-- Departamento --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Departamento *
                        </label>

                        <select id="region"
                                name="region"
                                class="form-select"
                                required>
                            <option value="{{ $persona->region }}" selected>
                                {{ $persona->region }}
                            </option>
                        </select>
                    </div>


                    {{-- Provincia --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Provincia *
                        </label>

                        <select id="province"
                                name="province"
                                class="form-select"
                                required>
                            <option value="{{ $persona->province }}" selected>
                                {{ $persona->province }}
                            </option>
                        </select>
                    </div>


                    {{-- Distrito --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Distrito *
                        </label>

                        <select id="district"
                                name="district"
                                class="form-select"
                                required>
                            <option value="{{ $persona->district }}" selected>
                                {{ $persona->district }}
                            </option>
                        </select>
                    </div>


                    {{-- Teléfono --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Teléfono *
                        </label>

                        <input type="text"
                               name="phone"
                               value="{{ old('phone',$persona->phone) }}"
                               class="form-control"
                               required>
                    </div>


                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Email *
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email',$persona->email) }}"
                               class="form-control"
                               required>
                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="card-footer bg-white border-0 p-4">

                <div class="d-flex justify-content-end gap-2">

                    <button type="submit" class="btn btn-dark">
                        Guardar cambios
                    </button>

                    <a href="{{ route('cliente.index') }}" class="btn btn-outline-danger">
                        Cancelar
                    </a>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection


@push('scripts')
<script src="{{ asset('js/script.js') }}"></script>
@endpush
