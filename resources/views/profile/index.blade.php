@extends('layouts.master')

@section('title', 'Мой профиль')
@section('header')
<header class="py-4" style="background-color: #c46f00;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <a href="{{ route('home') }}" class="navbar-brand text-white fw-bold d-flex align-items-center" style="font-size: 34px;">
                <img src="{{ asset('images/logo.jpg') }}" alt="Инструмент" width="85" height="85" style="margin-right: 10px;">
                <i>AutoDetails</i>
            </a>
    
            <nav class="d-none d-lg-flex align-items-center flex-wrap">
                <a href="{{ route('home') }}" class="text-white mx-2 my-1">Главная</a>
                <a href="{{ route('orders.index') }}" class="text-white mx-2 my-1">Мои заказы</a>
                <a href="{{ route('profile.index') }}" class="text-white mx-2 my-1 active">Профиль</a>
                <a href="{{ route('basket') }}" class="text-white mx-2 my-1">Корзина</a>
            </nav>

            <div class="d-flex align-items-center ms-lg-4 mt-3 mt-lg-0">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-light me-2">Выйти</button>
                </form>
                <a href="{{ route('home') }}" class="btn btn-primary">На главную</a>
            </div>            
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Боковое меню -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Меню профиля</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item active">
                        <a href="{{ route('profile.index') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-person me-2"></i>Личные данные
                        </a>
                    </li>
                    <li class="list-group-item">
                        <a href="{{ route('profile.addresses') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-geo-alt me-2"></i>Адреса доставки
                        </a>
                    </li>
                    <li class="list-group-item">
                        <a href="{{ route('orders.index') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-bag me-2"></i>Мои заказы
                        </a>
                    </li>
                    <li class="list-group-item">
                        <a href="{{ route('reviews.my') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-chat-left-text me-2"></i>Мои отзывы
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Основной контент -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-white border-0 pt-4">
                    <h4 class="mb-0">Личные данные</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Имя</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label">Телефон</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h5 class="mt-5 mb-3">Смена пароля</h5>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="current_password" class="form-label">Текущий пароль</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="new_password" class="form-label">Новый пароль</label>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                       id="new_password" name="new_password">
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="new_password_confirmation" class="form-label">Подтвердите пароль</label>
                                <input type="password" class="form-control" 
                                       id="new_password_confirmation" name="new_password_confirmation">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary px-4">Сохранить изменения</button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">Отмена</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<footer class="py-4 mt-5" style="background-color: #c46f00;">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5 class="fw-bold text-white">Контакты:</h5>
                <ul class="list-unstyled text-white">
                    <li><span>📞</span> +7 (800) 123-45-67</li>
                    <li><span>✉️</span> info@autodetails.com</li>
                    <li><span>📍</span> г. Дзержинск • ул. Циолковского д. 1, корп. 2</li>
                </ul>
            </div>
            
            <div class="col-md-4">
                <h5 class="fw-bold text-white">Навигация:</h5>
                <ul class="list-unstyled">
                    <a href="{{ route('home') }}" class="text-white mx-2 my-1">Главная</a> <br>
                    <a href="{{ route('categories.index') }}" class="text-white mx-2 my-1">Категории</a> <br>
                    <a href="{{ route('profile.index') }}" class="text-white mx-2 my-1">Профиль</a> <br>
                    <a href="{{ route('orders.index') }}" class="text-white mx-2 my-1">Заказы</a>
                </ul>
            </div>
            
            <div class="col-md-4">
                <h5 class="fw-bold text-white">Мы в соцсетях:</h5>
                <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i> Facebook</a><br>
                <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i> Instagram</a><br>
                <a href="#" class="text-white"><i class="bi bi-vk"></i> ВКонтакте</a>
            </div>
        </div>
        <hr class="bg-light">
        <div class="text-center text-white">
            <p class="mb-0">&copy; 2025 AutoDetails. Все права защищены.</p>
        </div>
    </div>
</footer>
@endsection