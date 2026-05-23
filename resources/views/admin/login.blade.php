@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', 'DiziBul Admin')
@section('auth_subheader', 'Sign in to continue')

@section('auth_body')
<form action="{{ route('admin.login') }}" method="POST">
    @csrf

    {{-- Email --}}
    <div class="input-group mb-3">
        <input type="email" name="email" value="{{ old('email') }}"
               class="form-control @error('email') is-invalid @enderror"
               placeholder="Email" autofocus required>
        <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
        </div>
        @error('email')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    {{-- Password --}}
    <div class="input-group mb-3">
        <input type="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
               placeholder="Password" required>
        <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
        </div>
        @error('password')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </div>
    </div>
</form>
@stop

@section('auth_footer')
    <a href="/">← Back to site</a>
@stop
