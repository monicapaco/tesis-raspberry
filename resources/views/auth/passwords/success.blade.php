@extends('layouts.auth')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 text-center">
                    <h3 class="mb-3 fw-bold">Contraseña actualizada</h3>
                    <p class="text-muted mb-4">
                        Tu contraseña se cambió correctamente. Ya puedes ingresar con tus nuevas credenciales.
                    </p>
                    <div class="d-grid">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Ir a iniciar sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
