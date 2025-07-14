@extends('layouts.app')

@section('title', 'Login')

@section('content')
<h3 class="card-title text-center mb-4">Login</h3>

@if ($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
    </div>

    {{-- Tombol Login (Utama) --}}
    <div class="d-grid">
        <button type="submit" class="btn btn-primary">Login</button>
    </div>

    {{-- [DIUBAH] Tombol untuk ke halaman Register dengan style baru --}}
    <div class="d-grid mt-2">
        {{-- Mengganti btn-secondary menjadi btn-outline-primary --}}
        <a href="{{ route('register') }}" class="btn btn-outline-primary">Buat Akun</a>
    </div>

</form>
@endsection