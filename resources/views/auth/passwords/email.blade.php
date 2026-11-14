@extends('layouts.auth')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="text-center mb-2 fw-bold">Recuperar contraseña</h3>
                    <p class="text-muted text-center mb-4">
                        Ingresa el correo asociado a tu cuenta y te enviaremos un enlace de recuperación.
                    </p>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                    @endif

                    <form id="password-recovery-form" method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input id="email" type="email"
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid mb-3">
                            <button id="password-recovery-submit" type="submit" class="btn btn-primary btn-lg" aria-live="polite">
                                <span id="password-recovery-spinner" class="spinner-border spinner-border-sm me-2 d-none" aria-hidden="true"></span>
                                <span id="password-recovery-submit-text">Enviar enlace de recuperación</span>
                            </button>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-decoration-none">Volver a iniciar sesión</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('password-recovery-form').addEventListener('submit', function () {
        const email = document.getElementById('email');
        const submitButton = document.getElementById('password-recovery-submit');
        const spinner = document.getElementById('password-recovery-spinner');
        const submitText = document.getElementById('password-recovery-submit-text');

        if (submitButton.disabled) {
            return;
        }

        email.readOnly = true;
        submitButton.disabled = true;
        spinner.classList.remove('d-none');
        submitText.textContent = 'Enviando correo…';
    });
</script>
@endsection
