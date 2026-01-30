@extends('layouts.master')

@section('title', 'Категории товаров')
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
                <a href="{{ route('categories.index') }}" class="text-white mx-2 my-1 active">Категории</a>
                <a href="{{ route('profile.index') }}" class="text-white mx-2 my-1">Профиль</a>
                <a href="{{ route('basket') }}" class="text-white mx-2 my-1">Корзина</a>
            </nav>

            <div class="d-flex align-items-center ms-lg-4 mt-3 mt-lg-0">
                @auth
                    <a href="{{ route('profile.index') }}" class="btn btn-outline-light me-2">{{ auth()->user()->name }}</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-light me-2">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Войти</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Регистрация</a>
                @endauth
            </div>            
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Категории товаров</h1>
    
    <div class="row">
        @foreach($categories as $category)
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-lg overflow-hidden">
                <a href="{{ route('category.show', $category->code) }}" class="text-decoration-none">
                    @if($category->image && file_exists(public_path('storage/' . $category->image)))
                        <img src="{{ asset('storage/' . $category->image) }}" 
                             class="card-img-top" 
                             alt="{{ $category->name }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="bi bi-image text-muted" style="font-size: 48px;"></i>
                        </div>
                    @endif
                    
                    <div class="card-body text-center">
                        <h5 class="card-title text-dark">{{ $category->name }}</h5>
                        <p class="card-text text-muted small">
                            {{ $category->description ? Str::limit($category->description, 60) : 'Товары для автомобилей' }}
                        </p>
                        <span class="badge bg-primary">{{ $category->products_count }} товаров</span>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('footer')
<footer class="py-4" style="background-color: #c46f00;">
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
                    <a href="{{ route('basket') }}" class="text-white mx-2 my-1">Корзина</a>
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