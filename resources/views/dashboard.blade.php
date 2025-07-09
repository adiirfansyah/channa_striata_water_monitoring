@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="text-center">
    <h3 class="mb-3">Welcome to the Dashboard!</h3>
    
    @auth
        <p class="lead">Hello, <strong>{{ Auth::user()->name }}</strong>!</p>
        <p>You are logged in.</p>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger mt-3">Logout</button>
        </form>
    @endauth
</div>
@endsection