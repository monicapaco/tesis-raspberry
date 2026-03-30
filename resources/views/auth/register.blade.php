@extends('layouts.auth')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center auth-bg">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">

                    <h3 class="text-center mb-4 fw-bold">
                        Crear cuenta
                    </h3>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Nombre y Apellido
                            </label>
                            <input
                                id="name"
                                type="text"
                                class="form-control form-control-lg @error('name') is-invalid @enderror"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Correo electrónico
                            </label>
                            <input
                                id="email"
                                type="email"
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email') }}"
                                required
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Contraseña
                            </label>
                            <input
                                id="password"
                                type="password"
                                class="form-control form-control-lg @error('password') is-invalid @enderror"
                                name="password"
                                required
                            >

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label">
                                Confirmar contraseña
                            </label>
                            <input
                                id="password-confirm"
                                type="password"
                                class="form-control form-control-lg"
                                name="password_confirmation"
                                required
                            >
                        </div>

                        {{-- Button --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Registrarme
                            </button>
                        </div>
                    </form>

                    {{-- Login link --}}
                    @if (Route::has('login'))
                        <div class="text-center mt-4">
                            <span class="text-muted">¿Ya tienes cuenta?</span>
                            <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">
                                Inicia sesión
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
