@extends('layouts.master')

@section('title', 'Восстановление пароля')
@section('header')
<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #c46f00;">
    <div class="container">
        <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.jpg') }}" alt="AutoDetails" width="60" height="60" class="me-2">
            <span style="font-size: 28px;">AutoDetails</span>
        </a>
        <div class="d-flex">
            <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Войти</a>
            <a href="{{ route('home') }}" class="btn btn-outline-light">На главную</a>
        </div>
    </div>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg mt-5">
                <div class="card-header bg-white border-0 text-center py-4">
                    <h3 class="mb-0">Восстановление пароля</h3>
                </div>
                <div class="card-body p-5">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="email" class="form-label">Email адрес</label>
                            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                На указанный email будет отправлена ссылка для восстановления пароля
                            </small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Отправить ссылку</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-primary">Вернуться к входу</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<footer class="py-4 mt-5" style="background-color: #c46f00;">
    <div class="container">
        <div class="text-center text-white">
            <p class="mb-0">&copy; 2025 AutoDetails. Все права защищены.</p>
        </div>
    </div>
</footer>
@endsection