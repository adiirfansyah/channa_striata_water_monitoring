@extends('layouts.app')

@section('title', 'Register')

@section('content')
<h3 class="card-title text-center mb-4">Buat akun baru</h3>

{{-- [DIUBAH] Menambahkan autocomplete="off" pada form --}}
<form method="POST" action="{{ route('register') }}" autocomplete="off">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        {{-- [DIUBAH] Menambahkan autocomplete="off" pada input --}}
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autocomplete="off">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        {{-- [DIUBAH] Menambahkan autocomplete="off" pada input --}}
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="off">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        {{-- [DIUBAH] Menggunakan autocomplete="new-password" untuk keamanan --}}
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        {{-- [DIUBAH] Menggunakan autocomplete="new-password" agar konsisten --}}
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
    </div>
    <div class="d-grid">
        <button type="submit" class="btn btn-primary">Buat akun</button>
    </div>
    <div class="text-center mt-3">
        <p>Sudah punya akun? <a href="{{ route('login') }}">Klik disini</a></p>
    </div>
</form>
@endsection