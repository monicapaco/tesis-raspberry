@extends('layouts.admin')

@section('contenido')

<div class="row">
    <div class="col-lg-6">
        <h3 class="fw-bold mb-4">Nuevo Proveedor</h3>

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

<form id="providerForm" action="{{ url('compras/proveedor') }}" method="post" autocomplete="off">
    @csrf

    <div class="row">

        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Nombre *</label>
                <input type="text" name="name" class="form-control form-control-lg" required maxlength="100">
            </div>
        </div>

        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Tipo documento *</label>
                <select name="type_document" class="form-control form-control-lg" required>
                    <option value="">Seleccione</option>
                    <option value="DNI">DNI</option>
                    <option value="RUC">RUC</option>
                    <option value="PAS">PAS</option>
                </select>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Número documento *</label>
                <input type="text" name="n_document" class="form-control form-control-lg" required maxlength="15">
            </div>
        </div>

        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Dirección *</label>
                <input type="text" name="address" class="form-control form-control-lg" required maxlength="70">
            </div>
        </div>

        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Departamento *</label>
                <select id="region" name="region" class="form-control form-control-lg" required>
                    <option value="">Seleccione un departamento</option>
                </select>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Provincia *</label>
                <select id="province" name="province" class="form-control form-control-lg" required disabled>
                    <option value="">Seleccione una provincia</option>
                </select>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Distrito *</label>
                <select id="district" name="district" class="form-control form-control-lg" required disabled>
                    <option value="">Seleccione un distrito</option>
                </select>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Teléfono *</label>
                <input type="text" name="phone" class="form-control form-control-lg" required maxlength="15">
            </div>
        </div>

        <div class="col-lg-6">
            <div class="mb-3">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control form-control-lg" required maxlength="50">
            </div>
        </div>

        <div class="col-lg-12 text-end mt-2">
            <button type="submit" class="btn btn-dark">
                Guardar proveedor
            </button>

            <a href="{{ route('proveedor.index') }}" class="btn btn-outline-danger px-4">
                Cancelar
            </a>
        </div>

    </div>

</form>

@endsection

@push('scripts')
<script src="{{ asset('js/script.js') }}"></script>
@endpush
