@extends('layouts.app')

@section('title', 'Login')

@section('content')
<h3 class="card-title text-center mb-4">Login</h3>

@if ($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first('email') }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="d-grid">
        <button type="submit" class="btn btn-primary">Login</button>
    </div>
    <div class="text-center mt-3">
        <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
    </div>
</form>
@endsection