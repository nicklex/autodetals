@extends('layouts.master')

@section('title', 'Мои отзывы')

@section('header')
<header class="py-4" style="background-color: #c46f00;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <!-- Логотип -->
            <a href="{{ route('home') }}" class="navbar-brand text-white fw-bold d-flex align-items-center" style="font-size: 34px;">
                <img src="{{ asset('images/logo.jpg') }}" alt="AutoDetails" width="85" height="85" style="margin-right: 10px;">
                <i>AutoDetails</i>
            </a>
    
            <!-- Навигация -->
            <nav class="d-none d-lg-flex align-items-center flex-wrap">
                <a href="#categories" class="text-white mx-2 my-1">Категории</a>
                <a href="#about" class="text-white mx-2 my-1">О компании</a>
                <a href="#delivery" class="text-white mx-2 my-1">Доставка и оплата</a>
                <a href="#features" class="text-white mx-2 my-1">Преимущества</a>
                <a href="#contacts" class="text-white mx-2 my-1">Контакты</a>
                @auth
                    <a href="{{ route('basket') }}" class="text-white mx-2 my-1 position-relative">
                        Корзина
                        @if(session('cart'))
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count">
                                {{ array_sum(array_column(session('cart'), 'quantity')) }}
                            </span>
                        @endif
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-white mx-2 my-1">Корзина</a>
                @endauth
            </nav>

            <!-- Кнопки авторизации -->
            <div class="d-flex align-items-center ms-lg-4 mt-3 mt-lg-0">
                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="bi bi-person me-2"></i>Профиль</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-bag me-2"></i>Мои заказы</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @if(auth()->user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Админ-панель</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Выйти</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Войти</a>
                    <a href="{{ route('register') }}" class="btn btn-light text-dark">Регистрация</a>
                @endauth
            </div>            
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Мои отзывы</h1>
    
    @if($reviews->count() > 0)
        <div class="row">
            @foreach($reviews as $review)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">
                                    <a href="{{ route('product.show', $review->product_id) }}" class="text-decoration-none">
                                        {{ $review->product->name ?? 'Товар удален' }}
                                    </a>
                                </h5>
                                <div class="rating small">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                                    @endfor
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">{{ $review->created_at->format('d.m.Y') }}</small>
                           
                            </div>
                        </div>
                        
                        @if($review->advantages)
                        <div class="mb-2">
                            <strong class="text-success">✓ Достоинства:</strong>
                            <p class="mb-0">{{ $review->advantages }}</p>
                        </div>
                        @endif
                        
                        @if($review->disadvantages)
                        <div class="mb-2">
                            <strong class="text-danger">✗ Недостатки:</strong>
                            <p class="mb-0">{{ $review->disadvantages }}</p>
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <strong>Комментарий:</strong>
                            <p class="mb-0">{{ $review->comment }}</p>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('product.show', $review->product_id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-box"></i> К товару
                            </a>
                            <form action="{{ route('review.destroy', $review->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Удалить отзыв?')">
                                    <i class="bi bi-trash"></i> Удалить
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Пагинация -->
        <div class="d-flex justify-content-center mt-4">
            {{ $reviews->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-chat-left-text display-1 text-muted"></i>
            <h3 class="mt-4">У вас пока нет отзывов</h3>
            <p class="text-muted">Оставляйте отзывы к товарам, и они появятся здесь</p>
            <a href="{{ route('catalog') }}" class="btn btn-primary">
                <i class="bi bi-shop"></i> Перейти в каталог
            </a>
        </div>
    @endif
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